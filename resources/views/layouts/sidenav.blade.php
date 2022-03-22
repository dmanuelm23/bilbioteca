<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <a class="nav-link" href="/home">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @role('Administrativo')
            <div class="sb-sidenav-menu-heading">Catálogos</div>
            <a class="nav-link"  href="{{ url('/books') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                Libros
            </a>
            @endrole
            @role('Administrativo|Prestatario')
            <div class="sb-sidenav-menu-heading">Interface</div>
            <a class="nav-link" href="{{ url('/loan-books') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-book-reader"></i></div>
                Prestamos
            </a>
            <a class="nav-link" href="{{ url('/return-books') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Devoluciones
            </a>
            @endrole
        </div>
    </div>
</nav>