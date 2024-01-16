<?php
session_start();

// Verificar si la sesión no está activa
if (!isset($_SESSION['Usuario'])) {
    // Redirigir al usuario al login
    header("Location: login.php");
    exit();
}
