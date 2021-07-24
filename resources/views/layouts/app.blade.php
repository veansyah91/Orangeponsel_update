<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS Orange Ponsel</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Livewire -->
    <livewire:styles />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm fixed-top" style="background-color: #ECD47A">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    Orange Ponsel
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                        <ul class="navbar-nav mr-auto">
                            @role('SUPER ADMIN')
                                <li class="nav-item{{ request()->is('outlet') ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('outlet.index') }}">Outlet</a>
                                </li>
                            @endrole

                            @role('SUPER ADMIN|ADMIN|FRONT LINER')
                                <li class="nav-item{{ request()->is('inter-outlet') ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('inter-outlet.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                                        </svg>
                                    </a>
                                </li>

                                <li class="nav-item dropdown{{ request()->is('master/*') ? ' active' :'' }}">
                                    <a class="nav-link dropdown-toggle" href="#" id="masterDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Master
                                    </a>
                                    
                                    <div class="dropdown-menu" aria-labelledby="masterDropdownMenuLink">
                                        <a class="dropdown-item{{ request()->is('master/brand') ? ' active' : '' }}" href="{{ route('master.brand') }}">Brand</a>
                                        <a class="dropdown-item{{ request()->is('master/kategori') ? ' active' : '' }}" href="{{ route('master.category') }}">Kategori</a>
                                        <a class="dropdown-item{{ request()->is('master/pemasok') ? ' active' : '' }}" href="{{ route('master.supplier') }}">Pemasok</a>
                                        <a class="dropdown-item{{ request()->is('master/pelanggan') ? ' active' : '' }}" href="{{ route('master.customer') }}">Pelanggan</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item{{ request()->is('master/produk') ? ' active' : '' }}" href="{{ route('master.product') }}">Produk</a>                                    
                                    </div>
                                </li>

                                <li class="nav-item dropdown{{ request()->is('stok/*') ? ' active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#" id="masterDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Stok
                                    </a>
                                    
                                    <div class="dropdown-menu" aria-labelledby="masterDropdownMenuLink">
                                        <a class="dropdown-item{{ request()->is('stok/item') ? ' active' : '' }}" href="{{ route('stok.index') }}">Stok Barang</a>
                                        <a class="dropdown-item{{ request()->is('stok/balance') ? ' active' : '' }}" href="{{ route('stok.balance') }}">Stok Saldo</a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown{{ request()->is('daily/*') ? ' active' :'' }}">
                                    <a class="nav-link dropdown-toggle" href="#" id="harianDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Harian
                                    </a>
                                    
                                    <div class="dropdown-menu" aria-labelledby="harianDropdownMenuLink">
                                        <a class="dropdown-item{{ request()->is('daily/invoice') ? ' active' : '' }}" href="{{ route('daily.invoice') }}">Invoice</a>                                   
                                        <a class="dropdown-item{{ request()->is('daily/balance') ? ' active' : '' }}" href="{{ route('daily.balance') }}">Invoice Pulsa</a>                                   
                                        <hr>
                                        <a class="dropdown-item{{ request()->is('daily/hutang') ? ' active' : '' }}" href="{{ route('daily.debt') }}">Hutang</a>                                   
                                    </div>

                                </li>
                            @endrole

                            @role('SUPER ADMIN')
                                <li class="nav-item{{ request()->is('credit-partners') ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('credit-partners.index') }}">Mitra Kredit</a>
                                </li>
                            @endrole

                            @foreach (CreditPartner::getPartner() as $partner)
                                <li class="nav-item dropdown{{ request()->is('credit-partner/*') ? ' active' :'' }}">
                                    <a class="nav-link dropdown-toggle" href="#" id="harianDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $partner->alias ? $partner->alias : $partner->nama_partner }}
                                    </a>

                                    @php
                                        $linkCustomer = 'credit-partner/partner=' . $partner->id .'/customer';
                                        $linkProposal = 'credit-partner/partner=' . $partner->id .'/proposal';
                                        $linkInvoice = 'credit-partner/partner=' . $partner->id .'/invoice';
                                        $linkInvoiceClaim = 'credit-partner/partner=' . $partner->id .'/invoice-claim';
                                    @endphp
                                    
                                    <div class="dropdown-menu" aria-labelledby="harianDropdownMenuLink">
                                        <a class="dropdown-item{{ request()->is($linkCustomer) ? ' active' : '' }}" 
                                            href="{{ route('credit-partner.customer',['partner' => $partner->id]) }}"
                                        >
                                            Pelanggan Kredit
                                        </a>           

                                        <a class="dropdown-item{{ request()->is($linkProposal) ? ' active' : '' }}" 
                                            href="{{ route('credit-partner.proposal',['partner' => $partner->id]) }}"
                                        >
                                            Pengajuan Kredit
                                        </a>                                   

                                        @role('SUPER ADMIN|ADMIN|FRONT LINER|PROMOTOR')
                                            <a class="dropdown-item{{ request()->is($linkInvoice) ? ' active' : '' }}" 
                                                href="{{ route('credit-partner.invoice', ['partner' => $partner->id]) }}"
                                            >
                                                Pengambilan Barang
                                            </a>
                                        @endrole

                                        @role('SUPER ADMIN')
                                            <hr>
                                                <a class="dropdown-item{{ request()->is($linkInvoiceClaim) ? ' active' : '' }}" 
                                                    href="{{ route('credit-partner.invoice-claim', ['partner' => $partner->id]) }}"
                                                >
                                                    Pengajuan Nota
                                                </a>
                                            <hr>
                                        @endrole
                                    </div>
                                </li>
                            @endforeach
                                                        
                        </ul>
                        
                    @endauth
                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            @role('SUPER ADMIN|ADMIN')
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle{{ request()->is('account/*') ? ' active' :'' }}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1h7.956a.274.274 0 0 0 .014-.002l.008-.002c-.002-.264-.167-1.03-.76-1.72C13.688 10.629 12.718 10 11 10c-1.717 0-2.687.63-3.24 1.276-.593.69-.759 1.457-.76 1.72a1.05 1.05 0 0 0 .022.004zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10c-1.668.02-2.615.64-3.16 1.276C1.163 11.97 1 12.739 1 13h3c0-1.045.323-2.086.92-3zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                        </svg>
                                        Akun
                                    </a>
                                    
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item{{ request()->is('account/users') ? ' active' :'' }}" href="{{ route('user.index') }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                                            </svg>    
                                            Pengguna
                                        </a>
                                        @role('SUPER ADMIN')
                                            <a class="dropdown-item{{ request()->is('account/roles') ? ' active' :'' }}" href="{{ route('user.role') }}"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                                                </svg> 
                                                Role
                                            </a>
                                        @endrole                                    
                                    </div>
                                    
                                </li>
                            @endrole
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle{{ request()->is('change-password') ? ' active' :'' }}" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item{{ request()->is('change-password') ? ' active' :'' }}" href="{{ route('change-password') }}"
                                    >
                                        {{ __('Change Password') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        

        <main class="py-4 mt-5">
            @yield('content')
        </main>
    </div>

    <livewire:scripts />
    {{-- <script src="https://cdn.jsdelivr.net/gh/livewire/vue@v0.3.x/dist/livewire-vue.js"></script> --}}
</body>
</html>
