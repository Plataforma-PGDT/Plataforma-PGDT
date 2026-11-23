# Plano em Fases — Plataforma de Gestão de Demandas Tecnológicas

Projeto de extensão "Rede de Apoio Tecnológico às Escolas" (UFES/CCENS — Alegre-ES).
Base: TCC1 de Paulo Henrique Bressanini Rizzi e o documento "Funcionalidades do sistema (RF)" (RF01–RF13), mais RF14 e RF15 incluídos pelo autor.

**Stack:** PHP + Laravel (MVC) · MySQL · Blade + Bootstrap + JavaScript · Apache/XAMPP (local) · Git + GitHub

**Perfis:** Administrador · Coordenador projeto de extensão · Demandante (comunidade/instituição) · Estudante Extensionista

**Termos:**
- **Projeto de extensão (Projeto Base):** a linha de extensão cadastrada pelo coordenador (RF05).
- **Sub-projeto:** uma demanda aceita, vinculada a um projeto de extensão e executada por uma equipe de estudantes (RF09).

**Legenda de status:** ⬜ não iniciada · 🟨 em andamento · ✅ concluída

---

## Visão geral

| Fase | Nome | RFs | Status |
|---|---|---|---|
| 0 | Decisões e preparação do ambiente | — | ✅ |
| 1 | Fundação, autenticação e usuários | RF01, RF02, RF03, RF04 | ✅ |
| 2 | Cadastros de apoio e Projetos Base | RF05 | ✅ |
| 3 | Demandas: abertura e triagem | RF06, RF07 | ✅ |
| 4 | Rastreabilidade e comunicação | RF08 | ✅ |
| 5 | Sub-projetos, vitrine pública e equipe | RF09, RF10, RF11, RF14 | ✅ |
| 6 | Entregas, validação e relatório | RF12, RF13, RF15 | ✅ |
| 7 | Complementos (dashboard, notificações, parâmetros) | antigos RF04/RF22/RF23 do TCC | ✅ |
| 8 | Testes, validação com usuários e implantação | RNF01–RNF05 | 🟨 |
| 9 | Melhorias do sistema | imagem do projeto, Meu perfil, demanda pela coordenação, combos com pesquisa, equipe manual, cartões no celular | 🟨 |

Cada fase termina com algo **utilizável e demonstrável**. Uma fase só começa depois que o critério de conclusão da anterior for atingido.

---

## Fase 0 — Decisões e preparação do ambiente ✅

**Objetivo:** fechar as lacunas dos requisitos que afetam o modelo de dados e deixar o ambiente pronto.

### Decisões aprovadas em 2026-10-18
O autor aprovou todas as propostas abaixo; o ciclo de status foi ajustado para mudanças só manuais.

- [x] **Ciclo de status da demanda:** incluir `Recusada/Não viável`, `Cancelada` e `Aguardando ajustes`? Quem muda cada status e se a mudança é automática.
  - *Decisão:* `Cadastrada` → `Em Análise` → `Priorizada` → `Em Desenvolvimento` → `Em Validação` → `Concluída`, mais `Aguardando Ajustes`, `Recusada` e `Cancelada`.
  - **Todas as mudanças de status são manuais** (decidido pelo autor em 2026-10-18). O sistema não muda o status sozinho.
  - Coordenador e admin: `Em Análise`, `Priorizada`, `Recusada` (motivo obrigatório), `Em Desenvolvimento`, `Em Validação`, `Aguardando Ajustes` e `Concluída`.
  - `Cancelada`: demandante, só antes de `Em Desenvolvimento`; admin, a qualquer momento.
  - Ações como criar o sub-projeto, enviar uma entrega ou aceitar a solução não mudam o status, mas a tela **sugere** o próximo status ao coordenador (ex.: "Entrega recebida. Mudar para Em Validação?").
  - Toda mudança fica no histórico (RF08) com quem mudou e quando.
- [x] **Usuário pendente (estudante e demandante):** pode fazer login e ver uma tela de "aguardando aprovação" ou fica bloqueado? Recebe e-mail quando é aprovado?
  - *Decisão:* faz login e vê só a tela "Cadastro em análise". Recebe e-mail na aprovação e na rejeição (com o motivo). `Rejeitado` e `Inativo` veem uma mensagem clara ao tentar entrar.
- [x] **Estudante:** aceitar só `@edu.ufes.br` ou também `@ufes.br` (domínio dos servidores)? Exigir confirmação de e-mail?
  - *Decisão:* só `@edu.ufes.br`, com confirmação de e-mail antes de ir para a fila de aprovação do admin.
- [x] **Demanda fora de qualquer Projeto Base:** existe um projeto base "Geral/Outros"?
  - *Decisão:* sim, um Projeto Base "Demandas Gerais" criado pelo seeder; o admin define o coordenador dele.
- [x] **Complexidade (RF07):** é um campo da triagem? Quais valores?
  - *Decisão:* sim, campo obrigatório da triagem: `Baixa`, `Média`, `Alta`.
- [x] **Modalidade Remoto/Presencial (RF10):** fica no Projeto Base ou no sub-projeto (RF09)?
  - *Decisão:* no sub-projeto, com os valores `Remoto`, `Presencial` e `Híbrido`, porque cada demanda tem a sua necessidade.
- [x] **Vagas (RF09):** existe um número máximo de vagas por sub-projeto?
  - *Decisão:* o coordenador define as vagas de cada sub-projeto (mínimo 1). Quando a equipe completa, a candidatura fecha. Sem limite global.
- [x] **Acesso ao RF08:** estudantes da equipe e admin também veem o histórico e o chat?
  - *Decisão:* sim. O admin vê tudo; o estudante vê o histórico e o chat só dos sub-projetos em que é membro aprovado.
- [x] **Limites de upload (RF12):** tipos e tamanho máximo das entregas.
  - *Decisão:* PDF, DOCX, JPG, PNG e ZIP, até 20 MB por arquivo e 5 arquivos por entrega; links validados como URL.
- [x] **Kit de autenticação:** o Laravel Breeze usa Tailwind, e o plano usa Bootstrap.
  - *Decisão:* usar `laravel/ui` com o preset Bootstrap (`php artisan ui bootstrap --auth`) como base e adaptar os cadastros por perfil.

### Decisões respondidas anteriormente
- [x] **Coordenador x Professor Orientador:** são o mesmo perfil, chamado **Coordenador projeto de extensão**.
- [x] **Aprovação de estudantes (RF04):** o estudante também fica `Pendente` até o admin aprovar.
- [x] **Carga horária (RF15):** uma estimativa por **sub-projeto**, preenchida pelo Admin ou pelo Coordenador projeto de extensão e editável em qualquer status, mesmo depois de `Concluída`.
- [x] **Projeto Base x sub-projeto:** são entidades distintas. A demanda vinculada a um projeto de extensão vira um sub-projeto.

### Ambiente (atualizado em 2026-10-18)

| Ferramenta | Situação |
|---|---|
| PHP | ✅ 8.3.33 (winget), no PATH. Extensões ativadas: `openssl`, `pdo_mysql`, `mysqli`, `curl`, `zip`, `intl`, `gd`, `sodium`. Backup do `php.ini` original: `php.ini.bak-2026-10-18` |
| Composer | ✅ 2.10.3 em `%LOCALAPPDATA%\Composer\bin` (no PATH do usuário). O `composer.phar` foi conferido pelo SHA-256 oficial. O `Composer-Setup.exe` falhou no modo silencioso |
| XAMPP | ✅ 8.2.12 em `C:\xampp` (winget): Apache, MariaDB 10.4.32 e phpMyAdmin. O PHP 8.2 do XAMPP não está no PATH; o projeto usa o PHP 8.3 |
| Banco | ✅ banco `pgdt` criado (utf8mb4); conexão PDO do PHP 8.3 testada. Usuário `root` sem senha (padrão do XAMPP, só para uso local) |
| Node.js | ✅ v24.16.0 |
| Git | ✅ 2.49.0, já configurado com nome e e-mail |
| GitHub CLI (`gh`) | ➖ não instalado (opcional; o repositório pode ser criado pelo site) |

Instalação em uma máquina nova: [`docs/INSTALACAO.md`](docs/INSTALACAO.md).

Para ligar o banco: abrir o **XAMPP Control Panel** (`C:\xampp\xampp-control.exe`) e clicar em **Start** no MySQL.

### Tarefas
- [x] Instalar PHP 8.2+, Composer, Node.js, XAMPP (Apache + MySQL) e Git.
- [x] Criar o repositório no GitHub e definir o fluxo de branches.
  - Repositório: https://github.com/Plataforma-PGDT/Plataforma-PGDT (remoto `origin`), com a raiz na pasta `PGDT/`.
  - Fluxo: `main` sempre funcionando; cada entrega em uma branch `feature/<fase>-<assunto>` (ex.: `feature/fase1-cadastros`), juntada na `main` quando o critério da etapa for atingido.
- [x] Criar o projeto Laravel e configurar `.env` e o banco local.
  - Laravel 13.37 em `PGDT/sistema/`; o plano fica na raiz do repositório.
  - `.env`: `APP_NAME=PGDT`, `APP_LOCALE=pt_BR`, MySQL `pgdt` (root, sem senha); fuso `America/Sao_Paulo` em `config/app.php`.
  - `laravel/ui` 4.6 com Bootstrap 5 e telas de login, cadastro e recuperação de senha; Tailwind removido; `axios` adicionado; página inicial própria em Bootstrap.
  - Migrations padrão rodadas no MySQL; `php artisan serve` responde em `/`, `/login` e `/register`.
  - ⚠️ O OneDrive marca as pastas como "somente leitura" e o PHP recusa gravar nelas. O atributo foi removido de `bootstrap/cache` e `storage/`; se o erro "directory must be present and writable" voltar, repetir esse passo.
  - ℹ️ `php artisan db:show` falha no MariaDB 10.4 do XAMPP (falta `performance_schema`). É só o comando de diagnóstico; o sistema funciona.
- [x] Escolher o kit de autenticação e o Bootstrap: `laravel/ui` com o preset Bootstrap.
- [x] Revisar o DER completo com as entidades que faltam: [`docs/DER.md`](docs/DER.md) (diagrama Mermaid + dicionário de dados). Os 4 pontos da seção 4 foram aprovados pelo autor.

**Critério de conclusão:** decisões registradas neste documento; `php artisan serve` abre a página inicial conectada ao MySQL; repositório com o primeiro commit.

---

## Fase 1 — Fundação, autenticação e usuários ✅

**RFs:** RF01 Cadastro Acadêmico · RF02 Cadastro Institucional · RF03 Acesso e Recuperação · RF04 Administração

### Separação dos cadastros (decidido em 2026-10-18)

| Perfil | Como entra no sistema | Rota |
|---|---|---|
| Estudante | Autocadastro em formulário próprio, fica `Pendente` até o admin aprovar | `/cadastro/estudante` |
| Demandante | Autocadastro em formulário próprio, fica `Pendente` até o admin aprovar | `/cadastro/demandante` |
| Coordenador projeto de extensão | **Sem autocadastro.** O admin envia um convite por e-mail (link para definir a senha) ou troca o perfil de um usuário existente (RF04) | `/convite/{token}` |
| Administrador | Seeder inicial; outros admins só pelo painel | — |

Regras para evitar erro do usuário:
- **Login único** para todos: o perfil vem da conta, o usuário nunca escolhe o perfil ao entrar.
- **"Criar conta" abre uma tela de escolha** com dois cartões grandes e textos claros: "Sou estudante da UFES" e "Represento uma comunidade ou instituição". Não existe um campo "perfil" dentro do formulário.
- **Cada formulário mostra só os campos do seu perfil** e tem um título fixo ("Cadastro de Estudante") com o link "Não é o seu caso? Trocar tipo de cadastro".
- **O e-mail confirma a escolha:** e-mail institucional da UFES no formulário de demandante gera o aviso "Parece que você é da UFES. Quer se cadastrar como estudante?"; e-mail de fora da UFES no formulário de estudante é bloqueado com um link para o cadastro de demandante.
- **Links diretos para divulgação:** as comunidades/instituições recebem o link `/cadastro/demandante`; os estudantes, o link `/cadastro/estudante`.
- **Mesmo modelo de dados:** uma única tabela `usuarios` com `perfil`; os campos específicos ficam nas tabelas de cada perfil.

### Entregáveis
- [x] Layout base responsivo (menu por perfil, tela de login).
- [x] Tabelas (ver `docs/DER.md`): `usuarios` (perfil, status, telefone), `estudantes` (matrícula, curso), `demandantes` (comunidade/instituição, cargo), `coordenadores`, `escolas_instituicoes`, `cursos`, `convites`.
- [x] Tela "Criar conta" com a escolha do tipo de cadastro (Estudante / Demandante).
- [x] RF01: formulário do estudante com validação de domínio; perfil Estudante atribuído automaticamente; status `Pendente`.
- [x] RF02: formulário do demandante (comunidade/instituição, cargo, telefone com máscara); status `Pendente`.
- [x] Cadastro do Coordenador projeto de extensão por convite do admin (e-mail com link de uso único e prazo de validade); a conta já nasce `Ativo`.
- [x] RF03: login, logout, "esqueci minha senha" com token por e-mail e redefinição.
- [x] RF04: painel do admin com filtros (nome, e-mail, perfil, status). O admin aprova os usuários, altera o status do usuário (`Ativo`, `Inativo`, `Pendente`, `Rejeitado`) e troca o papel/perfil dos usuários.
  - Só usuários `Ativo` conseguem usar o sistema; `Inativo` e `Rejeitado` não fazem login.
  - Rejeitar e inativar pedem confirmação; a troca de perfil mostra o perfil atual e o novo antes de salvar.
- [x] Middleware/policies de controle de acesso por perfil (RNF04).
- [x] Seeder com um usuário administrador inicial.

### Como foi implementado (2026-10-18)
- **Código:** branch `feature/fase1-usuarios`. Model `Usuario` (tabela `usuarios`) no lugar do `User` padrão; enums `Perfil`, `StatusUsuario` e `TipoEscola` em `app/Enums`.
- **Acesso:** middleware `perfil:admin,...` (`VerificaPerfil`) e `ativo` (`ContaAtiva`): `Pendente` vai para "Cadastro em análise"; `Inativo`/`Rejeitado` são desconectados com a mensagem e o motivo. O login também bloqueia essas contas.
- **Rotas:** `/cadastro` (escolha), `/cadastro/estudante`, `/cadastro/demandante`, `/convite/{token}`, `/painel`, `/admin/usuarios`, `/admin/convites`. `/register` e `/home` redirecionam para as rotas novas.
- **Convite:** token de 64 caracteres; no banco fica só o SHA-256. Validade de 7 dias, uso único; um convite novo para o mesmo e-mail cancela o anterior; o admin pode cancelar.
- **E-mails:** confirmação de e-mail (estudante), aprovação, rejeição com motivo, convite e recuperação de senha, em pt-BR. Em ambiente local o `MAIL_MAILER=log` grava os e-mails em `storage/logs/laravel.log`.
- **Seeders:** `CursoSeeder` (16 cursos do campus de Alegre) e `AdminSeeder` (usa `ADMIN_NOME`, `ADMIN_EMAIL` e `ADMIN_PASSWORD` do `.env`; sem senha, gera uma aleatória e mostra no terminal).
- **Testes:** 34 testes de feature (`CadastroTest`, `LoginTest`, `AdminUsuariosTest`, `ConviteTest`) passando no SQLite em memória.
- **Teste manual no navegador:** cadastro de demandante com comunidade/instituição nova, aviso de e-mail da UFES, máscara de telefone, tela "Cadastro em análise", login do admin, aprovação com e-mail registrado no log e layout no celular.

