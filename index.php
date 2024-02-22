<?php

use Infrastructure\Web\Controller\AuthController;
use Infrastructure\Web\Controller\DashboardController;
use Infrastructure\Web\Controller\SignatureController;

session_start();

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace("/projeto", "", $path);

if ($path === '/') {
    header("Location: " . BASE_URL . "/login");
    exit();
}

if ($path == '/login') {
    if (isset($_SESSION['logged_in'])) {
        if ($_SESSION['logged_in'] === true) {
            header("Location: " . BASE_URL . "/dashboard");
            exit();
        }
    }
    $controller = new AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->showLogin();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->login();
    }
} elseif ($path == '/logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $controller->logout();
} elseif (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: " . BASE_URL . "/login");
    exit();
} else {


    // Rotas protegidas
    if ($path == '/dashboard' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller = new DashboardController();
        $controller->showDashboard();
    } elseif ($path == '/assinatura/documento' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller = new SignatureController();
        $controller->showUpload();
    } elseif ($path == '/assinatura/documento' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new SignatureController();
        $controller->upload();
    } elseif (preg_match('/^\/assinatura\/signatarios\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller = new SignatureController();
        $controller->showSigners();
    } elseif ($path == '/assinatura/signatarios' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $controller = new SignatureController();
        $controller->saveSigners($data);
    } elseif ($path == '/assinatura/documentos' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller = new SignatureController();
        $controller->showDocuments();
    } elseif (preg_match('/^\/assinatura\/signatario\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $signatureId = $matches[1];
        $controller = new SignatureController();
        $controller->showSignature($signatureId);
    } elseif (preg_match('/^\/assinatura\/download\/([A-Fa-f0-9]+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $chave = $matches[1];
        $controller = new SignatureController();
        $controller->download($chave);
    } elseif (preg_match('/^\/assinatura\/assinaturas\/([A-Fa-f0-9]+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $chave = $matches[1];
        $controller = new SignatureController();
        $controller->getSignatures($chave);
    } elseif (preg_match('/^\/assinatura\/adicionar_signatario\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $signatureId = $matches[1];
        $controller = new SignatureController();
        $controller->showAddSignature($signatureId);
    } elseif (preg_match('/^\/assinatura\/adicionar_signatario\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $signatureId = $matches[1];
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $controller = new SignatureController();
        $controller->addSigners($signatureId, $data);
    } elseif (preg_match('/^\/assinatura\/enviar_email\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $signatureId = $matches[1];
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $controller = new SignatureController();
        $controller->sendEmail($signatureId, $data);
    } elseif (preg_match('/^\/assinatura\/deletar_assinatura\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $signatureId = $matches[1];
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $controller = new SignatureController();
        $controller->deleteSigner($signatureId, $data);
    } elseif (preg_match('/^\/assinatura\/documento\/(\d+)$/', $path, $matches) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $signatureId = $matches[1];
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $controller = new SignatureController();
        $controller->deleteDocument($signatureId);
    } else {
        echo "Página não encontrada.";
    }

}


