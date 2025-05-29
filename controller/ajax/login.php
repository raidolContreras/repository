<?php
require_once "../forms.controller.php";
require_once "../../model/forms.models.php";

session_start();
if (isset($_POST['email']) && isset($_POST['password'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	$cryptPassword = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

	$users = FormsController::ctrSearchUsers($email);
	
	if ($users != false){
		if ($email == $users['email'] && $cryptPassword == $users['password']) {
			if($users['status'] == 1){
				$_SESSION['logged'] = true;
				$_SESSION['email'] = $users['email'];
				$_SESSION['idUser'] = $users['idUser'];
				$_SESSION['name'] = $users['firstname'] . " " . $users['lastname'];
				$_SESSION['level'] = $users['level'];
				$_SESSION['levelName'] = ($users['level'] == 1) ? 'Colaborador' : 'Administrador';
				echo 'ok';
			} else {
				echo 'status';
			}
		} else {
			echo 'error';
		}
	}
} else {
	echo 'error';
}