**Critério de conclusão:** os 4 perfis conseguem se cadastrar ou ser criados, fazer login e ver apenas o seu menu; o admin aprova um estudante e um demandante pendentes.

---

## Fase 2 — Cadastros de apoio e Projetos Base ✅

**RF:** RF05 Cadastro de Projetos Base

### Entregáveis
- [x] Tabelas de domínio: `tipologias`, `categorias_demanda`, `prioridades` (com seeders).
- [x] CRUD de Projetos Base (título, descrição, escopo preliminar, datas, tipologia, coordenador responsável).
- [x] Listagem de Projetos Base (usada pelo Demandante na Fase 3 e pela página pública do RF14).
- [x] Permissão: só o Coordenador projeto de extensão e o Admin cadastram e editam.

### Como foi implementado (2026-10-21)
- **Código:** branch `feature/fase2-projetos-base`. Migration `2026_10_21_000001_create_projetos_base_tables`; models `Tipologia`, `CategoriaDemanda`, `Prioridade` (trait `ItemDeDominio`) e `ProjetoBase`; `ProjetoBasePolicy`.
- **Rotas:** `/projetos-base` (lista, novo, detalhes, editar) e `/admin/cadastros/{tipologias|categorias|prioridades}`.
- **Cadastros de apoio (admin):** incluir, renomear e ativar/inativar; prioridades têm `ordem` (menor primeiro). Nenhum item é apagado, para não quebrar os registros que já o usam.
- **Seeders:** `DominioSeeder` (tipologias: Programa, Projeto, Curso, Oficina, Evento, Prestação de Serviço; 7 categorias; prioridades Urgente, Alta, Média, Baixa) e `ProjetoBaseSeeder` ("Demandas Gerais").
- **Regras:**
  - O coordenador que cadastra vira o responsável; só o admin escolhe ou troca o coordenador (precisa ser um coordenador `Ativo`).
  - O coordenador edita só os projetos que coordena; o admin edita todos.
  - Projeto Base não é apagado: desmarcar "Ativo" tira o projeto da seleção de demandas (`ProjetoBase::disponiveis()`). "Público" controla a página do RF14.
  - Demandante e estudante veem a lista "Projetos de extensão" só com os ativos, sem botões de edição.
  - "Demandas Gerais" nasce sem coordenador; o admin vê um aviso na lista até definir quem coordena.
  - A data de término é opcional e não pode ser anterior à de início. Uma tipologia inativada continua valendo nos projetos que já a usam.
- **Testes:** 19 testes novos (`ProjetoBaseTest`, `DominioTest`); 53 no total, todos passando.
- **Teste manual no navegador (admin):** lista com o aviso de "Demandas Gerais" sem coordenador, formulário com validação em pt-BR, detalhes, edição pré-preenchida, cadastros de apoio e layout no celular e no computador. Corrigidos: as chaves "Público"/"Ativo" voltavam marcadas depois de um erro de validação, e a ordem das prioridades aparecia sem rótulo.

**Critério de conclusão:** um coordenador cadastra um Projeto Base e ele aparece disponível para seleção.

---

## Fase 3 — Demandas: abertura e triagem ✅

**RFs:** RF06 Abertura de Solicitações · RF07 Triagem e Parametrização

### Entregáveis
- [x] Tabelas `solicitacoes` e `anexos` (um para muitos).
- [x] RF06: formulário do Demandante com seleção do Projeto Base, título (máx. 100 caracteres), descrição, justificativa, público afetado e upload drag-and-drop (PDF/JPG/PNG, até 5 MB).
- [x] Armazenamento seguro dos arquivos (`storage`, fora do diretório público, com download autorizado).
- [x] "Minhas demandas" para o Demandante.
- [x] RF07: fila do coordenador (status `Cadastrada`), tela de detalhes, definição de categoria, prioridade e complexidade.
- [x] Transições de status iniciais: Cadastrada → Em Análise → Priorizada (e Recusada, se decidido na Fase 0).

### Como foi implementado (2026-10-21)
- **Código:** branch `feature/fase3-demandas`. Migration `2026_10_21_000002_create_solicitacoes_tables`; enums `StatusDemanda` e `Complexidade`; models `Solicitacao` e `Anexo`; `SolicitacaoPolicy`; `SolicitacaoController`.
- **Rotas:** `/demandas` (lista: "Minhas demandas" para o demandante, fila para coordenador e admin), `/demandas/create`, `/demandas/{id}` (detalhes), `PATCH /demandas/{id}/triagem`, `PATCH /demandas/{id}/status` e `/demandas/{id}/anexos/{anexo}` (download).
- **Abertura (RF06):** o demandante escolhe um Projeto Base ativo (a descrição do projeto aparece ao escolher), com contador no título. A comunidade/instituição é copiada do cadastro do demandante. Anexos são opcionais: até 5 arquivos PDF/JPG/PNG de até 5 MB, conferidos no navegador (tipo, tamanho, quantidade, repetidos) e de novo no servidor; os erros citam o nome do arquivo.
- **Arquivos:** gravados no disco `local` (`storage/app/private/solicitacoes/{id}`), fora da pasta pública, com nome aleatório. O download passa pela policy (só quem vê a demanda) e devolve o nome original. Se a gravação da demanda falhar, os arquivos já salvos são apagados.
- **Fila (RF07):** abre nas demandas `Cadastrada`; filtros por título, status, prioridade e projeto. Ordem: prioridade (pela `ordem` do cadastro de apoio; sem prioridade por último) e depois a mais antiga.
- **Triagem:** categoria, prioridade e complexidade (`Baixa`, `Média`, `Alta`), todas obrigatórias; grava quem classificou e quando. Pode ser revista enquanto a demanda está `Cadastrada`, `Em Análise` ou `Priorizada`. Salvar a triagem não muda o status.
- **Status (sempre manual):** coordenador e admin: `Cadastrada` → `Em Análise` ou `Recusada`; `Em Análise` → `Priorizada` (só depois da triagem) ou `Recusada`; `Priorizada` → `Em Análise` ou `Recusada`; `Recusada` → `Em Análise` (reabrir). `Recusada` e `Cancelada` exigem motivo, que o demandante vê. O demandante cancela até `Priorizada`; o admin cancela a qualquer momento. A tela sugere o próximo passo ao coordenador.
- **Painel:** admin e coordenador veem o número de demandas novas; o demandante vê as 5 últimas demandas e o botão "Nova demanda". Menu: "Demandas" (admin e coordenador) e "Minhas demandas" (demandante).
- **Testes:** 24 testes novos (`SolicitacaoTest`); 77 no total, todos passando.
- **Teste manual no navegador:** coordenador cadastrou um Projeto Base de teste; demandante de teste criado pelo cadastro e aprovado pelo admin; demanda aberta com dois anexos pelo arrastar-e-soltar (arquivo `.xlsx` e PDF de 6 MB barrados no navegador); download conferido; admin passou a demanda por Em Análise → classificação → Priorizada; demandante viu o status e a classificação no celular. Corrigidos: tamanho do arquivo no formato brasileiro ("1,4 MB") e o cancelamento do demandante passou a ficar recolhido. A triagem no navegador foi feita como admin (a senha do coordenador de teste não está registrada); o fluxo do coordenador está coberto pelos testes.
- **Conta de teste:** o e-mail e a senha do demandante de teste estão no `sistema/.env` local (`TESTE_DEMANDANTE_*`), que não vai para o GitHub.

**Critério de conclusão:** um demandante abre uma demanda com anexo e um coordenador a classifica e prioriza.

---

## Fase 4 — Rastreabilidade e comunicação ✅

**RF:** RF08

### Entregáveis
- [x] Tabelas `historicos` e `mensagens`.
- [x] Registro automático no histórico a cada mudança de status ou ação relevante (via Observer/Event do Laravel).
- [x] Barra de progresso visual na tela da demanda.
- [x] Linha do tempo cronológica do histórico.
- [x] Chat da demanda (autor, perfil, data/hora, texto) com o botão "Enviar Comentário".
- [x] Acesso restrito aos envolvidos (policy).

### Como foi implementado (2026-10-21)
- **Código:** branch `feature/fase4-rastreabilidade`. Migration `2026_10_21_000003_create_historicos_mensagens_tables`; enum `TipoHistorico`; models `Historico` e `Mensagem`; `SolicitacaoObserver`; `MensagemController`; parciais `_progresso`, `_linha-do-tempo` e `_chat` na tela da demanda.
- **Histórico automático:** o `SolicitacaoObserver` grava a abertura ("Demanda aberta"), cada mudança de status (de/para e o motivo, quando há) e cada classificação ("Demanda classificada" e, nas revisões, "Classificação revista" só com o que mudou). Guarda os nomes da categoria e da prioridade, para a linha do tempo não mudar se o admin renomear um item. Status e histórico são gravados na mesma transação (`updateOrFail`). O histórico não pode ser editado nem apagado (o model recusa).
- **Demandas antigas:** a migration criou o registro "Demanda aberta" para as demandas que já existiam; as mudanças de status feitas antes desta fase não têm registro.
- **Barra de progresso:** Cadastrada → Em Análise → Priorizada → Em Desenvolvimento → Em Validação → Concluída. "Aguardando Ajustes" aparece na etapa de validação. Recusada ou cancelada, a barra marca com ✕ a etapa em que a demanda parou (tirada do histórico). No celular, só o nome da etapa atual aparece.
- **Linha do tempo:** na coluna da direita, da mais antiga para a mais recente, com quem fez e quando.
- **Chat:** rota `POST /demandas/{id}/mensagens` (limite de 20 envios por minuto). Mostra autor, perfil, data/hora e texto; as mensagens do próprio usuário ficam à direita. Até 2.000 caracteres. O perfil do autor é guardado na mensagem.
- **Acesso:** quem vê a demanda (demandante dela, coordenador do projeto e admin) vê o histórico e o chat; o estudante entra na Fase 5, quando houver equipe.
- **Testes:** 16 testes novos (`RastreabilidadeTest`); 93 no total, todos passando.
- **Teste no navegador:** como demandante, enviei um comentário na demanda de teste; como admin, respondi, passei a demanda para Em Análise e de volta para Priorizada, e as duas mudanças apareceram na linha do tempo. Conferido também no celular. Corrigido: espaços em branco a mais dentro do balão da mensagem.

### Decisões de implementação (aprovadas pelo autor em 2026-10-21)
- (a) Mensagens do chat não podem ser editadas nem apagadas, para a conversa valer como registro.
- (b) O chat fica só para leitura quando a demanda está `Concluída` ou `Cancelada`; em `Recusada` continua aberto, porque a demanda pode ser reaberta.
- (c) Todos os envolvidos veem na linha do tempo o nome de quem fez cada ação (inclusive o demandante, que antes não via quem fez a triagem).
- (d) `mensagens.perfil` guarda o perfil do autor no envio; `historicos.usuario_id` aceita nulo para mudanças feitas fora de uma sessão de usuário (DER atualizado).
- (e) Sem aviso de nova mensagem por e-mail ou no painel nesta fase; as notificações ficam para a Fase 7.

**Critério de conclusão:** demandante e coordenador trocam mensagens em uma demanda e veem cada mudança de status registrada.

---

## Fase 5 — Sub-projetos, vitrine pública e equipe ✅

**RFs:** RF09 Criação de Sub-projetos · RF10 Vitrine de Oportunidades · RF11 Gestão de Lotação · RF14 Consulta Pública de Projetos

### Entregáveis
- [x] Tabelas `subprojetos` (1:1 com a solicitação; o Projeto Base vem dela; data de início, data de fim e carga horária estimada), `candidaturas`, `papeis_equipe`, `membros_equipe` (com papel e `removido_em`).
- [x] RF09: converter a demanda em sub-projeto do projeto de extensão (campos pré-preenchidos, escopo obrigatório, data de início e de fim, carga horária estimada, vagas, modalidade); o coordenador logado vira o responsável; ao salvar, a tela sugere mudar a demanda para `Em Desenvolvimento` (mudança manual).
  - A carga horária estimada é preenchida pelo Admin ou pelo Coordenador projeto de extensão e pode ser alterada em qualquer status, inclusive depois de `Concluída`.
  - Cada alteração da carga horária fica registrada no histórico (RF08): valor anterior, valor novo, quem alterou e quando.
- [x] RF10: vitrine em cards com busca, filtro por modalidade e candidatura com um clique.
- [x] RF11: tabela de candidaturas pendentes (Aprovar/Recusar), equipe com papéis (Membro Desenvolvedor, Responsável Técnico, funções de campo) e remoção de membros.
- [x] RF14: página pública (`/projetos`), sem login, com os projetos de extensão cadastrados e os sub-projetos com vagas abertas (busca e filtros iguais aos da vitrine).
  - Mostra só dados públicos: título, descrição, coordenador, modalidade, período e vagas. Contatos do demandante, anexos e chat ficam fora (LGPD).
  - O botão "Quero participar" leva ao login. Depois do login, a pessoa volta para o mesmo projeto e conclui a inscrição com um clique. A tela de login tem o link "Ainda não tem conta? Criar conta".
  - Só o perfil Estudante com status `Ativo` pode se inscrever; para os outros o botão aparece desabilitado com a explicação.

### Como foi implementado (2026-10-21)
- **Código:** branch `feature/fase5-subprojetos`. Migration `2026_10_21_000004_create_subprojetos_tables` (cria também os 3 papéis iniciais); enums `Modalidade` e `StatusCandidatura`; models `Subprojeto`, `Candidatura`, `PapelEquipe` e `MembroEquipe`; observers `SubprojetoObserver` e `MembroEquipeObserver`; `SubprojetoPolicy`; controllers `SubprojetoController`, `CandidaturaController`, `MembroEquipeController`, `OportunidadeController` e `ProjetoPublicoController`.
- **RF09:** na demanda "Priorizada", o card "Sub-projeto" leva ao formulário (`/demandas/{id}/subprojeto/criar`) com título e escopo vindos da demanda. Depois de salvar, a tela da demanda sugere "Em Desenvolvimento" (já vem escolhido na caixa de status). A tela do sub-projeto (`/subprojetos/{id}`) mostra candidaturas pendentes, equipe e dados; "Editar" altera os dados e a opção "Receber candidaturas".
- **Carga horária:** cada alteração grava "Carga horária estimada alterada de X h para Y h" na linha do tempo, com `{"de": X, "para": Y}`, quem e quando. Com a demanda encerrada, a tela de edição mostra só esse campo.
- **RF10:** menu "Oportunidades" do estudante (`/oportunidades`): cards com busca (título ou escopo), filtro por modalidade e por projeto. O card leva à página do sub-projeto, onde "Confirmar candidatura" envia com um clique. "Meus sub-projetos" mostra as equipes e as candidaturas com a situação.
- **RF11:** aprovar (escolhendo o papel), recusar, trocar papel e remover membro, com confirmação. A aprovação trava o sub-projeto no banco para duas aprovações simultâneas não passarem das vagas. O admin cadastra novos papéis em "Cadastros de apoio" → "Papéis da equipe".
- **RF14:** `/projetos` e `/projetos/{id}` sem login, com o link "Projetos e vagas" no menu dos visitantes e na página inicial. "Quero participar" leva ao login (com o aviso "Entre com a sua conta de estudante para concluir a inscrição") e, depois do login, de volta ao sub-projeto. Quem não pode se inscrever vê o botão desabilitado com o motivo.
- **Painel:** admin e coordenador veem o total de candidaturas pendentes; o estudante vê as vagas abertas e as suas equipes. Menu "Sub-projetos" para admin e coordenador.
- **Testes:** 38 testes novos (`SubprojetoTest`, `EquipeTest`, `ConsultaPublicaTest`), incluindo o critério de conclusão de ponta a ponta; 127 no total, todos passando.
- **Teste no navegador:** como admin, criei o sub-projeto da demanda de teste (presencial, 2 vagas, 40 h). Como visitante, cliquei em "Quero participar", criei a estudante de teste pelo cadastro, confirmei o e-mail (link no `laravel.log`) e, ainda pendente, vi o botão desabilitado com o motivo. Depois da aprovação pelo admin, entrei, voltei ao sub-projeto e me candidatei com um clique. Como admin, aprovei como Responsável Técnico, corrigi a carga horária para 48 h e conferi as duas ações na linha do tempo. Conferido também no celular. A conta de teste da estudante está anotada no `.env` local.
- **Ajuste durante o teste:** quando o admin cria o sub-projeto, o responsável passa a ser o coordenador do projeto (a página pública mostraria "Administrador" como coordenação).

