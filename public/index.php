<?php
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../controllers/CompteController.php';

$controller = new CompteController();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'view':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $controller->view($id);
        break;
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $controller->edit($id);
        break;
    case 'delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $controller->delete($id);
        break;
    default:
        $controller->index();
        break;
}
?>