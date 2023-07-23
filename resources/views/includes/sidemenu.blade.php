<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
        <a class="nav-link " href="{{route('home')}}">
            <i class="bi bi-house"></i>
            <span>
                @if(auth()->check())
                    Accueil
                @else
                    Login
                @endif
            </span>
        </a>
    </li><!-- End Dashboard Nav -->

    @if (auth()->check())
    <li class="nav-item">
        <a class="nav-link" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark-excel"></i><span>Importation de fichiers</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
            @if(auth()->user()->role == "Admin")
            <li>
                <a href="{{ route('filieres') }}">
                    <i class="bi bi-circle"></i><span>Filières</span>
                </a>
            </li>
            <li>
                <a href="{{ route('niveaux') }}">
                    <i class="bi bi-circle"></i><span>Niveaux</span>
                </a>
            </li>
            <li>
                <a href="{{ route('classes.all') }}">
                    <i class="bi bi-circle"></i><span>Classes</span>
                </a>
            </li>
            <li>
                <a href="{{ route('ues') }}">
                    <i class="bi bi-circle"></i><span>UEs</span>
                </a>
            </li>
            <li>
                <a href="{{ route('etudiants') }}">
                    <i class="bi bi-circle"></i><span>Etudiants</span>
                </a>
            </li>
            <li>
                <a href="{{ route('calculmoyenne') }}">
                    <i class="bi bi-circle"></i><span>Moyennes</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('notes') }}">
                    <i class="bi bi-circle"></i><span>Notes</span>
                </a>
            </li>
        </ul>
    </li>
    @else

    @endif

</ul>