### Decisões de implementação (aprovadas pelo autor em 2026-10-21)
- (a) O sub-projeto só é criado com a demanda "Priorizada", uma vez por demanda. Criar não muda o status; "Em Desenvolvimento" só aparece na lista depois que o sub-projeto existe.
- (b) Responsável: o coordenador que cria. Se quem cria é o admin, fica o coordenador do Projeto Base (ou o admin, se o projeto não tiver coordenador). O responsável também gerencia a demanda e a equipe, mesmo que o admin troque depois o coordenador do Projeto Base.
- (c) Com a demanda "Concluída", "Cancelada" ou "Recusada", o sub-projeto sai da vitrine e os dados e a equipe não mudam mais; só a carga horária pode ser corrigida.
- (d) O sub-projeto aparece na vitrine enquanto "Receber candidaturas" estiver marcado, houver vagas livres, a demanda estiver em andamento e o Projeto Base estiver ativo. Com a equipe completa, sai da vitrine sozinho e volta se alguém sair. As vagas não podem ficar abaixo do número de membros.
- (e) O estudante pode cancelar a candidatura enquanto ela não foi avaliada e se candidatar de novo depois. Quem foi recusado ou removido não se candidata de novo ao mesmo sub-projeto.
- (f) Na aprovação, o papel sugerido é "Membro Desenvolvedor". Entrada, troca de papel e remoção de membros vão para a linha do tempo da demanda; as candidaturas não (ficam na tela do sub-projeto).
- (g) O estudante da equipe vê a demanda (descrição, anexos, linha do tempo e chat) e conversa no chat, mas não vê o e-mail e o telefone do demandante. Removido da equipe, perde o acesso.
- (h) A página pública mostra título, escopo, projeto, coordenador, modalidade, período e vagas. A carga horária aparece só na vitrine, para quem está logado. Projetos ocultos (`publico` desmarcado) e os seus sub-projetos ficam fora.
- (i) O escopo vem preenchido com a descrição da demanda e o formulário avisa que ele vai para a página pública, para não incluir dados pessoais.

**Critério de conclusão:** um visitante sem login vê os projetos, clica em "Quero participar", faz login, se candidata, é aprovado e aparece na equipe com um papel.

---

## Fase 6 — Entregas, validação e relatório ✅

**RFs:** RF12 Submissão de Entregas · RF13 Validação e Aceite (antes "Homologação") · RF15 Relatório de Demandas por Extensionista

### Entregáveis
- [x] Tabelas `entregas`, `entrega_arquivos` (até 5 por entrega) e `validacoes` (etapa, decisão, feedback, autor, data).
- [x] RF12: formulário com tipo de entrega dinâmico (link/repositório, relatório técnico, arquivos/fotos), validação de formato e tamanho; só membros aprovados enviam.
- [x] RF13 etapa 1: coordenador escolhe "Aprovar Tecnicamente" ou "Devolver para Ajustes" (feedback obrigatório).
- [x] RF13 etapa 2: demandante escolhe "Aceitar Solução" ou "Reportar Pendência" (o admin e o coordenador também podem registrar a decisão em nome dele).
  - O nome "homologar" foi trocado por "aceite" (pedido do autor em 2026-10-21), porque os projetos de extensão não são só de programação.
- [x] Depois do aceite, o coordenador ou o admin muda a demanda para `Concluída`; depois de uma devolução ou pendência, muda para `Aguardando Ajustes` e a demanda volta para a equipe. As mudanças são manuais, com sugestão na tela.
- [x] Tela de mudança de status (coordenador e admin) com confirmação e registro no histórico.
- [x] RF15: relatório de demandas executadas por extensionista. Para cada sub-projeto de que o estudante participou: título, projeto de extensão, comunidade/instituição, papel do estudante, **data de início**, **data de fim** e **carga horária estimada** do sub-projeto, com o total de horas no final.
  - Os valores vêm do sub-projeto (RF09); se o Admin ou o coordenador corrigir a carga horária, o relatório já sai com o valor novo.
  - Filtros: estudante, período, Projeto Base e status (por padrão, só demandas `Concluída`).
  - Exportação em PDF (ex.: `barryvdh/laravel-dompdf`).
  - Acesso: o admin vê todos; o coordenador vê os estudantes dos seus projetos; o estudante vê só o próprio relatório.

### Como foi implementado (2026-10-21)
- **Código:** branch `feature/fase6-entregas`. Migration `2026_10_21_000005_create_entregas_tables`; enums `TipoEntrega`, `SituacaoEntrega`, `EtapaValidacao` e `DecisaoValidacao`; models `Entrega`, `EntregaArquivo` e `Validacao`; observers `EntregaObserver` e `ValidacaoObserver`; `EntregaPolicy`; controllers `EntregaController` e `RelatorioController`. Pacote novo: `barryvdh/laravel-dompdf` 3.1 (exportação do RF15).
- **RF12:** na tela do sub-projeto, o card "Entregas" lista as entregas numeradas e tem o botão "Nova entrega" para os membros da equipe ("Registrar entrega da equipe" para o coordenador e o admin). O formulário (`/subprojetos/{id}/entregas/criar`) muda conforme o tipo: link/repositório (endereço `http`/`https` obrigatório), relatório técnico (PDF, DOCX ou Excel) ou arquivos/fotos (PDF, DOCX, Excel, JPG, PNG ou ZIP), até 5 arquivos de 20 MB, com arrastar e soltar. Os arquivos ficam fora da pasta pública e só são baixados por quem vê a entrega. O formulário mostra os comentários das entregas devolvidas ou com pendência.
- **RF13:** a tela da entrega mostra o conteúdo, os arquivos e as decisões. O coordenador (ou o admin) escolhe "Aprovar Tecnicamente" ou "Devolver para Ajustes"; depois o demandante escolhe "Aceitar Solução" ou "Reportar Pendência". O admin e o coordenador também veem esses dois botões, para registrar a decisão quando o demandante confirma por outro meio (reunião, telefone, e-mail); nesse caso a linha do tempo mostra "(registrado pela coordenação em nome do demandante)". Devolver e reportar pendência exigem comentário. Cada envio e cada decisão vão para a linha do tempo da demanda, com o comentário.
- **Status:** a caixa de status passa a oferecer "Em Validação" (com uma entrega enviada), "Aguardando Ajustes", "Em Desenvolvimento" e "Concluída" (só com a solução aceita). A tela sugere o próximo status pela última entrega ("Entrega recebida. Mudar para Em Validação?") e já deixa essa opção escolhida; a mudança continua manual e com confirmação.
- **RF15:** menu "Relatório" (admin e coordenador) e "Meu relatório" (estudante), em `/relatorios/extensionistas`. Uma seção por estudante com título, projeto de extensão, comunidade/instituição, papel, início, fim e carga horária estimada de cada sub-projeto, e o total. Filtros de estudante, período (sub-projetos em andamento entre as datas), Projeto Base e status (padrão: "Concluída"). "Exportar PDF" gera o mesmo relatório em A4 paisagem.
- **Painel:** admin e coordenador veem as entregas para validar; o demandante vê um aviso quando há entrega aguardando o seu aceite; o estudante tem o atalho para o relatório. A lista de sub-projetos marca os que têm entrega para validar.
- **Testes:** 38 testes novos (`EntregaTest`, `ValidacaoTest`, `RelatorioTest` e `CicloCompletoTest`, que percorre o critério de conclusão de ponta a ponta); 165 no total, todos passando. Migrations `2026_10_21_000005_create_entregas_tables` e `2026_10_27_000001_add_numero_to_entregas_table` rodadas no MySQL local.
- **Teste no navegador (2026-10-27, feito pelo autor):** a demanda de teste foi para "Em Desenvolvimento"; a estudante enviou a entrega; o admin aprovou tecnicamente e registrou o aceite em nome do demandante; a demanda foi concluída e o relatório da estudante foi conferido. Ajuste durante o teste: as caixas de confirmação do navegador viraram modal do Bootstrap.

### Decisões de implementação (revisadas e aprovadas pelo autor em 2026-10-27)
- (a) **Várias entregas ao mesmo tempo, numeradas:** cada entrega recebe um número na ordem de envio dentro do sub-projeto (Entrega nº 1, nº 2...) e é avaliada separadamente. A equipe continua enviando enquanto a demanda estiver em execução, mesmo depois de uma entrega aceita. *(Mudou na revisão: antes era uma entrega por vez.)*
- (b) **Quem envia:** os membros ativos da equipe e, em nome dela, o coordenador ou o admin (ex.: material recebido por e-mail). A linha do tempo indica "(enviada pela coordenação em nome da equipe)". As entregas são aceitas com a demanda em "Em Desenvolvimento", "Em Validação" ou "Aguardando Ajustes". *(Mudou na revisão.)*
- (c) O demandante só vê a entrega depois da aprovação técnica.
- (d) O aceite é do demandante; o admin e o coordenador também podem registrar o aceite ou a pendência em nome dele, com indicação na linha do tempo. A validação técnica é do coordenador do projeto, do responsável pelo sub-projeto ou do admin.
- (e) "Concluída" só com pelo menos uma entrega aceita e "Em Validação" só com uma entrega enviada; os demais status de execução ficam livres. Se ainda houver entregas em avaliação, a tela avisa antes de concluir. O admin continua podendo cancelar a qualquer momento.
- (f) Coluna `entregas.situacao` com o resultado da última validação; as validações não podem ser editadas nem apagadas.
- (g) **Tipos de arquivo:** "Arquivos ou fotos" aceita PDF, DOCX, **Excel (XLSX e XLS)**, JPG, PNG e ZIP; o relatório técnico aceita PDF, DOCX ou **Excel (XLSX e XLS)**; o link não guarda arquivos. *(Excel incluído na revisão, nos dois tipos.)*
- (h) **Relatório:** o filtro de período conta só os sub-projetos que **começaram e terminaram** entre as datas. Membros removidos não aparecem; o coordenador vê os estudantes das equipes dos seus projetos e dos sub-projetos de que é responsável; o demandante não acessa o relatório. *(Período mudou na revisão.)*
- (i) Sem aviso por e-mail das entregas e decisões nesta fase (fica para a Fase 7).

**Critério de conclusão:** o ciclo completo funciona de ponta a ponta: demanda → sub-projeto → entrega → aprovação técnica → aceite → concluída; o relatório do estudante mostra esse sub-projeto com datas e carga horária.

---

## Fase 7 — Complementos ✅

Itens do TCC que não estão no documento de RF, mas que agregam valor. O autor escolheu em 2026-10-27 os itens 1, 2 e 4; o log de auditoria fica como trabalho futuro.

- [x] Dashboard gerencial (demandas por status, categoria, comunidade/instituição e tempo médio de atendimento).
- [x] Notificações por e-mail (cadastro aprovado, mudança de status, nova mensagem, candidatura aprovada, entrega devolvida).
- [x] Parâmetros gerais configuráveis pelo admin.

### Como foi implementado (2026-10-27)
- **Código:** branch `feature/fase7-complementos`. Migration `2026_10_27_000002_create_parametros_table`; model `Parametro`; notificação `Aviso`; observers novos `MensagemObserver` e `CandidaturaObserver` (os de demanda, entrega e validação passaram a enviar avisos); controllers `Admin\ParametroController` e `DashboardController`. Nenhum pacote novo.
- **Parâmetros gerais:** menu "Configurações" → "Parâmetros gerais" (só admin), em `/admin/parametros`. Parâmetros: nome da rede (rodapé, página inicial, assinatura dos e-mails e PDF do relatório), e-mail de contato (rodapé e e-mails), avisos por e-mail ligados ou desligados, quantidade e tamanho dos anexos da demanda (padrão 5 arquivos de 5 MB) e dos arquivos da entrega (padrão 5 de 20 MB). A tela mostra os limites do servidor (`upload_max_filesize` e `post_max_size` do PHP) e não aceita valores acima deles. A tabela guarda só o que foi alterado, com quem alterou; sem alteração, valem os padrões.
- **Avisos por e-mail:** um e-mail por acontecimento, com botão para a tela certa, assinatura da rede e e-mail de contato:
  - nova demanda → coordenação (coordenador do Projeto Base e responsável pelo sub-projeto; sem nenhum, os admins);
  - mudança de status → demandante e equipe (com o motivo, em "Recusada" e "Cancelada");
  - mensagem no chat → demandante, coordenação e equipe;
  - candidatura nova → coordenação; aprovada ou recusada → estudante;
  - entrega enviada → coordenação; aprovada tecnicamente → demandante (chamado para o aceite) e equipe; devolvida → equipe; aceita ou com pendência → equipe e coordenação (com o comentário).
  - Quem fez a ação não recebe o aviso, e contas inativas não recebem nada. Os e-mails vão pela fila depois de a ação ser gravada (`php artisan queue:work`, ver `docs/INSTALACAO.md`).
- **Dashboard:** menu "Dashboard" (admin e coordenador), em `/dashboard`. Totais (demandas, em triagem, em execução, concluídas), tempo médio de atendimento (da abertura até "Concluída") e tempo médio da primeira resposta (da abertura até sair de "Cadastrada"), calculados pela linha do tempo; barras por status, categoria, comunidade ou instituição (as 10 com mais demandas e "Outras") e projeto de extensão. Filtros: período de abertura e Projeto Base. O coordenador vê só as demandas que já vê na lista.
- **Testes:** 22 testes novos (`ParametroTest`, `NotificacaoTest` e `DashboardTest`); 187 no total, todos passando. Migration rodada no MySQL local.

### Decisões de implementação (aprovadas pelo autor em 2026-10-27)
- (a) Os e-mails de cadastro, convite e recuperação de senha continuam sempre ligados; o parâmetro "Enviar avisos por e-mail" desliga só os avisos novos da Fase 7.
- (b) Cada mensagem do chat gera um e-mail para os outros participantes (sem resumo diário).
- (c) Os admins só recebem avisos das demandas sem coordenador (ex.: "Demandas Gerais"); nas demais, quem recebe é a coordenação.
- (d) Avisos extras, além dos listados no TCC: nova demanda, nova candidatura, entrega enviada, aprovação técnica e aceite.
- (e) Os avisos vão pela fila do banco; em produção, o `queue:work` precisa ficar rodando (entra no deploy da Fase 8).
- (f) O tempo médio de atendimento conta só as demandas que estão "Concluída"; o período do dashboard filtra pela data de abertura.
- (g) O dashboard não tem exportação para PDF nem gráfico por mês nesta fase.
- (h) "Cadastros de apoio" e "Parâmetros gerais" ficam juntos no menu "Configurações" do admin.

