<?php
require_once 'models/Cliente.php';

class ClienteController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php');
            exit();
        }

        // Obtener todos los clientes
        $clientes = $this->clienteModel->obtenerTodos();
        
        include 'views/cliente/cliente.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cedula' => trim($_POST['cedula']),
                'nombre' => trim($_POST['nombre']),
                'apellido' => trim($_POST['apellido']),
                'email' => trim($_POST['email']),
                'telefono' => trim($_POST['telefono']),
                'direccion' => trim($_POST['direccion']),
                'estado' => 'activo'
            ];

            $resultado = $this->clienteModel->registrar($data);

            switch ($resultado) {
                case 'registro_exitoso':
                    $_SESSION['mensaje'] = 'Cliente registrado exitosamente';
                    $_SESSION['tipo_mensaje'] = 'success';
                    break;
                case 'cedula_existente':
                    $_SESSION['mensaje'] = 'La cédula ya está registrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    break;
                case 'email_existente':
                    $_SESSION['mensaje'] = 'El email ya está registrado';
                    $_SESSION['tipo_mensaje'] = 'error';
                    break;
                default:
                    $_SESSION['mensaje'] = 'Error al registrar el cliente';
                    $_SESSION['tipo_mensaje'] = 'error';
            }

            header('Location: index.php?c=cliente');
            exit();
        }
    }public function editar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();

        $required = ['id_cliente', 'cedula', 'nombre', 'apellido', 'email', 'telefono', 'direccion', 'estado'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['mensaje'] = "Error: El campo $field es requerido";
                $_SESSION['tipo_mensaje'] = 'error';
                header('Location: index.php?c=cliente');
                exit();
            }
        }

        $data = [
            'id_cliente' => (int)$_POST['id_cliente'], // <- nombre correcto
            'cedula' => trim($_POST['cedula']),
            'nombre' => trim($_POST['nombre']),
            'apellido' => trim($_POST['apellido']),
            'email' => trim($_POST['email']),
            'telefono' => trim($_POST['telefono']),
            'direccion' => trim($_POST['direccion']),
            'estado' => trim($_POST['estado'])
        ];

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje'] = 'Error: Formato de email no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: index.php?c=cliente');
            exit();
        }

        $resultado = $this->clienteModel->actualizar($data);

        if ($resultado === true) {
            $_SESSION['mensaje'] = 'Cliente actualizado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el cliente';
            $_SESSION['tipo_mensaje'] = 'error';
        }

        header('Location: index.php?c=cliente');
        exit();
    } else {
        header('Location: index.php?c=cliente');
        exit();
    }
}


public function eliminar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];

        $resultado = $this->clienteModel->eliminar($id);

        if ($resultado) {
            $_SESSION['mensaje'] = 'Cliente eliminado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar el cliente';
            $_SESSION['tipo_mensaje'] = 'error';
        }

        header('Location: index.php?c=cliente');
        exit();
    }
}
    public function obtenerCliente() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $cliente = $this->clienteModel->obtenerPorId($id);
            
            header('Content-Type: application/json');
            echo json_encode($cliente);
            exit();
        }
    }
}