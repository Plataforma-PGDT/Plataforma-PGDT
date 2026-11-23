<?php

namespace Tests\Feature;

use App\Enums\StatusCandidatura;
use App\Enums\StatusDemanda;
use App\Models\CategoriaDemanda;
use App\Models\Candidatura;
use App\Models\MembroEquipe;
use App\Models\PapelEquipe;
use App\Models\ProjetoBase;
use App\Models\Solicitacao;
use App\Models\Subprojeto;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * RF14: consulta pública dos projetos de extensão sem login; participar exige login.
 */
class ConsultaPublicaTest extends TestCase
{
    use RefreshDatabase;

    private ProjetoBase $projeto;

    private Subprojeto $subprojeto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projeto = ProjetoBase::factory()->create(['titulo' => 'Escola Conectada']);
        $solicitacao = Solicitacao::factory()->triada()->comStatus(StatusDemanda::Priorizada)->create(['projeto_base_id' => $this->projeto->id]);
        $this->subprojeto = Subprojeto::factory()->create(['solicitacao_id' => $solicitacao->id, 'titulo' => 'Wi-Fi da biblioteca']);
    }

    public function test_visitante_ve_projetos_e_sub_projetos_com_vagas_sem_login(): void
    {
        $this->get('/projetos')
            ->assertOk()
            ->assertSee('Escola Conectada')
            ->assertSee('Wi-Fi da biblioteca')
            ->assertSee($this->subprojeto->coordenador->nome)
            ->assertSee('Quero participar');

        $this->get("/projetos/{$this->projeto->id}")
            ->assertOk()
            ->assertSee($this->projeto->descricao)
            ->assertSee('Wi-Fi da biblioteca');
    }

    public function test_pagina_publica_nao_mostra_dados_do_demandante(): void
    {
        $demandante = $this->subprojeto->solicitacao->demandante;

        $this->get('/projetos')
            ->assertDontSee($demandante->nome)
            ->assertDontSee($demandante->email)
            ->assertDontSee($demandante->telefone)
            ->assertDontSee($this->subprojeto->solicitacao->justificativa);
    }

    public function test_projeto_oculto_ou_inativo_fica_fora_da_pagina_publica(): void
    {
        $oculto = ProjetoBase::factory()->create(['titulo' => 'Projeto oculto', 'publico' => false]);
        $solicitacao = Solicitacao::factory()->triada()->comStatus(StatusDemanda::Priorizada)->create(['projeto_base_id' => $oculto->id]);
        Subprojeto::factory()->create(['solicitacao_id' => $solicitacao->id, 'titulo' => 'Sub-projeto do oculto']);
        $inativo = ProjetoBase::factory()->inativo()->create(['titulo' => 'Projeto encerrado']);

        $this->get('/projetos')
            ->assertDontSee('Projeto oculto')
            ->assertDontSee('Sub-projeto do oculto')
            ->assertDontSee('Projeto encerrado');

        $this->get("/projetos/{$oculto->id}")->assertNotFound();
        $this->get("/projetos/{$inativo->id}")->assertNotFound();
    }

    public function test_filtros_da_pagina_publica(): void
    {
        $this->get('/projetos?modalidade=presencial')->assertDontSee('Wi-Fi da biblioteca');
        $this->get('/projetos?modalidade=remoto&busca=biblioteca')->assertSee('Wi-Fi da biblioteca');
    }

    public function test_outros_perfis_veem_o_botao_desabilitado_com_a_explicacao(): void
    {
        $this->actingAs(Usuario::factory()->demandante()->create())->get('/projetos')
            ->assertSee('Só estudantes extensionistas podem se inscrever nos sub-projetos.');

        $this->actingAs(Usuario::factory()->estudante()->pendente()->create())->get('/projetos')
            ->assertSee('Seu cadastro ainda está em análise.');
    }

    /**
     * Critério de conclusão da Fase 5: visitante vê o projeto, clica em "Quero participar", faz login,
     * se candidata, é aprovado e aparece na equipe com um papel.
     */
    public function test_do_quero_participar_ate_a_equipe(): void
    {
        $estudante = Usuario::factory()->estudante()->create();
        $this->projeto->estudantes()->attach($estudante);
        $destino = "/oportunidades/{$this->subprojeto->id}";

        $this->get('/projetos')->assertSee(route('oportunidades.show', $this->subprojeto), false);

        $this->get($destino)->assertRedirect('/login');
        $this->get('/login')->assertSee('Entre com a sua conta de estudante para concluir a inscrição no sub-projeto.');

        $this->post('/login', ['email' => $estudante->email, 'password' => 'password'])->assertRedirect($destino);

        $this->get($destino)->assertOk()->assertSee('Confirmar candidatura');
        $this->post("{$destino}/candidatura")->assertRedirect($destino);

        $candidatura = Candidatura::sole();
        $papel = PapelEquipe::where('nome', 'Responsável Técnico')->firstOrFail();

        $this->actingAs($this->subprojeto->coordenador)
            ->patch("/subprojetos/{$this->subprojeto->id}/candidaturas/{$candidatura->id}/aprovar", ['papel_id' => $papel->id])
            ->assertSessionHasNoErrors();

        $this->assertSame(StatusCandidatura::Aprovada, $candidatura->fresh()->status);
        $this->assertSame('Responsável Técnico', MembroEquipe::sole()->papel->nome);

        $this->actingAs($estudante)->get("/subprojetos/{$this->subprojeto->id}")
            ->assertOk()
            ->assertSee($estudante->nome)
            ->assertSee('Responsável Técnico');
    }

    public function test_pagina_publica_fala_primeiro_com_a_comunidade(): void
    {
        CategoriaDemanda::create(['nome' => 'Site e presença digital', 'ativo' => true]);
        CategoriaDemanda::create(['nome' => 'Categoria inativa', 'ativo' => false]);
        CategoriaDemanda::create(['nome' => 'Outros', 'ativo' => true]);

        $this->get('/projetos')
            ->assertOk()
            ->assertSeeInOrder(['Sua comunidade ou instituição precisa de ajuda com tecnologia?', 'Como funciona', 'Escolhe o projeto de extensão', 'Projetos de extensão', 'É estudante da UFES? Veja as vagas abertas'])
            ->assertSee(route('cadastro.demandante'), false)
            ->assertSee('Site e presença digital')
            ->assertDontSee('Categoria inativa')
            ->assertDontSee('>Outros<', false);
    }

    public function test_visitante_escolhe_o_projeto_e_cai_no_login_com_o_projeto_guardado(): void
    {
        $destino = route('demandas.create', ['projeto' => $this->projeto->id]);

        $this->get('/projetos')->assertSee($destino, false);

        $this->get($destino)->assertRedirect('/login');
        $this->get('/login')
            ->assertSee('Entre com a conta da sua comunidade ou instituição para fazer o pedido.')
            ->assertSee(route('cadastro.demandante'), false);

        $demandante = Usuario::factory()->demandante()->create();
        $this->post('/login', ['email' => $demandante->email, 'password' => 'password'])->assertRedirect($destino);
    }

    public function test_demandante_logado_pede_ajuda_no_projeto_escolhido(): void
    {
        $demandante = Usuario::factory()->demandante()->create();

        $this->actingAs($demandante)->get('/projetos')
            ->assertSee(route('demandas.create', ['projeto' => $this->projeto->id]), false)
            ->assertSee('Quero pedir ajuda');

        $formulario = $this->actingAs($demandante)->get(route('demandas.create', ['projeto' => $this->projeto->id]))->assertOk();
        $this->assertMatchesRegularExpression('/value="'.$this->projeto->id.'" data-descricao="[^"]*" selected/', $formulario->getContent());
    }

    public function test_estudante_nao_ve_os_botoes_de_pedir_ajuda(): void
    {
        $this->actingAs(Usuario::factory()->estudante()->create())->get('/projetos')
            ->assertOk()
            ->assertDontSee('Quero pedir ajuda')
            ->assertDontSee('Pedir ajuda neste projeto')
            ->assertSee('Quero participar');
    }
}