### Trabalhos futuros
- Log de auditoria geral (logins, alterações administrativas): não entra nesta fase, por escolha do autor (2026-10-27). A linha do tempo das demandas (RF08) continua registrando as ações sobre cada demanda.

**Critério de conclusão:** os itens escolhidos estão funcionando; os demais ficam registrados como trabalhos futuros.

---

## Fase 8 — Testes, validação com usuários e implantação 🟨

### Escolhas do autor (2026-10-27)
- As melhorias que o autor quer fazer ficam para uma **Fase 9**, depois do sistema implantado. **Revisto em 2026-10-27:** a Fase 9 entra antes da retomada da Fase 8, para os testes com usuários e o deploy já usarem o sistema melhorado.
- O ambiente de produção ainda não está definido e fica para o fim da fase, por escolha do autor: levantar as opções (servidor da UFES, hospedagem compartilhada, VPS/nuvem) com prós, contras e custos para o autor decidir.
- Testes com usuários: **roteiro de tarefas por perfil**; o autor coleta os comentários livremente.

### Situação (2026-10-27): pausada para a Fase 9
Prontos: revisão de segurança, responsividade, dados de demonstração e roteiro de testes com usuários. Ao retomar, faltam: a revisão do aviso de privacidade pelo autor (com os dados da UFES), os testes com usuários, as correções a partir do feedback e, por último, a hospedagem e o deploy. Antes dos testes, conferir o roteiro e os dados de demonstração contra as mudanças da Fase 9. A Fase 9 começa a partir da branch `feature/fase8-testes-implantacao`, e as duas são juntadas na `main` quando a Fase 8 terminar.

### Ordem de trabalho
1. Revisão de segurança e das permissões, com testes automáticos para o que faltar. ✅ (falta a revisão do aviso de privacidade pelo autor)
2. Testes de responsividade (celular, tablet, desktop). ✅
3. Dados de demonstração (seeder separado, fora do `DatabaseSeeder`). ✅
4. Roteiro de testes por perfil → testes com usuários (no ambiente local) → correções.
5. Por último: levantamento das opções de produção → decisão do autor → guia de deploy (inclui `queue:work`/cron e HTTPS) → deploy.


- [ ] Testes automatizados (Feature tests do Laravel) para os fluxos principais e as permissões.
- [x] Revisão de segurança: CSRF, hash de senhas, validação de uploads, autorização em todas as rotas, LGPD (dados mínimos necessários).
- [x] Testes de responsividade (celular, tablet, desktop) — RNF02.
- [x] Dados de demonstração (seeders) para a apresentação.
- [ ] Testes com usuários reais (coordenador, demandante de comunidade ou instituição, estudantes) e registro dos resultados para o TCC2.
- [ ] Correções a partir do feedback.
- [ ] Definir o ambiente de produção e fazer o deploy.

### Revisão de segurança (2026-10-27)
Revisados as rotas, os middlewares, as policies, todos os controllers, os uploads e downloads, as notificações e as telas.

**Já estava correto:**
- CSRF: todos os formulários que enviam dados têm `@csrf`; nenhuma tela usa `{!! !!}` (todo texto é escapado).
- Senhas: guardadas com hash (bcrypt, 12 rodadas), mínimo de 8 caracteres com letras e números; login com bloqueio depois de 5 tentativas erradas; convite com token guardado só como hash, de uso único e com prazo.
- Autorização: toda rota interna exige login, e-mail confirmado e conta ativa; a área do admin exige o perfil; demandas, sub-projetos, entregas, anexos e arquivos passam pelas policies, e os parâmetros aninhados nas rotas são conferidos (um anexo só abre pela demanda dele).
- Uploads: o tipo é conferido pelo conteúdo do arquivo, não só pela extensão; os arquivos ficam fora da pasta pública e só saem pelo download autorizado; tamanho e quantidade limitados pelos parâmetros.
- Dados gravados só a partir de campos validados; o perfil e o status não podem ser alterados pelo próprio usuário.
- LGPD: a página pública e a vitrine não mostram e-mail, telefone ou matrícula; o estudante da equipe não vê os contatos do demandante.

**Corrigido nesta revisão:**
- Cadastro de estudante, de demandante e aceite de convite: até 10 envios por minuto, para conter cadastros em massa.
- Cabeçalhos de segurança em todas as respostas (middleware `CabecalhosSeguranca`): bloqueia a abertura das telas dentro de outro site, impede o navegador de adivinhar o tipo dos arquivos e, em HTTPS, obriga o uso de HTTPS.
- Avisos por e-mail: o texto digitado pelos usuários (chat, títulos, motivos, nomes) deixou de virar link ou formatação no e-mail, o que permitia montar um link falso dentro de um aviso oficial.
- PDF do relatório: o título do Projeto Base só aparece se o projeto estiver entre os que o usuário vê.
- Testes novos em `SegurancaTest` (8): todas as rotas internas mandam o visitante para o login; todas as rotas do admin dão 403 para coordenador, demandante e estudante; dashboard fechado para demandante e estudante; cabeçalhos; limite do cadastro; arquivo PHP renomeado para `.pdf` recusado; texto do chat sem link no e-mail; PDF sem projeto de outro coordenador. 195 testes no total, todos passando.

**Pendente:**
- LGPD: rascunho do aviso de privacidade criado em `/privacidade` (2026-10-27), com link no rodapé e nos formulários de cadastro e de convite (aviso, sem caixa de marcar, porque a base legal proposta é a execução das atividades da universidade pública, e não o consentimento). Aguarda a revisão do autor. A confirmar com a UFES: encarregado de dados (DPO), base legal, prazo de guarda dos registros, hospedagem e serviço de e-mail. O e-mail de contato precisa ser preenchido nos parâmetros gerais.
- Checklist de produção, para o guia de deploy: `APP_ENV=production`, `APP_DEBUG=false`, `LOG_LEVEL=warning`, HTTPS com `SESSION_SECURE_COOKIE=true`, a raiz do site apontando para `sistema/public`, senha do admin inicial trocada, `php artisan config:cache` e `route:cache`, `queue:work` sempre rodando e backup do banco e da pasta `storage/app`.

### Testes de responsividade — RNF02 (2026-10-27)
- **Como foi feito:** um script no navegador abriu cada tela em 360 px (celular), 768 px (tablet) e 1280 px (computador) e mediu se a página ficava mais larga que a tela. Foram 26 telas logado como admin (painel, dashboard, usuários, convites, configurações, Projetos Base, demandas, sub-projetos, entregas, vitrine, relatório, página pública) e 9 telas de visitante (início, login, cadastros, recuperação de senha, página pública e aviso de privacidade). Depois, as telas principais foram conferidas visualmente no celular e no tablet.
- **Problema encontrado e corrigido:** no tablet, o menu do admin ficava mais largo que a tela em todas as páginas (cerca de 920 px para 768 px). O menu do admin agora vira o botão de menu abaixo de 1200 px, e o dos outros perfis abaixo de 992 px. Medido de novo em 768, 992, 1024, 1199, 1200 e 1280 px: sem rolagem lateral e com o menu numa linha só.
- **Sem outros problemas:** no celular, nenhuma tela tem rolagem lateral; as tabelas (usuários, demandas, relatório) rolam dentro do próprio quadro.
- **Telas dos outros perfis:** conferidas com os dados de demonstração, sem rolagem lateral (ver "Dados de demonstração").
- **Sugestão para a Fase 9:** no celular, a lista de usuários e o relatório mostram as últimas colunas só rolando a tabela (o status do usuário, por exemplo, fica escondido à direita). Um layout em cartões nessas telas ficaria melhor.

### Dados de demonstração (2026-10-27)
- **Seeder:** `DemonstracaoSeeder`, fora do `DatabaseSeeder`. Uso: `php artisan db:seed --class=DemonstracaoSeeder`; passo a passo em `docs/INSTALACAO.md` (seção 6). Não roda em produção, não carrega duas vezes no mesmo banco e não envia e-mails.
- **Cenário (fictício):** 4 comunidades e instituições de Alegre, Guaçuí e Jerônimo Monteiro; 2 coordenadores; 5 demandantes; 7 estudantes; 4 projetos de extensão ("Robótica Educacional nas Comunidades/instituições", "Conecta Comunidade/instituição: redes e infraestrutura", "Presença Digital das Comunidades/instituições", "Formação Tecnológica para Educadores") além do "Demandas Gerais".
- **11 demandas, uma em cada etapa:** 2 cadastradas (uma com ofício em PDF), em análise com dúvidas no chat, priorizada com o sub-projeto na vitrine e 2 candidaturas para avaliar, em desenvolvimento com entrega aguardando a validação técnica, em validação aguardando o aceite (depois de uma entrega devolvida), aguardando ajustes (pendência reportada pela comunidade/instituição), 2 concluídas (uma com aceite registrado pela coordenação), recusada e cancelada pela comunidade/instituição, com os motivos.
- **Para a fila do admin:** um estudante e um demandante (com comunidade/instituição digitada no cadastro) aguardando aprovação.
- **Coerência:** cada demanda percorre o fluxo real, com as datas e o autor de cada passo, então a linha do tempo, o dashboard (tempos médios) e o relatório dos extensionistas mostram números reais. E-mails em `@demo.pgdt.example`, que não recebem mensagens. A senha das contas vem de `DEMO_PASSWORD` no `.env` (ou é gerada e mostrada no terminal).
- **Testes:** `DemonstracaoSeederTest` (5): todas as etapas e situações de entrega presentes, linha do tempo em ordem e com autor, telas de cada perfil abrindo, sem duplicar e sem rodar em produção. 201 testes no total, todos passando.
- **Banco local:** carregado no banco separado `pgdt_demo` (o `pgdt` com os dados de teste das fases não foi alterado); servidor da demonstração na porta 8002 (`php artisan serve --port=8002` com `DB_DATABASE=pgdt_demo`). Os arquivos de demonstração ficam na mesma pasta `storage/app/private` do banco `pgdt`, com nomes aleatórios.
- **Responsividade dos outros perfis:** com as contas de demonstração, 22 telas de estudante, demandante e coordenador medidas em 360, 768 e 1280 px, sem rolagem lateral; as permissões também conferiram (estudante e demandante recebem "Acesso negado" nas demandas de que não participam).

### Roteiro de testes com usuários (2026-10-27)
- **Documento:** Roteiro de testes com usuários — PGDT (exportado para Word ou PDF). Tem a preparação do ambiente, como conduzir a sessão, as tarefas de cada perfil (demandante D1–D6, estudante E1–E5, coordenador C1–C7, administrador A1–A5, opcional), as perguntas finais e a ficha de registro com a consolidação dos problemas.
- **Participantes previstos:** 1 demandante, 1 coordenador, 2 estudantes e, opcionalmente, o admin; cerca de 30 minutos cada. A demonstração recomeça do zero antes de cada participante, e pelo menos um faz as tarefas no celular (servidor com `--host=0.0.0.0`).
- **Ajuste na demonstração:** a demanda "Planilha para controlar as doações" passou do "Demandas Gerais" para o projeto da coordenadora Mariana, para ela ter um pedido novo para a triagem (tarefa C1).
- **Teste novo:** `test_tarefas_do_roteiro_de_testes_com_usuarios_funcionam` executa as tarefas do roteiro com os dados de demonstração e confirma que todas podem ser concluídas. 202 testes no total, todos passando.
- **Próximo passo:** o autor aplica os testes e preenche as fichas; os problemas encontrados viram as correções desta fase.

### Ensaio do roteiro com o código da Fase 9 (2026-10-27)
A Fase 9 é contínua (melhorias sempre entram), então a Fase 8 segue em paralelo, testando o código atual. Todas as tarefas do roteiro foram feitas no navegador, como cada perfil, num banco separado `pgdt_teste` (demonstração + complemento da Fase 9; servidor na porta 8003), para não gastar o cenário do `pgdt_demo`. Nenhum erro no servidor nem no navegador.

**Resultado:** 23 tarefas concluídas, 1 impossível (E1 do Bruno). D4 foi feita até a decisão, sem enviar, para a C6 continuar possível no mesmo banco.

**Problemas e ajustes encontrados:**
| # | Problema | Tela | Gravidade | Proposta |
|---|---|---|---|---|
| 1 | O aviso "uma entrega aguarda o seu aceite" não diz qual demanda nem tem link, e o painel lista só as 5 demandas mais recentes: depois de abrir uma demanda nova, a da biblioteca some do painel | Painel do demandante | Média | Fase 9: aviso com o nome e o link da demanda (ou da entrega) |
| 2 | O Bruno não está vinculado à Robótica, então pela regra (s) da Fase 9 não vê a oficina: a E1 dele não pode ser feita | Roteiro / demonstração | Alta para o teste | Vincular o Bruno à Robótica no complemento ou trocar a tarefa dele para o "Cardápio da merenda" |
| 3 | A pergunta do chat do Wi-Fi já tem a resposta do João, e a D2 pede para responder | Roteiro / demonstração | Média | Tirar a resposta da demonstração ou reescrever a D2 |
| 4 | A A5 espera 6 estudantes no PDF; agora são 11 (estudantes da Fase 9) | Roteiro | Baixa | Atualizar o número no roteiro |
| 5 | A preparação manda `migrate:fresh` antes de cada participante, o que apaga o banco | Roteiro | Média | Recriar só um banco próprio de teste (como o `pgdt_teste`) e rodar também o `DemonstracaoFase9Seeder` |
| 6 | Aprovar tecnicamente e registrar o aceite não mudam o status da demanda: a coordenação precisa mudar à mão (as mensagens avisam) | Entrega / demanda | Baixa | Observar nos testes reais se a coordenadora percebe o segundo passo |
| 7 | "Reportar Pendência" sem comentário deixa o campo vermelho, mas sem mensagem de erro | Entrega | Baixa | Mostrar a mensagem junto do campo |
| 8 | Datas incoerentes na demonstração: o site terminou em 12/10 e segue em desenvolvimento; a candidatura da manutenção foi enviada depois do fim do sub-projeto | Demonstração | Baixa | Ajustar as datas no seeder |
| 9 | A candidatura é recusada sem motivo, e o estudante não sabe por quê | Sub-projeto | Baixa | Avaliar com o autor um motivo opcional |

Os itens 1, 6, 7 e 9 são do sistema e ficam para a sessão da Fase 9 decidir; os itens 2 a 5 e 8 são do roteiro e dos dados de demonstração.

**Roteiro corrigido (2026-10-27), itens 2 a 5:** a E1 do Bruno passou a ser a inscrição no "Cardápio da merenda" (os vínculos da demonstração não mudaram); a D2 pede uma informação nova no chat em vez de responder à pergunta; a A5 espera 11 estudantes; a preparação usa o banco próprio `pgdt_teste` (criado uma vez, recriado antes de cada participante, com o `DemonstracaoSeeder` e o `DemonstracaoFase9Seeder`, porta 8003), sem tocar no `pgdt` e no `pgdt_demo`. Falta o item 8 (datas da demonstração), que mexe no seeder. Não foi conferido: o uso no celular com as telas da Fase 9 e a percepção de usuários reais.

### Teste completo de todas as ações por perfil (2026-10-27)
No banco `pgdt_teste`, pelo navegador. Feito: varredura automática de todas as telas de 8 contas (admin, 2 coordenadores, 2 demandantes, 3 estudantes; cerca de 400 páginas), matriz de acesso (quem abre cada demanda, sub-projeto, entrega, edição, anexo e arquivo), todos os formulários de cada perfil com dados válidos, inválidos e forjados (enviados direto ao servidor), o ciclo completo de uma demanda sem comunidade/instituição e sem demandante, os fluxos do visitante (cadastro, confirmação de e-mail, convite, recuperação de senha) e a fila de avisos (45 e-mails, nenhuma falha).

