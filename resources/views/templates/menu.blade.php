 <nav class="navbar navbar-default navbar-static-top navbar-fixed-top">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url('/img/iete-logo.png') }}" style="margin-left:auto;margin-right:auto; max-height:25px;webkit-filter: grayscale(100%) brightness(0%) invert(100%);filter: grayscale(100%) brightness(0%) invert(100%);">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                  @if (!Auth::guest() && Auth::user()->level!="vendedor")
                    <li class="dropdown">
                      <a href="{{ url('/rhumanos') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Cadastros <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ url('/pessoas/cliente') }}">Clientes</a></li>
                        <li><a href="{{ url('/pessoas/funcionario') }}">Funcionários</a></li>
                        <li><a href="{{ url('/pessoas/fornecedor') }}">Fornecedores</a></li>
                        <li><a href="{{ url('/users') }}">Usuários</a></li>
                      </ul>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-money" aria-hidden="true"></span> Contas <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                          <li><a href="{{ url('/financeiro/contasapagar') }}"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> Contas a Pagar</a></li>
                          <li><a href="{{ url('/financeiro/contasareceber') }}"><i class="fa fa-money" aria-hidden="true"></i> Contas a Receber</a></li>
                      </ul>
                    </li>

                    <li class="dropdown">
                      <a href="{{ url('/produtos') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Produtos <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ url('/produtos') }}">Produtos</a></li>
                        <li><a href="{{ url('/categorias') }}">Categorias</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('/estoque') }}">Estoque</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('/unidades') }}">Unidade de Medida</a></li>
                        <li><a href="{{ url('/impostos') }}">Despesas sobre Prod.</a></li>
                        <li><a href="{{ url('/taxageral') }}">Aplicar Agregado Geral</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('/etiquetas') }}">Gerar Etiquetas</a></li>
                      </ul>
                    </li>
                    <li class="dropdown">
                      <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> PDV <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ url('/vendas') }}">Vendas</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('/cheques') }}">Cheques</a></li>
                        <li><a href="{{ url('/tipopagamentos') }}">Tipo Pagamento</a></li>
                      </ul>
                    </li>
                    <li class="hidden-md"><a href="{{ url('/relatorios') }}"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Relatórios</a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="glyphicon glyphicon-log-out"></i> Sair</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
    </nav>
