<?php


$nivelUsuario = $_SESSION['Nivel'];


// Opciones de menú para nivel 1 
$menuNivel1 = [
    'Usuarios' => ['icon' => 'bi bi-people', 'url' => 'usuarios.php'],
    'Noticias' => ['icon' => 'bi bi-file-earmark-richtext', 'url' => 'noticias.php'],
    'Documentos' => ['icon' => 'bi bi-folder-check', 'url' => 'documentos.php']
];

// Opciones de menú para nivel 2
$menuNivel2 = [
    'Noticias' => ['icon' => 'bi bi-file-earmark-richtext', 'url' => 'noticias.php'],
    'Documentos' => ['icon' => 'bi bi-folder-check', 'url' => 'documentos.php']
];

$menu = ($nivelUsuario == '1') ? $menuNivel1 : $menuNivel2;
?>

<div class="page-wrapper">
    <!-- Sidebar wrapper start -->
    <nav class="sidebar-wrapper">
        <div class="brand justify-content-center">
            <a href="index.php" class="logo">
                <img src="assets/images/prueba2.png" class="d-none d-md-block me-4" width="120" height="200" />
                <img src="assets/images/prueba2.png" class="d-block d-md-none me-4" />
            </a>
        </div>

        <div class="sidebar-menu">
            <div class="sidebarMenuScroll">
                <ul>
                    <?php
                    foreach ($menu as $opcion => $data) {
                        $icono = $data['icon'];
                        $url = $data['url'];
                        echo '<li><a href="' . $url . '"><i class="' . $icono . '"></i><span class="menu-text">' . $opcion . '</span></a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</div>