**O que está certo:** nenhuma tela quebra; a matriz de acesso bate com as regras em todos os perfis; todas as validações testadas recusam o dado errado (arquivos falsos, SVG, limites, SIAPE e matrícula repetidos, status fora de ordem, projeto de outro coordenador, comunidade/instituição e demandante trocados, parâmetros inválidos, convite reusado).

**Erros encontrados:**
| # | Erro | Como acontece | Gravidade |
|---|---|---|---|
| E1 | Erro 500 ao colocar de volta na equipe um estudante removido | Remover um membro e depois incluí-lo de novo pela edição do sub-projeto: a tabela `membros_equipe` só aceita um registro por estudante e sub-projeto, e a remoção não apaga o registro | Alta |
| E2 | Erro 500 ao aprovar a candidatura de um estudante já removido | Estudante incluído direto pela coordenação, removido, se candidata pela vitrine (o sistema deixa) e a coordenação aprova: mesma causa do E1 | Alta |
| E3 | O admin cancela uma demanda já concluída | "Cancelada" aparece para o admin em qualquer status; numa concluída, as horas dos estudantes somem do relatório (ex.: Eduarda perdeu as do curso de Scratch) e não há como reabrir | Alta |
| E4 | Trocar o perfil ou inativar um coordenador não trata os projetos dele | Os Projetos Base e sub-projetos continuam com ele como coordenador: ninguém além do admin gerencia, e os avisos continuam indo para ele (o admin não recebe, porque o projeto "tem" coordenador). O sistema não avisa nem bloqueia | Média |
| E5 | Mensagem leva a uma ação impossível | Ao devolver uma entrega com a demanda "Em Desenvolvimento", o sistema diz para mudar para "Aguardando Ajustes", status que só existe a partir de "Em Validação" | Baixa |
| E6 | Trocar o coordenador do Projeto Base não passa os sub-projetos | O coordenador antigo continua responsável pelos sub-projetos já criados e segue gerenciando essas demandas | Baixa (confirmar a regra) |
| E7 | Convite cancelado ou substituído aparece como "Expirado" | Cancelar só encerra o prazo; a lista não diferencia | Baixa |
| E8 | Ação repetida ou com a tela desatualizada mostra "Acesso negado" | Ex.: validar de novo uma entrega já avaliada (duplo clique ou duas abas): a página 403 não explica o que houve | Baixa |
| E9 | Estudante removido vê a candidatura como "Aprovada" | Na vitrine, com o aviso "Você já participou desta equipe" | Baixa |
| E10 | O admin não consegue corrigir os dados de um usuário (achado pelo autor; o teste completo não cobriu) | Na tela do usuário, curso e matrícula ficam dentro de "Trocar perfil", que exige um perfil diferente: trocar só o curso de um estudante dá "Escolha um perfil diferente do atual". O mesmo vale para matrícula, comunidade/instituição e cargo do demandante e SIAPE do coordenador. O estudante muda o próprio curso no "Meu perfil", mas a matrícula ninguém corrige | Alta |
| E11 | "Conferir comunidade/instituição" edita a comunidade/instituição de todos | O bloco aparece para todo demandante e altera o cadastro da comunidade/instituição, que vale para todos os usuários e demandas dela; não há como mudar o demandante de comunidade/instituição | Média |

**Conferido depois (2026-10-29):** rejeitar, inativar e reativar contas (motivo obrigatório; a pessoa vê o motivo no login) e inativar cadastro de apoio em uso (a demanda mantém o item) funcionam.

Somam-se aos pontos do ensaio anterior: aviso de aceite sem link no painel do demandante (1), comentário obrigatório sem mensagem (7) e recusa de candidatura sem motivo (9). Correções a combinar com o autor (decidir onde corrigir, já que a sessão da Fase 9 usa a mesma pasta).

**Correções (2026-10-29, sessão da Fase 9):** E1 a E11 e os pontos 1, 7 e 9 do ensaio foram corrigidos na branch `feature/fase9-melhorias`. Detalhes e decisões na seção da Fase 9, "Correções do teste completo da Fase 8". Falta repetir no navegador os passos que geraram cada erro.

### Reteste completo depois das correções (2026-10-29)
Banco novo `pgdt_teste2` (demonstração + complemento da Fase 9, todas as migrations; configuração `pgdt-teste2`, porta 8004), sem apagar nenhum banco. Método: inventário automático de todos os formulários das telas de cada perfil (45 ações distintas em cerca de 270 telas de admin, coordenador, demandante, estudante e visitante), mais as ações que só aparecem em certas situações (criar sub-projeto, cancelar candidatura e convite, remover foto, aceitar convite, confirmar e-mail, recuperar senha); cada ação testada com dados válidos, inválidos e forjados, a partir da própria tela, para ver a mensagem do usuário. Também: suíte automatizada (255 testes passando), matriz de permissões, ciclo completo de uma demanda com os quatro perfis, efeito dos parâmetros gerais, páginas públicas, troca de perfil de usuários com vínculos, fila de avisos (41 e-mails, nenhuma falha) e log (nenhum erro novo).

**Correções conferidas no navegador:** E1 a E11 e os pontos 1, 7 e 9 do ensaio, todos corrigidos.

**Erros novos:**
| # | Erro | Como acontece | Gravidade |
|---|---|---|---|
| N1 | Comunidade/instituição duplicada pelo cadastro de demandante | Em "Não encontrei minha comunidade/instituição", dá para cadastrar uma comunidade/instituição com o mesmo nome e cidade de uma existente (EMEF Córrego Alegre, Guaçuí); ao aprovar o cadastro, a comunidade/instituição aparece duas vezes nas listas | Média |
| N2 | Projeto Base com título repetido | O sistema aceita criar outro "Robótica Educacional nas Comunidades/instituições"; os dois aparecem iguais na escolha do projeto | Baixa |
| N3 | Trocar o perfil de um demandante com demandas não trata as demandas | Regina (4 demandas) virou estudante: as demandas continuam no nome dela, ela não consegue mais listá-las ("Minhas demandas" dá acesso negado) e segue recebendo os avisos; não há aviso antes da troca, como existe para o coordenador (E4) | Média |
| N4 | Estudante de uma equipe que vira coordenador continua na equipe | Felipe virou coordenador: segue como membro (ocupa a vaga) e recebe os avisos da equipe, mas não abre mais o sub-projeto (acesso negado); não há aviso antes da troca | Média |
| N5 | O painel do estudante conta as vagas sem a regra de vínculo | "Oportunidades: 3" para todos, mas a vitrine mostra só as dos projetos vinculados (0 para quem não tem vínculo); o contador em `HomeController` não usa `abertosPara` | Média |

**Textos menores:** "A definir pelo administrador", "A definir pela administração" e "a definir" para a mesma situação; mensagem "O campo siape já está sendo utilizado" com "siape" minúsculo; cancelar um convite já cancelado e remover uma foto que não existe respondem como se tivessem feito algo.

**Decisão do autor (2026-11-01):** os erros N1 a N5 e os textos menores não serão corrigidos por enquanto; ficam registrados aqui como pendências conhecidas.

**Fora do alcance deste teste:** celulares reais, envio real de e-mails, uso simultâneo por várias pessoas e a configuração de produção. As telas foram conferidas pelo conteúdo, com poucas capturas de tela.

**Critério de conclusão:** sistema implantado e validado com usuários; resultados documentados.

---

## Fase 9 — Melhorias do sistema 🟨

Melhorias pedidas pelo autor, feitas antes da retomada da Fase 8 (decisão de 2026-10-27). Branch `feature/fase9-melhorias`.

- [x] Imagem do Projeto de extensão no cadastro e na edição; sem imagem, fundo padrão do sistema.
- [x] "Meu perfil": cada usuário edita os próprios dados, a foto e a senha; sem foto, avatar padrão de acordo com o perfil (admin, coordenador, demandante, estudante).
- [x] Coordenador abre demandas nos projetos que coordena, e o admin em qualquer projeto.
- [x] Comunidade ou instituição opcional na demanda aberta pela coordenação.
- [x] Pesquisa em todos os combos (listas de seleção).
- [x] Inclusão manual de estudantes extensionistas na equipe, ao criar ou editar o sub-projeto.
- [x] Botão "Voltar" padronizado no topo das telas.
- [x] Linha do tempo da demanda numa janela (pop-up).
- [x] Data de término do sub-projeto editável também depois de a demanda ser encerrada.
- [x] Sugestão anotada na Fase 8: no celular, mostrar a lista de usuários e o relatório em cartões.
- [x] Papel "Outros" na equipe do sub-projeto.
- [x] Sem o texto "Estimada, por estudante." abaixo da carga horária do sub-projeto.
- [x] Filtro na lista de sub-projetos.
- [x] Estudantes extensionistas vinculados ao Projeto Base; o sub-projeto só aloca os vinculados.
- [x] Correções do teste completo da Fase 8 (E1 a E11 e pontos 1, 7 e 9 do ensaio).

### Imagem do Projeto de extensão (2026-10-27)
- **Cadastro e edição:** campo "Imagem do projeto" (opcional), com prévia antes de salvar. Na edição, dá para trocar ou marcar "Remover a imagem e voltar ao fundo padrão".
- **Onde aparece:** faixa no topo da tela do projeto, miniatura na lista de Projetos Base, cartões e tela do projeto na página pública `/projetos`.
- **Sem imagem:** fundo padrão azul com uma rede de pontos ligados (`public/images/projeto-padrao.svg`).

### Meu perfil (2026-10-27)
- **Acesso:** menu do usuário (canto direito, agora com a foto ao lado do nome) → "Meu perfil" (`/perfil`). Só para contas ativas.
- **Aba "Meus dados":** nome e telefone para todos; curso (estudante), cargo (demandante), SIAPE e departamento (coordenador). Mostra sem permitir edição: e-mail, matrícula e comunidade/instituição.
- **Aba "Senha":** troca com a senha atual.
- **Foto:** enviar, trocar ou remover. Sem foto, aparece o avatar padrão do perfil: escudo cinza (admin), quadro roxo (coordenador), comunidade/instituição verde (demandante), capelo laranja (estudante), em `public/images/avatares/`. Se o admin trocar o perfil, o avatar padrão acompanha.
- **Onde a foto aparece:** menu, Meu perfil, lista e detalhe de usuários do admin, chat da demanda e equipe do sub-projeto.

### Decisões de implementação da Fase 9 (aprovadas pelo autor em 2026-10-27)
- (a) Imagem e foto aceitam JPG, PNG e WebP até 5 MB, entre 100 e 6000 pixels. O sistema reduz e regrava tudo em JPEG: a imagem do projeto fica com até 1600 px, e a foto é recortada num quadrado de 400 × 400 px, pelo centro. A regravação deixa o arquivo leve e apaga o que vinha junto do original (como a localização GPS das fotos de celular). Foto tirada no celular deitado é girada para a posição certa.
- (b) Os arquivos ficam fora da pasta pública (como os anexos) e saem por rota própria, sem precisar do `storage:link` no servidor. A imagem antiga é apagada quando é trocada ou removida.
- (c) A imagem de projeto que está na página pública abre sem login. A de projeto oculto ou inativo segue as mesmas regras da tela do projeto.
- (d) A foto de perfil só aparece para quem está logado; a página pública não mostra fotos (LGPD).
- (e) O usuário não troca pelo "Meu perfil": e-mail (é o login; a troca fica com a coordenação), perfil, status, matrícula do estudante e comunidade/instituição do demandante (dependem da conferência do admin). O telefone continua obrigatório para o demandante.
- (f) Troca de senha exige a senha atual, com até 6 tentativas por minuto.

### Demanda aberta pela coordenação (2026-10-27)
- **Quem:** o coordenador, só nos projetos ativos que coordena; o admin, em qualquer projeto ativo. O demandante continua abrindo só para a própria comunidade/instituição.
- **Onde:** botão "Nova demanda" na lista de demandas e "Nova demanda neste projeto" na tela do Projeto Base.
- **Comunidade/instituição e demandante (escolha do autor: "os dois"):** a coordenação escolhe a comunidade/instituição e, se ela tiver alguém cadastrado, o demandante que acompanha. A lista de demandantes mostra só os da comunidade/instituição escolhida. Sem demandante, a demanda fica só em nome da comunidade/instituição.
- **Com demandante:** ele recebe um aviso por e-mail ("Demanda cadastrada em seu nome"), vê a demanda em "Minhas demandas", conversa no chat e dá o aceite normalmente.
- **Sem demandante:** o contato com a comunidade/instituição e o aceite ficam com a coordenação. A tela da entrega explica isso, e a linha do tempo mostra "registrado pela coordenação em nome da comunidade/instituição".
- **Registro:** a demanda entra como "Cadastrada" e segue a triagem normal. A linha do tempo mostra "Demanda aberta pela coordenação em nome de …" (ou "para a comunidade/instituição …, sem demandante no sistema"), e a tela da demanda mostra "Cadastrada por".
- **Banco:** `solicitacoes.demandante_id` passa a aceitar nulo e entra `solicitacoes.cadastrada_por`; as demandas antigas ficaram com o próprio demandante.

### Decisões de implementação da demanda pela coordenação (aprovadas pelo autor em 2026-10-27)
- (g) Só demandante **ativo** e da comunidade/instituição escolhida pode ser indicado; só comunidade/instituição ativa pode ser escolhida (a comunidade/instituição nova digitada no cadastro precisa ser conferida pelo admin antes).
- (h) O coordenador que abre a demanda não recebe aviso da própria ação; a coordenação do projeto (ou o admin, no "Demandas Gerais" sem coordenador) recebe o aviso de demanda nova, como hoje.
- (i) Depois de aberta, a demanda não muda de demandante nem de comunidade/instituição (como a demanda aberta pelo demandante, que não é editada).

### Segundo lote de melhorias (2026-10-27)
- **Comunidade/instituição opcional:** na "Nova demanda" da coordenação, a comunidade/instituição passa a ser opcional. Sem comunidade/instituição, a lista mostra todos os demandantes (com a comunidade/instituição de cada um ao lado), e escolher um demandante preenche a comunidade/instituição dele. Sem comunidade/instituição e sem demandante, a linha do tempo registra "Demanda aberta pela coordenação (sem comunidade/instituição e sem demandante no sistema)", e as telas mostram "Não informada" ou "—". Banco: `solicitacoes.escola_instituicao_id` passa a aceitar nulo (a demanda aberta pelo demandante continua com a comunidade/instituição dele). O dashboard já agrupa essas demandas em "Sem comunidade/instituição".
- **Combos com pesquisa:** todo combo do sistema ganhou uma caixa "Pesquisar...", que acha o item sem diferenciar maiúsculas e acentos ("corrego" acha "Córrego") e funciona pelo teclado (setas, Enter, Esc). Feito em JavaScript próprio (`resources/js/combo-pesquisa.js`), sem biblioteca nova. O combo original continua no formulário, escondido, e é ele que vai no envio.
- **Estudantes na equipe pela coordenação:** criar e editar sub-projeto têm o bloco "Incluir estudantes na equipe", com o combo de estudantes (vários de uma vez, com pesquisa por nome, matrícula ou curso) e o papel. Entram na equipe na hora, com o registro na linha do tempo e um aviso por e-mail ("Você entrou na equipe").
- **Botão "Voltar":** os links do topo que mostravam o nome da página anterior ("← Projetos Base", "← título da demanda") viraram um botão "← Voltar" igual em todas as telas (`layouts/_voltar.blade.php`). O destino continua na dica do botão e para leitores de tela. A tela de criar e editar Projeto Base também ganhou o botão.
- **Linha do tempo em janela:** a tela da demanda mostra só o último registro e o botão "Ver a linha do tempo completa (N registros)", que abre a lista numa janela com rolagem (tela cheia no celular).
- **Término do sub-projeto:** com a demanda concluída, cancelada ou recusada, a correção passa a aceitar a data de término, além da carga horária (botão "Corrigir carga horária ou término"). A mudança vai para a linha do tempo. Em andamento, o término já podia ser alterado no "Editar".

