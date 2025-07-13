<div class="menu-modal" id="menuModal">
    <div class="modal-overlay"></div>
    <div class="menu-content">
        <a href="{{ route('practicantes.create') }}" class="menu-item">
            <span class="item-text">Registrar practicante</span>
            <i class="fa-solid fa-user-plus menu-icon"></i>
        </a>
        <div class="menu-divider"></div>
        <a href="{{ route('practicantes.index') }}" class="menu-item">
            <span class="item-text">Lista de practicantes</span>
            <i class="fa-solid fa-clipboard-list menu-icon"></i>
        </a>
        <div class="menu-divider"></div>
        <a href="{{ route('admin.create') }}" class="menu-item">
            <span class="item-text">Registrar Administrador</span>
            <i class="fa-solid fa-gear menu-icon"></i>
        </a>
        <div class="menu-divider"></div>
        <a href="{{ route('administradores.lista') }}" class="menu-item">
            <span class="item-text">Lista de asesores</span>
            <i class="fa-solid fa-gear menu-icon"></i>
        </a>
        <div class="menu-divider"></div>
        <a href="{{ route('instituciones.create') }}" class="menu-item">
            <span class="item-text">Registrar institución</span>
            <i class="fa-solid fa-building menu-icon"></i>
        </a>
        <div class="menu-divider"></div>
        <a href="{{ route('instituciones.index') }}" class="menu-item">
            <span class="item-text">Lista de instituciones</span>
            <i class="fa-solid fa-list-ol"></i>
        </a>
        <div class="menu-divider"></div>
        <a href="{{ route('modificar_avisos.index') }}" class="menu-item">
            <span class="item-text">Modificar avisos</span>
            <i class="fa-solid fa-triangle-exclamation menu-icon"></i>
        </a>
        <div class="menu-divider"></div>
        <form id="logout-form" action="{{ url('/logout') }}"method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" class="menu-item"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="item-text">Cerrar Sesión</span>
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>
</div>
|