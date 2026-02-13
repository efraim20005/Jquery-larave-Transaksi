<!DOCTYPE html>
<html>
<head>
    <title>Website Penjualan</title>

    <!-- CSRF TOKEN WAJIB -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Penjualan App</a>

        @if(auth()->user()->role == 'admin')

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{route('products.index')}}">Produk</a>


                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('categories.index')}}">Kategori</a>
                </li>
            </ul>
        </div>

        @endif

        @if(auth()->user()->role == 'kasir')

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{route('transactions.index')}}">Transaksi</a>
                    </li>
                </ul>
            </div>
        @endif

        <div class="ms-auto">
            @auth
                <span class="text-white me-3">
                    {{ auth()->user()->name }} ({{ auth()->user()->role }})
                </span>

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="btn btn-danger btn-sm">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endauth
        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- GLOBAL CSRF AJAX -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@yield('scripts')

</body>
</html>