### Decisões de implementação do segundo lote (aprovadas pelo autor em 2026-10-27)
- (j) Só estudantes com cadastro **ativo** podem ser incluídos; quem já está na equipe não aparece na lista. Estudante removido antes pode ser incluído de novo.
- (k) A inclusão respeita as vagas: se passar do número de vagas, o sistema pede para aumentar as vagas (pode ser na mesma edição). Nada é gravado se der erro.
- (l) Quem tinha candidatura pendente e é incluído tem a candidatura aprovada (recebe o aviso de aprovação). Quem entra sem candidatura não pode se candidatar ao mesmo sub-projeto.
- (m) Todos os incluídos de uma vez recebem o mesmo papel; dá para trocar depois, na tela do sub-projeto.
- (n) O botão "Voltar" leva para a tela "de cima" (a lista ou a tela de origem fixa), e não para o histórico do navegador, para funcionar igual mesmo quando a pessoa chega por um link do e-mail.
- (o) A data de término corrigida depois do encerramento não pode ser anterior ao início do sub-projeto.

**Testes do segundo lote:** `EquipeManualTest` (3), `DemandaPelaCoordenacaoTest` (+1, comunidade/instituição opcional), `SubprojetoTest` (correção do término depois de concluída) e `RastreabilidadeTest` (janela da linha do tempo). 233 testes no total, todos passando. Combo com pesquisa e botão "Voltar" conferidos no navegador nas telas públicas (cadastro e página do projeto); as telas com login foram cobertas pelos testes, sem conferência visual.

### Cartões no celular (2026-10-27)
- **Lista de usuários (admin):** em telas menores que um tablet, cada usuário aparece num cartão com foto, nome, e-mail, perfil, status (e o aviso "e-mail não confirmado"), data do cadastro e o botão "Analisar" ou "Detalhes". O status, que antes ficava escondido à direita da tabela, fica à vista. Do tablet para cima, a tabela continua igual.
- **Relatório por extensionista:** no celular, cada participação vira um cartão com o sub-projeto, o status, a carga horária em destaque e as linhas Projeto, Comunidade/instituição, Papel e Período. O total de cada estudante continua no topo. O PDF não muda.

**Testes:** `AdminUsuariosTest` e `RelatorioTest` conferem os cartões. 233 testes no total, todos passando. A conferência visual no celular não foi feita: as duas telas exigem login.

### Terceiro lote de melhorias (2026-10-27)
- **Papel "Outros":** entra na lista de papéis da equipe (migration `2026_10_27_000006`; também no `DominioSeeder`). O admin pode renomear ou inativar, como os outros papéis.
- **Carga horária:** o formulário do sub-projeto não mostra mais o texto "Estimada, por estudante.".
- **Filtro em Sub-projetos:** busca por título ou escopo, projeto de extensão, status da demanda e modalidade, com "Limpar". O combo de projetos mostra só os projetos que têm sub-projeto visível para quem está filtrando. Vale também para o estudante, em "Meus sub-projetos".
- **Estudantes vinculados ao Projeto Base:** o cadastro e a edição do Projeto Base têm o campo "Estudantes extensionistas vinculados" (vários, com pesquisa). A tela do projeto mostra a lista só para o admin e o coordenador do projeto. O estudante vinculado vê o aviso "Você é estudante extensionista deste projeto" na tela do projeto e a seção "Meus projetos de extensão" em "Meus sub-projetos". A página pública não mostra os vínculos.
- **Sub-projeto:** no "Incluir estudantes na equipe", só aparecem (e só são aceitos) os estudantes ativos vinculados ao Projeto Base da demanda. Sem ninguém vinculado, o formulário avisa e leva ao cadastro do projeto.
- **Banco:** tabela `estudantes_projeto_base` (migration `2026_10_27_000007`). Quem já tinha participado de uma equipe foi vinculado ao projeto dela, nos bancos `pgdt` e `pgdt_demo`. Os dados de demonstração também vinculam os membros das equipes.

### Decisões de implementação do terceiro lote (aprovadas pelo autor em 2026-10-27)
- (p) Quem pode vincular e desvincular: o admin (em qualquer projeto) e o coordenador do projeto, no cadastro e na edição do Projeto Base.
- (q) Desvincular um estudante não o tira das equipes de que ele já faz parte; ele só deixa de aparecer para novas inclusões.
- (r) Só estudantes com cadastro ativo podem ser vinculados; quem já estava vinculado e teve a conta inativada continua na lista (marcado "conta inativa") até ser desmarcado.
- (s) **Escolha do autor (2026-10-27): restringir também.** Na vitrine de oportunidades, o estudante só vê as vagas dos projetos a que está vinculado. Pelo link da página pública a vaga abre, mas a inscrição explica que é preciso ser vinculado pela coordenação. A aprovação de candidatura também exige o vínculo: a candidatura de quem foi desvinculado aparece marcada "Não vinculado(a) ao projeto", e o sistema pede para vincular ou recusar. A aprovação não vincula sozinha. A página pública continua mostrando todas as vagas.

**Testes do terceiro lote:** `ProjetoVinculoTest` (7: vínculo, quem vê, desvínculo sem tirar da equipe, vitrine e candidatura só para vinculados, aprovação bloqueada sem vínculo e filtro da lista) e `EquipeManualTest` (4, agora com o vínculo e o papel "Outros"). Os testes antigos de candidatura (`EquipeTest`, `CicloCompletoTest`, `ConsultaPublicaTest`, `NotificacaoTest`) passaram a vincular o estudante. 241 testes no total, todos passando. Telas com login sem conferência visual.

**Dados de demonstração:** o `DemonstracaoSeeder` agora vincula os estudantes aos projetos (por exemplo, Ana na Robótica, Conecta e Presença Digital) e vincula quem se candidata. Conferir o roteiro de testes com usuários (Fase 8) contra essa regra.

### Correções do teste completo da Fase 8 (2026-10-29)
Erros da seção "Teste completo de todas as ações por perfil", na ordem pedida pelo autor.

**Escolhas do autor:**
- **E4:** coordenador inativado ou que muda de perfil passa os Projetos Base para a administração (todos os admins), que escolhe outro coordenador depois. Os sub-projetos de demandas concluídas continuam no nome dele.
- **E6:** trocar o coordenador do Projeto Base **não** passa os sub-projetos existentes: eles ficam com o coordenador antigo (comportamento atual mantido).

**O que mudou:**
- **E1 e E2 (erro 500):** quem foi removido volta para a equipe no mesmo registro (sem a data de remoção e com o papel novo), tanto pela edição do sub-projeto quanto pela aprovação de candidatura. A linha do tempo registra "voltou para a equipe", e o estudante recebe o aviso (ou o da aprovação, se voltou por candidatura).
- **E9:** a candidatura aprovada de quem saiu da equipe aparece como "Aprovada · removido(a) da equipe" na vitrine, em "Meus sub-projetos" e na tela do sub-projeto.
- **E3:** "Cancelada" não aparece mais para demanda concluída ou recusada, nem para o admin; o envio forjado é recusado.
- **E10:** a tela do usuário no admin ganhou "Editar dados": nome, telefone e os dados do perfil atual (matrícula e curso; comunidade/instituição e cargo; SIAPE e departamento), sem trocar o perfil.
- **E10, ajuste (2026-10-29):** o autor ainda não conseguia trocar o curso. O servidor aceitava, mas a tela confundia: "Editar dados" ficava fechado embaixo, e "Trocar perfil" mostrava matrícula e curso do perfil atual, que davam "Escolha um perfil diferente do atual". Agora "Editar dados" fica sempre aberto, com a explicação do que corrige; "Trocar perfil" só mostra os campos de um perfil diferente e aponta para "Editar dados" (também nas mensagens de erro). Conferido no navegador com a página renderizada.
- **E11:** "Conferir comunidade/instituição nova" só aparece para comunidade/instituição digitada no cadastro e ainda não conferida; a comunidade/instituição já conferida não é editada pela tela do usuário. O demandante muda de comunidade/instituição pelo "Editar dados", sem alterar a comunidade/instituição de mais ninguém.
- **E4:** ao inativar (ou rejeitar) um coordenador ativo ou trocar o perfil dele, os Projetos Base ficam "A definir pelo administrador" e os sub-projetos em andamento ficam "A definir pela administração", com registro na linha do tempo. A tela do usuário avisa antes quantos projetos e sub-projetos serão passados, e a mensagem de sucesso confirma. Os avisos da demanda deixam de ir para coordenador inativo ou com outro perfil e passam para todos os admins ativos. Banco: `subprojetos.coordenador_id` passa a aceitar nulo (migration `2026_10_29_000001`).
- **E5:** depois de devolver a entrega ou registrar pendência, a mensagem só sugere "Aguardando Ajustes" com a demanda "Em Validação"; em desenvolvimento, diz que a equipe faz os ajustes e envia uma nova entrega.
- **E7:** convite encerrado antes do prazo aparece como "Cancelado" ou "Substituído" (migration `2026_10_29_000002`, coluna `convites.encerrado_como`). Os convites encerrados antes desta correção continuam como "Expirado".
- **E8:** quem decide a entrega e a encontra já em outra situação (duplo clique, duas abas) volta para a entrega com um aviso explicando; nada é registrado de novo. Nos demais casos, a página "Acesso negado" agora explica o que pode ter acontecido e tem os botões "Voltar" e "Ir para o início".
- **Ponto 1 do ensaio:** o aviso de aceite no painel do demandante lista cada entrega com o número, o nome da demanda e o link.
- **Ponto 7 do ensaio:** "Devolver para ajustes" ou "Reportar Pendência" sem comentário mostra a mensagem junto do campo, que some quando a pessoa começa a escrever.
- **Ponto 9 do ensaio:** a recusa de candidatura tem um campo "Motivo (opcional)" (migration `2026_10_29_000003`, coluna `candidaturas.motivo_recusa`). O estudante vê o motivo no aviso, na vitrine e em "Meus sub-projetos".
- As três migrations foram aplicadas nos bancos `pgdt`, `pgdt_demo` e `pgdt_teste` (só acrescentam colunas ou permitem nulo; nada apagado).

### Decisões de implementação das correções (aprovadas pelo autor em 2026-10-29)
- (t) Estudante removido não se candidata de novo pela vitrine, mesmo que tenha entrado sem candidatura (mantém a decisão (e) da Fase 5). Para voltar, a coordenação o inclui pela edição do sub-projeto.
- (u) No E4, os sub-projetos de demandas canceladas ou recusadas também continuam no nome do coordenador, como os concluídos (todos encerrados).
- (v) No E3, a demanda recusada também não pode ser cancelada (já está encerrada).
- (w) No E10, o e-mail não é editado pelo admin, porque é o login (a troca exigiria uma nova confirmação).
- (x) No E8, o aviso amigável vale para as decisões da entrega (validação técnica e aceite); as outras ações negadas mostram a página "Acesso negado" explicada.
- (y) O motivo da recusa é opcional, com até 500 caracteres.

**Testes:** `CorrecoesTesteCompletoTest` (12, um ou mais por erro), `AdminUsuariosTest` (+1 de "Editar dados" e comunidade/instituição) e `ValidacaoTest` (decisão repetida volta com aviso em vez de 403). 255 testes no total, todos passando. Não conferido no navegador.

### Complemento dos dados de demonstração (2026-10-27)
**Escolha do autor:** acrescentar dados sem apagar nada; apagar ou recriar banco só quando ele pedir.
- **`DemonstracaoFase9Seeder`** (`php artisan db:seed --class=DemonstracaoFase9Seeder`, depois do `DemonstracaoSeeder`): só insere. Não apaga nem altera o que já existe, e rodar de novo não duplica (só confere os vínculos).
- **O que acrescenta:**
  - 5 estudantes (Gabriela, Hugo, Isabela, Lucas, Marina), com a mesma senha das outras contas;
  - uma comunidade/instituição sem ninguém cadastrado (EMEF Boa Esperança);
  - os vínculos de estudantes com os quatro projetos;
  - 5 demandas: uma aberta pela coordenação em nome de demandante, uma pelo admin sem demandante, uma sem comunidade/instituição e sem demandante, uma em desenvolvimento com término prorrogado e uma aberta pelo demandante;
  - 3 sub-projetos, com inclusão manual na equipe, o papel "Outros" e vagas abertas na vitrine.
- **Rodado no `pgdt_demo`:** usuários 15 → 20, demandas 11 → 16, sub-projetos 6 → 9, registros da linha do tempo 83 → 109, membros de equipe 11 → 16, vínculos 9 → 23. Nenhum registro apagado; o banco `pgdt` não foi tocado.
- O `DemonstracaoSeeder` passou a ter os auxiliares `protected` (reaproveitados pelo complemento) e grava `cadastrada_por` nas demandas.
- **Teste:** `DemonstracaoSeederTest` confere que o complemento não apaga nem muda o que existe (inclusive uma demanda editada antes), acrescenta os dados, não duplica ao rodar de novo e mostra as novidades nas telas. 242 testes no total, todos passando.

**Testes:** `ImagemProjetoTest` (7), `PerfilTest` (14) e `DemandaPelaCoordenacaoTest` (6); o teste antigo "só o demandante abre demandas" virou "estudante não abre demandas". 229 testes no total, todos passando. A tela "Nova demanda" da coordenação não foi conferida no navegador (falta acesso às contas de demonstração).

**Testes (antes desta parte):** `ImagemProjetoTest` (7) e `PerfilTest` (14). 223 testes no total, todos passando. Telas públicas conferidas no navegador com os dados de demonstração (banco `pgdt_demo` migrado). As telas com login foram cobertas pelos testes, sem conferência visual.

**Para o deploy (Fase 8):** a extensão GD do PHP (com JPEG, PNG e WebP) é necessária no servidor; incluir no checklist de produção e no backup a pasta `storage/app/private` (que agora guarda também as imagens e fotos).

---

## Modelo de dados por fase

Detalhes de cada tabela, colunas e regras: [`docs/DER.md`](docs/DER.md).

| Fase | Tabelas |
|---|---|
| 1 | usuarios, estudantes, demandantes, coordenadores, escolas_instituicoes, cursos, convites, password_reset_tokens |
| 2 | tipologias, categorias_demanda, prioridades, projetos_base |
| 3 | solicitacoes, anexos |
| 4 | historicos, mensagens |
| 5 | subprojetos, candidaturas, papeis_equipe, membros_equipe |
| 6 | entregas, entrega_arquivos, validacoes |
| 7 | parametros (`logs_auditoria` fica para trabalhos futuros) |

---

## Registro de decisões

