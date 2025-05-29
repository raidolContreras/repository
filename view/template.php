<?php
header('Content-Type: text/html; charset=utf-8');
?>

<?php

// Comenzar la sesión
session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Explorador de Archivos</title>
    <!-- Cargar Bootstrap 5 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="view/assets/js/script.js"></script>

    <title>Repositorio - UNIMO</title>
    <?php include "css.php"; ?>
</head>
<style>
    .floating-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .floating-button button {
        display: flex;
    }

    .bookmarkBtn {
        width: 100px;
        height: 40px;
        border-radius: 40px;
        border: 1px solid rgba(255, 255, 255, 0.349);
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition-duration: 0.3s;
        overflow: hidden;
        padding: 0;
    }

    .IconContainer {
        width: 30px;
        height: 30px;
        background: linear-gradient(to bottom, rgb(255, 136, 255), rgb(172, 70, 255));
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        z-index: 2;
        transition-duration: 0.3s;
    }
    .bookmarkBtn.move .IconContainer {
        background: linear-gradient(to bottom, rgb(255, 136, 255), rgb(172, 70, 255));
    }
    .bookmarkBtn.delete .IconContainer {
        background: linear-gradient(to bottom, rgb(255, 136, 136), rgb(255, 70, 70));
    }

    .icon {
        color: white;
        font-size: 16px;
    }

    .text-btn {
        height: 100%;
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        transition-duration: 0.3s;
        font-size: 1.04em;
    }

    .bookmarkBtn:hover .IconContainer {
        width: 90px;
        transition-duration: 0.3s;
    }

    .bookmarkBtn:hover .text-btn {
        transform: translate(10px);
        width: 0;
        font-size: 0;
        transition-duration: 0.3s;
    }

    .bookmarkBtn:active {
        transform: scale(0.95);
        transition-duration: 0.3s;
    }
</style>

<div class="floating-button">
    <button id="moveSelectedFilesButton" class="bookmarkBtn move" style="display: none;">
        <span class="IconContainer">
            <i class="fas fa-arrow-alt-circle-right icon"></i>
        </span>
        <span class="text-btn">Mover</span>
    </button>

    <button id="deleteSelectedFilesButton" class="bookmarkBtn delete" style="display: none;">
        <span class="IconContainer">
            <i class="fas fa-trash-alt icon"></i>
        </span>
        <span class="text-btn">Eliminar</span>
    </button>

</div>

<body>

    <link href="view/assets/css/style2.css" rel="stylesheet">
    <?php include "config/whiteList.php"; ?>

    <!-- Cargar Bootstrap 5 JS -->
    <script src="https://kit.fontawesome.com/f4781c35cc.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.bundle.min.js"></script>
</body>

</html>