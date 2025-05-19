<?php
$c = $_GET['c'] ?? 'auth';
$a = $_GET['a'] ?? 'login';

$controllerName = ucfirst($c) . 'Controller';
$method = $a;

if ($c === 'cliente') {
    $controllerFile = "controllers/{$controllerName}.php";
    if (!file_exists($controllerFile)) {
        die("Archivo del controlador '$controllerFile' no encontrado.");
    }
    require_once $controllerFile;

    if (!class_exists($controllerName)) {
        die("Controlador '$controllerName' no encontrado.");
    }

    $clienteController = new $controllerName();

    switch ($a) {
        case 'index':
            $clienteController->index();
            break;
        case 'registrar':
            $clienteController->registrar();
            break;
        case 'editar':
            $clienteController->editar();
            break;
        case 'eliminar':
            $clienteController->eliminar();
            break;
        case 'obtenerCliente':
            $clienteController->obtenerCliente();
            break;
        default:
            $clienteController->index();
            break;
    }

} else {
    $controllerFile = "controllers/{$controllerName}.php";

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                die("Método '$method' no encontrado en el controlador '$controllerName'");
            }
        } else {
            die("Controlador '$controllerName' no encontrado.");
        }
    } else {
        die("Archivo del controlador '$controllerFile' no encontrado.");
    }
}