| Data | Decisão | Motivo |
|---|---|---|
| 2026-10-18 | O texto do TCC não será alterado por enquanto; o foco é o plano de construção do sistema. | Pedido do autor. |
| 2026-10-18 | O perfil "Gestor" passa a se chamar "Demandante". | Pedido do autor. |
| 2026-10-18 | Cadastros separados: formulário próprio para Estudante e para Demandante, a partir de uma tela de escolha; Professor Orientador só por convite do admin ou promoção; login único. | Reduzir confusão e erro na escolha do perfil e impedir que alguém se dê permissões de professor. |
| 2026-10-18 | RF04 reescrito: o admin aprova os usuários, define o status (`Ativo`, `Inativo`, `Pendente`, `Rejeitado`) e troca o perfil. | Pedido do autor. |
| 2026-10-18 | RF05: quem cadastra Projetos Base é o Coordenador do Projeto de Extensão (e o Admin). | Pedido do autor. |
| 2026-10-18 | Novo RF14: consulta pública dos projetos de extensão sem login; participar/inscrever-se exige login. Entra na Fase 5. | Pedido do autor. |
| 2026-10-18 | Novo RF15: relatório de demandas executadas por extensionista, com data de início/fim e carga horária estimada. Entra na Fase 6 e substitui o "comprovante de participação" opcional da Fase 7. | Pedido do autor. |
| 2026-10-18 | O perfil "Professor Orientador" passa a se chamar "Coordenador projeto de extensão" e concentra RF05, RF07, RF09, RF11 e RF13. | Pedido do autor: é o mesmo perfil. |
| 2026-10-18 | O estudante também fica `Pendente` até o admin aprovar. | Pedido do autor. |
| 2026-10-18 | A demanda vinculada a um projeto de extensão vira um **sub-projeto** (tabela `subprojetos`). A carga horária estimada é uma por sub-projeto, preenchida pelo Admin ou pelo coordenador e editável em qualquer status, com registro no histórico. | Pedido do autor; o registro no histórico garante rastreabilidade das correções. |
| 2026-10-18 | Ciclo de status da demanda sem mudanças automáticas: todos os status são alterados manualmente (coordenador/admin; `Cancelada` também pelo demandante antes de `Em Desenvolvimento`). A tela só sugere o próximo status. | Pedido do autor. |
| 2026-10-18 | Campos específicos de cada perfil ficam em tabelas 1:1 (`estudantes`, `demandantes`, `coordenadores`), e não em `usuarios`. | O plano tinha duas versões; tabelas por perfil evitam colunas vazias e separam as regras de cada cadastro. |
| 2026-10-18 | DER aprovado: o demandante escolhe a comunidade/instituição da lista ou digita uma nova (conferida pelo admin na aprovação); membro removido não aparece no RF15; papéis iniciais Membro Desenvolvedor, Responsável Técnico e Apoio de Campo; todo Projeto Base ativo é público por padrão, e o coordenador pode ocultar. | Pedido do autor. |
| 2026-10-18 | Decisões de implementação da Fase 1: (a) a confirmação de e-mail vale só para o estudante, como decidido na Fase 0; (b) senha com no mínimo 8 caracteres, letras e números; (c) comunidade/instituição digitada pelo demandante nasce inativa e é ativada na aprovação; (d) o admin não altera o próprio status ou perfil; (e) na troca de perfil, os dados do perfil anterior ficam guardados; (f) estudante sem e-mail confirmado não pode ser aprovado. | Segurança e coerência com as decisões da Fase 0; aprovadas pelo autor junto com a Fase 1. |
| 2026-10-21 | Decisões de implementação da Fase 2 (aprovadas pelo autor junto com a fase): (a) o coordenador edita só os projetos que coordena; (b) só o admin troca o coordenador responsável; (c) Projeto Base e itens de domínio não são apagados, só inativados; (d) `projetos_base.coordenador_id` aceita nulo só para o "Demandas Gerais" até o admin definir o coordenador; (e) demandante e estudante consultam a lista de projetos ativos. | Segurança, rastreabilidade e a decisão da Fase 0 sobre "Demandas Gerais". |
| 2026-10-21 | Decisões de implementação da Fase 3 (aprovadas pelo autor junto com a fase): (a) o coordenador vê e classifica só as demandas dos projetos que coordena; as do "Demandas Gerais" sem coordenador ficam com o admin; (b) até 5 anexos por demanda, opcionais; (c) o demandante não edita a demanda depois de enviada (ajustes vão pelo chat da Fase 4); (d) "Priorizada" só depois de categoria, prioridade e complexidade definidas; (e) a triagem pode ser revista até "Priorizada"; (f) uma demanda recusada pode ser reaberta para "Em Análise"; (g) o histórico das mudanças de status (tabela `historicos`) entra na Fase 4; até lá fica gravado só o status atual, o motivo e quem fez a triagem. | Segurança e coerência com as decisões da Fase 0. |
| 2026-10-21 | Decisões de implementação da Fase 4 (aprovadas pelo autor junto com a fase): (a) mensagens do chat não são editadas nem apagadas; (b) o chat fica só para leitura em "Concluída" e "Cancelada" e continua aberto em "Recusada"; (c) todos os envolvidos veem na linha do tempo quem fez cada ação; (d) `mensagens.perfil` guarda o perfil do autor no envio e `historicos.usuario_id` aceita nulo para mudanças fora de uma sessão de usuário; (e) sem aviso de nova mensagem nesta fase (fica para a Fase 7). | Rastreabilidade e coerência com as decisões da Fase 0. |
| 2026-10-21 | Decisões de implementação da Fase 5 (aprovadas pelo autor junto com a fase): (a) o sub-projeto só é criado com a demanda "Priorizada", uma vez por demanda, e "Em Desenvolvimento" só aparece depois dele; (b) o responsável é o coordenador que cria (se for o admin, o coordenador do Projeto Base); (c) com a demanda concluída, cancelada ou recusada, só a carga horária pode ser corrigida; (d) a equipe completa tira o sub-projeto da vitrine; (e) quem cancela a candidatura pode se candidatar de novo, quem foi recusado ou removido não; (f) mudanças na equipe vão para a linha do tempo, candidaturas não; (g) o estudante da equipe não vê os contatos do demandante; (h) a página pública não mostra a carga horária e omite projetos ocultos; (i) o formulário avisa que o escopo é público. | Segurança, LGPD e coerência com as decisões da Fase 0. |
| 2026-10-21 | RF13: a etapa "Homologação" passa a se chamar **Aceite** ("Aceitar Solução"); o admin e o coordenador também podem registrar o aceite ou a pendência em nome do demandante, com indicação na linha do tempo. | Pedido do autor: os projetos de extensão não são só de programação, e a demanda não deve travar quando o demandante confirma por outro meio. |
| 2026-10-27 | Revisão das decisões da Fase 6 pelo autor: (a) várias entregas ao mesmo tempo, numeradas na ordem de envio; (b) o coordenador e o admin podem enviar entregas em nome da equipe; (g) planilhas do Excel aceitas nos arquivos e no relatório técnico; (h) o período do relatório conta só os sub-projetos que começaram e terminaram entre as datas; (c), (d), (e), (f) e (i) aprovadas como estavam. | Pedido do autor. |
| 2026-10-27 | Decisões de implementação da Fase 7 (aprovadas pelo autor): (a) e-mails de cadastro, convite e senha sempre ligados, o parâmetro desliga só os avisos novos; (b) um e-mail por mensagem do chat; (c) admins só recebem avisos das demandas sem coordenador; (d) avisos extras de nova demanda, nova candidatura, entrega enviada, aprovação técnica e aceite; (e) avisos pela fila do banco; (f) tempo médio de atendimento só com demandas concluídas e período pela data de abertura; (g) sem PDF nem gráfico por mês no dashboard; (h) menu "Configurações" do admin. | Pedido do autor (Fase 7). |
| 2026-10-27 | A Fase 9 (melhorias) entra antes da retomada da Fase 8: a Fase 8 fica pausada depois do roteiro de testes, e os testes com usuários e o deploy usam o sistema já melhorado. | Pedido do autor. |
| 2026-10-27 | Decisões da Fase 9 (aprovadas pelo autor): (a) imagem e foto em JPG, PNG ou WebP até 5 MB, regravadas em JPEG (projeto até 1600 px; foto 400 × 400 px, recortada pelo centro), sem os metadados do original; (b) arquivos fora da pasta pública, servidos por rota própria; (c) imagem de projeto público abre sem login; (d) foto de perfil só para quem está logado; (e) e-mail, perfil, status, matrícula e comunidade/instituição não mudam pelo "Meu perfil"; (f) troca de senha exige a senha atual, com até 6 tentativas por minuto; (g) só demandante ativo da comunidade/instituição escolhida e só comunidade/instituição ativa; (h) quem abre a demanda não recebe aviso da própria ação; (i) a demanda aberta não muda de demandante nem de comunidade/instituição. | Pedido do autor. |
| 2026-10-27 | Decisões do segundo lote da Fase 9 (aprovadas pelo autor): (j) só estudantes ativos e fora da equipe podem ser incluídos; (k) a inclusão respeita as vagas e nada é gravado se der erro; (l) candidatura pendente é aprovada na inclusão, e quem entra sem candidatura não se candidata ao mesmo sub-projeto; (m) os incluídos de uma vez recebem o mesmo papel; (n) o "Voltar" leva à tela de origem fixa, não ao histórico do navegador; (o) o término corrigido não pode ser anterior ao início. | Pedido do autor. |
| 2026-10-27 | Decisões do terceiro lote da Fase 9 (aprovadas pelo autor): (p) o admin, em qualquer projeto, e o coordenador do projeto vinculam e desvinculam estudantes; (q) desvincular não tira o estudante das equipes de que já faz parte; (r) só estudantes ativos são vinculados, e quem teve a conta inativada continua marcado até ser desmarcado; (s) a vitrine e a aprovação de candidatura também exigem o vínculo com o Projeto Base. | Pedido do autor. |
| 2026-10-29 | Correções do teste completo da Fase 8 (escolhas e decisões aprovadas pelo autor): E4, coordenador inativado ou com outro perfil passa os Projetos Base e os sub-projetos em andamento para a administração, e os encerrados ficam no nome dele; E6, trocar o coordenador do Projeto Base não passa os sub-projetos existentes; (t) removido não se candidata de novo, volta só pela coordenação; (u) sub-projetos cancelados ou recusados também ficam com o coordenador; (v) demanda recusada não é cancelada; (w) o admin não edita o e-mail; (x) aviso amigável nas decisões da entrega e página "Acesso negado" explicada nas demais; (y) motivo da recusa opcional, até 500 caracteres. | Pedido do autor. |

## Registro de progresso

