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
         @if(auth()->user()->role == "Admin")
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-file-earmark-excel"></i><span>Structurer Le Systeme</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('filieres') }}">
                            <i class="bi bi-circle"></i><span>Importer les Filières</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('niveaux') }}">
                            <i class="bi bi-circle"></i><span>Ajouter les Niveaux</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('classes.all') }}">
                            <i class="bi bi-circle"></i><span>Ajouter des Classes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ues') }}">
                            <i class="bi bi-circle"></i><span>Ajouter des UEs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('etudiants') }}">
                            <i class="bi bi-circle"></i><span>Ajouter des Etudiants</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

    @endif
    @if (auth()->check())
    <li class="nav-item">
        <a class="nav-link" data-bs-target="#components-nav2" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark-excel"></i><span>Gerer les Notes</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav2" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
            @if(auth()->user()->role == "Admin")
            <li>
                <a href="{{ route('calculmoyenne') }}">
                    <i class="bi bi-circle"></i><span>Calculer les Moyennes</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('notes') }}">
                    <i class="bi bi-circle"></i><span>Gerer les Notes</span>
                </a>
            </li>
        </ul>
    </li>
    @else

    @endif

</ul>
