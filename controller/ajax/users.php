<?php
require_once "../forms.controller.php";
require_once "../../model/forms.models.php";
// controller/ajax/users.php
header('Content-Type: application/json; charset=UTF-8');

$response = [
    'success' => false,
    'error'   => null
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['error'] = 'Método no permitido';
    echo json_encode($response);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {

    case 'create_user':
        // Recoger y sanear datos
        $name     = trim($_POST['name']     ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $role     = $_POST['role']          ?? '';

        // Validaciones básicas
        if (!$name || !$lastname || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$password) {
            $response['error'] = 'Datos incompletos o inválidos.';
            break;
        }

        // Hashear contraseña
        $hashedPassword = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        // Preparar arreglo para el controlador
        $data = [
            'firstname'     => $name,
            'lastname' => $lastname,
            'email'    => $email,
            'password' => $hashedPassword,
            'level'     => $role
        ];

        // Llamada al controlador
        try {
            $result = formsController::AddUsers($data);
            if ($result === 'ok') {
                $response['success'] = true;
            } else {
                // Si AddUsers devuelve mensaje de error
                $response['error'] = $result;
            }
        } catch (Exception $e) {
            $response['error'] = 'Excepción: ' . $e->getMessage();
        }
        break;

    // Aquí puedes añadir más actions...
    // case 'update_user': ...

    default:
        $response['error'] = 'Acción no válida.';
        break;
}

echo json_encode($response);
