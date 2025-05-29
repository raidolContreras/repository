<?php 
    
	$pagina = $_GET['pagina'] ?? 'vista';

	$navs = [
		'vista',
	];
    
    if(isset($_SESSION['logged']) && $_SESSION['logged'] == true){
        
        if (in_array($pagina, $navs)) {
            include "view/pages/navs/header.php";
            include "view/js.php";
        }

        if ($pagina == 'vista'){
    
            include "view/pages/$pagina.php";
    
        } elseif ($pagina == 'login'){
    
            header("Location: ./");
            exit();
    
        } else {
    
            include "error404.php";
    
        }
    } elseif($pagina == 'login') {
        include "view/pages/login/login.php";
    } else {
        header("Location: login");
        exit();
    }