<div class="menu-modal" id="menuModal">
    <div class="modal-overlay"></div>
    <div class="menu-content">
        <a href="{{ route('asesor.practicantes.index') }}" class="menu-item">
            <span class="item-text">Lista de practicantes</span>
            <i class="fa-solid fa-clipboard-list menu-icon"></i>
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