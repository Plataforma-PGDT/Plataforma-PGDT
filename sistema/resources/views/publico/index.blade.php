@extends('layouts.app')

@section('titulo', 'Projetos de extensão')

{{--
    RF14: consulta pública, sem login. A página fala primeiro com a comunidade (como pedir ajuda)
    e deixa as vagas dos estudantes numa seção própria, no fim.
--}}
@php($podePedir = auth()->guest() || auth()->user()->can('create', \App\Models\Solicitacao::class))
@php($linkPedirAjuda = auth()->guest() ? route('cadastro.demandante') : route('demandas.create'))

@section('content')
<div class="container pagina-publica">

    <section class="card shadow-sm border-0 mb-4" aria-labelledby="titulo-pagina">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <p class="text-primary fw-bold text-uppercase small mb-2">Projetos de extensão da UFES</p>
                    <h1 id="titulo-pagina" class="display-6 fw-bold mb-3">Sua comunidade ou instituição precisa de ajuda com tecnologia?</h1>
                    <p class="lead text-body-secondary mb-4">
                        Estudantes e professores da UFES desenvolvem soluções de tecnologia para comunidades e instituições da região.
                        Conte o que você precisa e a coordenação acompanha o seu pedido.
                    </p>
                    @if ($podePedir)
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <a href="{{ $linkPedirAjuda }}" class="btn btn-primary btn-lg">Quero pedir ajuda</a>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Já tenho cadastro – entrar</a>
                            @endguest
                        </div>
                    @endif
                    <p class="text-body-secondary mb-0">Não precisa entender de tecnologia: é só explicar o problema com as suas palavras.</p>
                </div>

                @if ($categorias->isNotEmpty())
                    <div class="col-lg-5">
                        <div class="quadro-ajuda rounded-3 p-4">
                            <h2 class="h5 fw-bold mb-3">Que tipo de ajuda dá para pedir</h2>
                            <ul class="list-unstyled mb-0 d-grid gap-2">
                                @foreach ($categorias as $categoria)
                                    <li class="d-flex gap-2 align-items-start">
                                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" class="text-primary flex-shrink-0 mt-1"><path d="M20 6 9 17l-5-5"/></svg>
                                        <span>{{ $categoria->nome }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="como-funciona" class="mb-5" aria-labelledby="titulo-como-funciona">
        <h2 id="titulo-como-funciona" class="h3 fw-bold mb-1">Como funciona</h2>
        <p class="text-body-secondary mb-3">Do pedido até a entrega, você acompanha tudo pelo seu painel.</p>

        <ol class="list-unstyled row row-cols-1 row-cols-md-2 row-cols-xl-5 g-3 mb-0">
            <li class="col">
                <div class="card h-100 shadow-sm passo">
                    <div class="card-body">
                        <span class="passo-numero" aria-hidden="true">1</span>
                        <h3 class="h6 fw-bold mt-3">Você faz o cadastro</h3>
                        <p class="small text-body-secondary mb-0">Informe os dados da sua comunidade ou instituição. A coordenação confere e libera o seu acesso.</p>
                    </div>
                </div>
            </li>
            <li class="col">
                <div class="card h-100 shadow-sm passo passo-destaque">
                    <div class="card-body d-flex flex-column">
                        <span class="passo-numero" aria-hidden="true">2</span>
                        <h3 class="h6 fw-bold mt-3">Escolhe o projeto de extensão</h3>
                        <p class="small text-body-secondary">
                            Cada pedido fica ligado a um projeto de extensão. Escolha o que mais combina com a sua necessidade;
                            na dúvida, escolha "{{ \App\Models\ProjetoBase::DEMANDAS_GERAIS }}".
                        </p>
                        @if ($podePedir && $projetos->isNotEmpty())
                            <form method="GET" action="{{ route('demandas.create') }}" class="mt-auto">
                                <label for="escolha-projeto" class="form-label small fw-semibold">Projeto de extensão</label>
                                <select id="escolha-projeto" name="projeto" class="form-select form-select-sm mb-2" required>
                                    <option value="">Escolha o projeto...</option>
                                    @foreach ($projetos as $projeto)
                                        <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Pedir ajuda neste projeto</button>
                            </form>
                        @endif
                        <a href="#projetos" class="small mt-2">Conhecer os projetos</a>
                    </div>
                </div>
            </li>
            <li class="col">
                <div class="card h-100 shadow-sm passo">
                    <div class="card-body">
                        <span class="passo-numero" aria-hidden="true">3</span>
                        <h3 class="h6 fw-bold mt-3">Conta o que precisa</h3>
                        <p class="small text-body-secondary mb-0">Descreva o problema com as suas palavras. Pode anexar fotos ou documentos.</p>
                    </div>
                </div>
            </li>
            <li class="col">
                <div class="card h-100 shadow-sm passo">
                    <div class="card-body">
                        <span class="passo-numero" aria-hidden="true">4</span>
                        <h3 class="h6 fw-bold mt-3">Uma equipe da UFES trabalha</h3>
                        <p class="small text-body-secondary mb-0">A coordenação do projeto analisa o pedido e monta uma equipe de estudantes orientada por professores.</p>
                    </div>
                </div>
            </li>
            <li class="col">
                <div class="card h-100 shadow-sm passo">
                    <div class="card-body">
                        <span class="passo-numero" aria-hidden="true">5</span>
                        <h3 class="h6 fw-bold mt-3">Você recebe e aprova</h3>
                        <p class="small text-body-secondary mb-0">Você testa o que foi entregue e confirma se atendeu. Pelo chat, tira dúvidas com a coordenação.</p>
                    </div>
                </div>
            </li>
        </ol>
    </section>

    <section id="projetos" class="mb-5" aria-labelledby="titulo-projetos">
        <h2 id="titulo-projetos" class="h3 fw-bold mb-1">Projetos de extensão</h2>
        <p class="text-body-secondary mb-3">Cada pedido é atendido por um destes projetos. Conheça o que cada um faz.</p>

        <div class="row g-3">
            @forelse ($projetos as $projeto)
                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 shadow-sm overflow-hidden">
                        <a href="{{ route('publico.show', $projeto) }}" class="d-block capa-cartao-projeto" tabindex="-1" aria-hidden="true">
                            <img src="{{ $projeto->urlImagem() }}" alt="" class="w-100 h-100 object-fit-cover" loading="lazy">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <span class="badge text-bg-light border align-self-start mb-2">{{ $projeto->tipologia->nome }}</span>
                            <h3 class="h5 fw-bold mb-2">
                                <a href="{{ route('publico.show', $projeto) }}" class="text-body text-decoration-none">{{ $projeto->titulo }}</a>
                            </h3>
                            <p class="small mb-2">{{ \Illuminate\Support\Str::limit($projeto->descricao, 220) }}</p>
                            <p class="small text-body-secondary mb-3">Coordenação: {{ $projeto->coordenador?->nome ?? 'a definir' }}</p>
                            <div class="mt-auto d-flex flex-wrap gap-2">
                                @if ($podePedir)
                                    <a href="{{ route('demandas.create', ['projeto' => $projeto->id]) }}" class="btn btn-sm btn-primary">Pedir ajuda neste projeto</a>
                                @endif
                                <a href="{{ route('publico.show', $projeto) }}" class="btn btn-sm btn-outline-primary">Saiba mais</a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="card card-body text-center text-muted py-4">Nenhum projeto de extensão publicado.</div>
                </div>
            @endforelse
        </div>
    </section>

    <section id="duvidas" class="mb-5" aria-labelledby="titulo-duvidas">
        <h2 id="titulo-duvidas" class="h3 fw-bold mb-3">Perguntas frequentes</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card card-body h-100 shadow-sm">
                    <h3 class="h6 fw-bold">Quem pode pedir ajuda?</h3>
                    <p class="small text-body-secondary mb-0">Comunidades, associações, escolas e outras instituições da região. Basta fazer o cadastro e aguardar a aprovação da coordenação.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 shadow-sm">
                    <h3 class="h6 fw-bold">Preciso entender de tecnologia?</h3>
                    <p class="small text-body-secondary mb-0">Não. Explique o problema do seu jeito; a coordenação e a equipe ajudam a transformar isso em uma solução.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 shadow-sm">
                    <h3 class="h6 fw-bold">E se eu não souber qual projeto escolher?</h3>
                    <p class="small text-body-secondary mb-0">Escolha "{{ \App\Models\ProjetoBase::DEMANDAS_GERAIS }}". A coordenação analisa o pedido e o encaminha da melhor forma.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 shadow-sm">
                    <h3 class="h6 fw-bold">Como acompanho o meu pedido?</h3>
                    <p class="small text-body-secondary mb-0">Entrando no sistema: você vê cada etapa, conversa com a coordenação pelo chat e aprova a entrega no final.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($podePedir)
        <section class="chamada-pedido rounded-4 p-4 p-lg-5 mb-5 text-white" aria-labelledby="titulo-chamada">
            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                <div>
                    <h2 id="titulo-chamada" class="h4 fw-bold mb-1">Pronto para contar o que sua comunidade precisa?</h2>
                    <p class="mb-0">
                        @guest
                            O cadastro leva poucos minutos. Depois da aprovação, você já pode fazer o seu pedido.
                        @else
                            Escolha o projeto de extensão e descreva o que você precisa.
                        @endguest
                    </p>
                </div>
                <a href="{{ $linkPedirAjuda }}" class="btn btn-light btn-lg fw-bold text-primary">Quero pedir ajuda</a>
            </div>
        </section>
    @endif

    <section id="vagas" class="secao-vagas rounded-4 p-3 p-lg-4" aria-labelledby="titulo-vagas">
        <h2 id="titulo-vagas" class="h4 fw-bold mb-1">É estudante da UFES? Veja as vagas abertas</h2>
        <p class="text-body-secondary mb-3">
            Participe de um sub-projeto e conte horas de extensão. Para se candidatar, entre com a sua conta de estudante.
        </p>

        @include('oportunidades._filtros', ['acao' => route('publico.index').'#vagas'])

        <div class="row g-3">
            @forelse ($subprojetos as $subprojeto)
                <div class="col-md-6 col-xl-4">
                    @include('oportunidades._card', ['publico' => true])
                </div>
            @empty
                <div class="col-12">
                    <div class="card card-body text-center text-muted py-4">
                        @if (array_filter($filtros))
                            Nenhum sub-projeto encontrado com esses filtros.
                        @else
                            Nenhum sub-projeto com vagas abertas no momento.
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $subprojetos->fragment('vagas')->links() }}
        </div>
    </section>
</div>
@endsection