| Data | Fase | O que foi feito |
|---|---|---|
| 2026-10-18 | — | Pasta `PGDT/` e este plano criados. |
| 2026-10-18 | 0 | Fase 0 iniciada. Ambiente verificado: PHP 8.3, Node 24 e Git ok; faltam Composer, MySQL/XAMPP e extensões do PHP. Propostas registradas para as 10 decisões pendentes. |
| 2026-10-18 | 0 | Ambiente pronto: extensões do PHP 8.3 ativadas, Composer 2.10.3 e XAMPP 8.2.12 (MariaDB 10.4.32) instalados, banco `pgdt` criado e conexão testada. |
| 2026-10-18 | 0 | Ciclo de status ajustado: todas as mudanças passam a ser manuais (RF09 e RF13 atualizados). |
| 2026-10-18 | 0 | As 9 propostas restantes foram aprovadas. Projeto Laravel 13 criado em `sistema/` com MySQL, pt_BR e `laravel/ui` + Bootstrap; página inicial funcionando em `php artisan serve`; repositório Git local iniciado com o primeiro commit. |
| 2026-10-18 | 0 | Repositório no GitHub configurado (`origin` = github.com/Plataforma-PGDT/Plataforma-PGDT) e branch `main` enviada; fluxo `main` + `feature/*` definido. |
| 2026-10-18 | 0 | DER completo revisado em `docs/DER.md` (23 tabelas no diagrama + `parametros`); plano ajustado: tabelas por perfil, `papeis_equipe`, `entrega_arquivos`. Diagrama Mermaid validado. Fase 0 aguarda a confirmação de 4 pontos do DER. |
| 2026-10-18 | 0 | Autor aprovou os 4 pontos do DER. **Fase 0 concluída**: decisões registradas, ambiente pronto, `php artisan serve` conectado ao MySQL e repositório no GitHub atualizado. |
| 2026-10-18 | 1 | Fase 1 iniciada na branch `feature/fase1-usuarios`. Traduções pt-BR do Laravel instaladas (`lucascudo/laravel-pt-br-localization`). |
| 2026-10-18 | 1 | Entregáveis da Fase 1 implementados: tabelas por perfil, cadastros separados, convite de coordenador, login com bloqueio por status, recuperação de senha, painel do admin (filtros, aprovação, status, troca de perfil) e menu por perfil. 34 testes passando e fluxo conferido no navegador. Aguarda validação do autor para juntar na `main`. |
| 2026-10-18 | 1 | Autor aprovou a Fase 1. **Fase 1 concluída**: branch `feature/fase1-usuarios` enviada ao GitHub e juntada na `main`. |
| 2026-10-18 | — | Passo a passo de instalação em máquina nova salvo em `docs/INSTALACAO.md`. |
| 2026-10-21 | 2 | Fase 2 iniciada na branch `feature/fase2-projetos-base`. Entregáveis implementados: tabelas de domínio com seeders, cadastros de apoio do admin, CRUD e listagem de Projetos Base com permissões por perfil. 53 testes passando; migrations e seeders rodados no MySQL local. Falta a conferência no navegador com login e a validação do autor. |
| 2026-10-21 | 2 | Telas conferidas no navegador como admin; duas correções de formulário feitas. Aguarda validação do autor (incluindo o fluxo do coordenador, que precisa de um coordenador convidado) para juntar na `main`. |
| 2026-10-21 | 2 | Autor cadastrou um coordenador pelo convite (link lido no `laravel.log`, pois o ambiente local usa `MAIL_MAILER=log`) e aprovou a Fase 2. **Fase 2 concluída**: branch `feature/fase2-projetos-base` juntada na `main`. |
| 2026-10-21 | 3 | Fase 3 iniciada na branch `feature/fase3-demandas`. |
| 2026-10-21 | 3 | Entregáveis da Fase 3 implementados: demandas com anexos (upload arrasta-e-solta, arquivos fora da pasta pública), "Minhas demandas", fila de triagem, classificação e status iniciais. 77 testes passando; migration rodada no MySQL local; fluxo conferido no navegador (computador e celular). Aguarda validação do autor para juntar na `main`. |
| 2026-10-21 | 3 | Autor aprovou a Fase 3 e as decisões de implementação (a)–(g). **Fase 3 concluída**: branch `feature/fase3-demandas` juntada na `main` e enviada ao GitHub. |
| 2026-10-21 | 4 | Fase 4 iniciada na branch `feature/fase4-rastreabilidade`. Entregáveis implementados: tabelas `historicos` e `mensagens`, registro automático pelo Observer, barra de progresso, linha do tempo e chat com acesso restrito. 93 testes passando; migration rodada no MySQL local; fluxo conferido no navegador (computador e celular). Aguarda validação do autor e das decisões (a)–(e) para juntar na `main`. |
| 2026-10-21 | 4 | Autor aprovou a Fase 4 e as decisões de implementação (a)–(e). **Fase 4 concluída**: branch `feature/fase4-rastreabilidade` juntada na `main` e enviada ao GitHub. |
| 2026-10-21 | 5 | Fase 5 iniciada na branch `feature/fase5-subprojetos`. Entregáveis implementados: sub-projetos com carga horária no histórico, vitrine de oportunidades, candidaturas, equipe com papéis e página pública `/projetos`. 127 testes passando; migration rodada no MySQL local; critério de conclusão conferido no navegador (computador e celular). Aguarda validação do autor e das decisões (a)–(i) para juntar na `main`. |
| 2026-10-21 | 5 | Autor aprovou a Fase 5 e as decisões de implementação (a)–(i). **Fase 5 concluída**: branch `feature/fase5-subprojetos` juntada na `main` e enviada ao GitHub. |
| 2026-10-21 | 6 | Fase 6 iniciada na branch `feature/fase6-entregas`. Entregáveis implementados: entregas com tipo dinâmico e upload, validação técnica e aceite com registro na linha do tempo, status de execução com sugestão, relatório por extensionista com exportação em PDF (`barryvdh/laravel-dompdf`). 158 testes passando, incluindo o critério de conclusão de ponta a ponta; migration rodada no MySQL local. Falta a conferência no navegador (login nas contas de teste) e a validação do autor e das decisões (a)–(i). |
| 2026-10-21 | 6 | A pedido do autor, "homologar" passou a se chamar "aceite" ("Aceitar Solução", "Aguardando aceite", "Solução aceita") em telas, código, rotas e DER, e o admin e o coordenador passaram a ver os botões "Aceitar Solução" e "Reportar Pendência" para registrar a decisão em nome do demandante. 161 testes passando. |
| 2026-10-27 | 6 | As caixas de confirmação do navegador (`confirm`/`alert`) foram trocadas por um modal do Bootstrap em todas as telas (status, perfil, candidaturas, equipe e entregas), porque o navegador embutido do app as bloqueava e as mudanças não eram enviadas. Conferido no navegador: "Cancelar" não envia e "Confirmar" envia. A demanda de teste foi para "Em Desenvolvimento" (passo 1 da conferência da Fase 6). |
| 2026-10-27 | 6 | Autor concluiu a conferência no navegador: entrega → aprovação técnica → aceite (registrado pelo admin em nome do demandante) → "Concluída" → relatório. Critério de conclusão atingido. Aguarda a aprovação das decisões (a)–(i) e do pacote `barryvdh/laravel-dompdf` para juntar na `main`. |
| 2026-10-27 | 6 | Autor revisou as decisões (a)–(i). Implementado: entregas numeradas e simultâneas (migration `2026_10_27_000001_add_numero_to_entregas_table`, que numerou a entrega de teste como nº 1), envio pela coordenação em nome da equipe, Excel nos arquivos e período estrito no relatório. 164 testes passando. |
| 2026-10-27 | 6 | A pedido do autor, o relatório técnico também aceita planilhas do Excel (XLSX e XLS). 165 testes passando. |
| 2026-10-27 | 6 | Autor aprovou a Fase 6, as decisões de implementação (a)–(i) revisadas e o pacote `barryvdh/laravel-dompdf`. **Fase 6 concluída**: branch `feature/fase6-entregas` juntada na `main` e enviada ao GitHub. |
| 2026-10-27 | 7 | Fase 7 iniciada na branch `feature/fase7-complementos`. O autor escolheu o dashboard gerencial, as notificações por e-mail e os parâmetros gerais; o log de auditoria fica como trabalho futuro. |
| 2026-10-27 | 7 | Entregáveis da Fase 7 implementados: parâmetros gerais, avisos por e-mail pela fila e dashboard gerencial. 187 testes passando; migration rodada no MySQL local. Falta a conferência no navegador com login e a aprovação das decisões (a)–(h) pelo autor. |
| 2026-10-27 | 7 | Autor aprovou as decisões de implementação (a)–(h). Falta a conferência no navegador com login para concluir a fase. |
| 2026-10-27 | 7 | Autor conferiu as telas no navegador e aprovou a Fase 7. **Fase 7 concluída**: branch `feature/fase7-complementos` juntada na `main` e enviada ao GitHub. |
| 2026-10-27 | 8 | Fase 8 iniciada na branch `feature/fase8-testes-implantacao`. Autor decidiu: melhorias numa Fase 9 depois da implantação; produção a definir a partir de um levantamento de opções; testes com usuários por roteiro de tarefas. 187 testes passando no início da fase. |
| 2026-10-27 | 8 | A pedido do autor, a hospedagem e o deploy passaram para o último passo da fase; os testes com usuários serão feitos no ambiente local. |
| 2026-10-27 | 8 | Revisão de segurança concluída: limite de envios nos cadastros, cabeçalhos de segurança, texto dos usuários sem Markdown nos e-mails e título do projeto no PDF restrito. 8 testes novos (`SegurancaTest`); 195 no total, todos passando. Pendentes: aviso de privacidade (LGPD) e checklist de produção para o deploy. |
| 2026-10-27 | 8 | Rascunho do aviso de privacidade (LGPD) em `/privacidade`, ligado ao rodapé e aos cadastros; conferido no navegador (computador e celular). 196 testes passando. Aguarda a revisão do autor e os dados da UFES. |
| 2026-10-27 | 8 | Testes de responsividade: 35 telas medidas em celular, tablet e computador. Corrigido o menu do admin, que ficava mais largo que a tela no tablet. Telas dos outros perfis ficam para a conferência com os dados de demonstração. O aviso de privacidade continua como rascunho, aguardando a revisão do autor. |
| 2026-10-27 | 8 | Dados de demonstração: `DemonstracaoSeeder` com 11 demandas (uma em cada etapa), 14 contas fictícias e passo a passo em `docs/INSTALACAO.md`. Carregado no banco separado `pgdt_demo`; telas de estudante, demandante e coordenador conferidas no celular, tablet e computador. 201 testes passando. |
| 2026-10-27 | 8 | Roteiro de testes com usuários criado, com tarefas por perfil, perguntas finais e ficha de registro; tarefas conferidas por um teste automático com os dados de demonstração. 202 testes passando. Aguarda a aplicação dos testes pelo autor. |
| 2026-10-27 | 8 | Fase 8 pausada a pedido do autor para a Fase 9 entrar antes. Feitos: segurança, responsividade, dados de demonstração e roteiro. Faltam: aviso de privacidade, testes com usuários, correções e deploy. |
| 2026-10-27 | 8 | Branch `feature/fase8-testes-implantacao` enviada ao GitHub como cópia de segurança, sem juntar na `main`. |
| 2026-10-27 | 9 | Fase 9 iniciada na branch `feature/fase9-melhorias`, criada a partir da `feature/fase8-testes-implantacao`. Aguarda o autor definir a lista de melhorias. |
| 2026-10-27 | 9 | Imagem do Projeto de extensão (com fundo padrão) e "Meu perfil" (dados, foto com avatar padrão por perfil e senha) implementados. Colunas `projetos_base.imagem` e `usuarios.foto`. 223 testes passando. Aguarda a aprovação do autor e das decisões (a)–(f). |
| 2026-10-27 | 9 | Coordenador (nos projetos que coordena) e admin (em qualquer projeto) passam a abrir demandas, em nome de uma comunidade/instituição e, se houver, de um demandante. Colunas: `solicitacoes.demandante_id` opcional e `solicitacoes.cadastrada_por`. 229 testes passando. Aguarda a aprovação do autor e das decisões (g)–(i). |
| 2026-10-27 | 9 | Segundo lote de melhorias: comunidade/instituição opcional na demanda da coordenação (migration `2026_10_27_000005`, rodada nos bancos `pgdt` e `pgdt_demo`), combos com pesquisa, inclusão manual de estudantes na equipe, botão "Voltar" padronizado, linha do tempo em janela e término do sub-projeto corrigível depois do encerramento. 233 testes passando. Aguarda a aprovação das decisões (j)–(o). |
| 2026-10-27 | 9 | Autor aprovou as decisões (j)–(o) do segundo lote. Seguem pendentes a aprovação das decisões (a)–(i) da Fase 9 e o item dos cartões no celular. |
| 2026-10-27 | 9 | Autor aprovou as decisões (a)–(i) da Fase 9. Falta o item dos cartões no celular (lista de usuários e relatório). |
| 2026-10-27 | 9 | Cartões no celular na lista de usuários e no relatório por extensionista. Todos os itens da Fase 9 prontos; 233 testes passando. Aguarda a validação do autor (conferência no navegador) para concluir a fase. |
| 2026-10-27 | 9 | Terceiro lote: papel "Outros", texto da carga horária retirado, filtro em Sub-projetos e estudantes vinculados ao Projeto Base (tabela `estudantes_projeto_base`, migrations `2026_10_27_000006` e `000007`, rodadas nos bancos `pgdt` e `pgdt_demo`); o sub-projeto só aloca os vinculados. 239 testes passando. Aguarda as decisões (p)–(s). |
| 2026-10-27 | 9 | Autor escolheu restringir também a vitrine e a aprovação de candidatura aos estudantes vinculados ao Projeto Base (decisão (s)). Implementado, com os dados de demonstração vinculando os estudantes. 241 testes passando. Aguarda a aprovação das decisões (p)–(r). |
| 2026-10-27 | 9 | Autor aprovou as decisões (p)–(r). Todas as decisões da Fase 9, de (a) a (s), estão aprovadas. Falta a conferência no navegador pelo autor para concluir a fase. |
| 2026-10-27 | 9 | Complemento dos dados de demonstração (`DemonstracaoFase9Seeder`), que só acrescenta dados, rodado no `pgdt_demo` sem apagar nada. 242 testes passando. Falta a conferência no navegador pelo autor. |
| 2026-10-29 | 9 | Correções do teste completo da Fase 8: E1 a E11 e os pontos 1, 7 e 9 do ensaio (migrations `2026_10_29_000001` a `000003`, aplicadas em `pgdt`, `pgdt_demo` e `pgdt_teste`). Autor escolheu as regras do E4 e do E6. 255 testes passando. Aguarda as decisões (t)–(y) e o reteste no navegador. |
| 2026-10-29 | 9 | Autor aprovou as decisões (t)–(y) das correções. Falta repetir no navegador os passos que geraram cada erro (sessão da Fase 8, banco `pgdt_teste`). |
| 2026-10-29 | 9 | E10 ajustado: o autor não conseguia trocar o curso pela tela. "Editar dados" fica sempre aberto e "Trocar perfil" não mostra mais os campos do perfil atual. 255 testes passando; tela conferida no navegador. |
| 2026-10-27 | 8 | Ensaio do roteiro de testes com usuários feito no navegador, com o código da Fase 9, no banco separado `pgdt_teste` (sem tocar no `pgdt_demo`): 23 de 24 tarefas concluídas, sem erros; 9 problemas registrados na seção da Fase 8 (4 do sistema para a Fase 9, 5 do roteiro e dos dados). |
| 2026-10-27 | 8 | Roteiro de testes com usuários corrigido (Bruno, D2, A5 e preparação no banco `pgdt_teste`, sem apagar o `pgdt` nem o `pgdt_demo`). Falta ajustar as datas da demonstração (item 8). |
| 2026-10-27 | 8 | Teste completo de todas as ações de cada perfil no `pgdt_teste`: cerca de 400 telas sem erro e matriz de acesso correta; 9 erros registrados (E1–E9), dois deles com erro 500 na equipe e um que deixa o admin cancelar demanda concluída. Aguarda o autor decidir as correções. |
| 2026-10-29 | 8 | Erro achado pelo autor reproduzido: o admin não consegue trocar o curso (nem outros dados) de um usuário sem trocar o perfil (E10). Achado junto: o bloco "Conferir comunidade/instituição" edita a comunidade/instituição de todos (E11). Para a sessão da Fase 9 corrigir. |
| 2026-10-29 | 8 | Reteste completo depois das correções, no banco novo `pgdt_teste2`: 45 ações do inventário, 255 testes automáticos, permissões, ciclo completo, parâmetros, fila e log. E1–E11 conferidos como corrigidos; 5 erros novos (N1–N5) e textos menores registrados para a Fase 9. |
| 2026-11-01 | 8 | Autor decidiu não corrigir por enquanto os erros N1–N5 e os textos menores do reteste; ficam como pendências conhecidas. |
| 2026-11-01 | 9 | Branch `feature/fase9-melhorias` enviada ao GitHub, com todas as melhorias e correções da Fase 9 e o registro do reteste. A junção na `main` fica para o fim da Fase 8. |
| 2026-11-01 | 8 e 9 | A pedido do autor, a branch `feature/fase9-melhorias` (que já contém todo o trabalho da `feature/fase8-testes-implantacao`) foi juntada na `main` e enviada ao GitHub, antes do fim da Fase 8. 255 testes passando. A Fase 8 continua (aviso de privacidade, testes com usuários e deploy), e a Fase 9 segue contínua. |
| 2026-11-01 | Documentação | Material para a apresentação ao orientador e para o TCC, feito uma parte de cada vez. Primeira parte: fluxogramas em [`docs/FLUXOGRAMA.md`](docs/FLUXOGRAMA.md) (Mermaid), com a visão geral, o cadastro e acesso, o ciclo de status da demanda, a equipe do sub-projeto e a entrega com validação em duas etapas, conferidos contra o código atual. Próximas partes: requisitos novos, modelo de dados e casos de uso. |
| 2026-11-01 | Documentação | Segunda parte: requisitos atualizados a partir do documento original "Funcionalidades do sistema (RF)" (RF01–RF13), em [`docs/REQUISITOS.md`](docs/REQUISITOS.md) e no PDF `docs/Funcionalidades do sistema (RF) - atualizado.pdf` (16 páginas, mesmo formato do original). RF01–RF13 reescritos como estão implementados, com "O que mudou"; RF14 e RF15; novos RF16 a RF22 (convite de coordenador, cadastros de apoio, dashboard, avisos por e-mail, parâmetros gerais, Meu perfil e vínculo de estudantes); RNF01 a RNF08 (o original não tinha RNFs; numeração a conferir com o TCC1). O PDF original na Área de Trabalho não foi alterado. |
| 2026-11-01 | Documentação | Terceira parte: modelo de dados. [`docs/DER.md`](docs/DER.md) conferido com as 19 migrations e reorganizado em visão conceitual (para a apresentação), três diagramas lógicos com atributos (usuários e cadastros; projetos e demandas; sub-projetos, equipe e entregas), dicionário das 24 tabelas e tabela de mudanças em relação ao DER do documento original. Corrigido: faltavam no diagrama `estudantes_projeto_base`, `parametros`, `cadastrada_por` e `aprovado_por`; `motivo_recusa` e `encerrado_como` entraram nas tabelas do dicionário; `logs_auditoria` saiu do diagrama (trabalho futuro). Próxima parte: casos de uso. |
| 2026-11-01 | Documentação | Quarta parte: casos de uso em [`docs/CASOS_DE_USO.md`](docs/CASOS_DE_USO.md). Seis atores, 24 casos de uso (UC01–UC24), quatro diagramas por área (acesso e cadastro; projetos e demandas; execução e validação; gestão e acompanhamento), especificação de cada caso (atores, RF, pré-condição, fluxo principal, fluxos alternativos, pós-condição) e matriz de rastreabilidade RF × UC, conferidos com o código. |
| 2026-11-01 | Documentação | Requisitos na versão de entrega, a pedido do autor: sem comentários, notas de mudança e tabela de resumo, no formato do documento original ("RFxx - Nome:" com Campos/Parâmetros Disponíveis, Regra de Negócio e Permissões). Escolhas do autor: incluir os RNF01–RNF08 e terminar com o DER conceitual. PDF renomeado para `docs/Funcionalidades do sistema (RF).pdf` (10 páginas). O histórico das mudanças em relação ao original fica no commit anterior de `docs/REQUISITOS.md`. |
| 2026-11-01 | Documentação | Os 4 commits de documentação (fluxogramas, requisitos, modelo de dados e casos de uso) foram enviados ao GitHub na branch `feature/fase9-melhorias`. A `main` ainda não tem esses commits. |
