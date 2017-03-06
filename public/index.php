<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');
require_once __DIR__ . '/../vendor/autoload.php';

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

$dConfig = [
    'driver'        => 'pdo_mysql',
    'host'          => 'localhost',
    'dbname'        => 'gpd',
    'user'          => 'root',
    'password'      => '123',
    'driverOptions' => array(
        1002 => 'SET NAMES utf8'
    )
];

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app          = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../templates',
));
$app->register(new \Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'var/logs/silex_dev.log'
));
$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
    'db.default_options' => $dConfig
));


$app['config'] = [
    'name' => 'Gestão Pública Digital'
];

/**
 * Página principal do sistema
 */
$app->get('/', function(Silex\Application $app, Request $request) {
    $modalConfig = new App\Order\ModalConfig($app, $request);
    $ordersDAO   = new \App\Order\OrderDAO($app, $request);
    $orders      = $ordersDAO->getOrders();
    return $app['twig']->render('index.twig', ['modal' => $modalConfig, 'orders' => $orders]);
});

/**
 * Carrega o modal dos pedidos
 */
$app->get('/api/order/modal/{id}', function(Silex\Application $app, Request $request, $id) {
    $modalConfig = new App\Order\ModalConfig($app, $request);
    $modalConfig->setModalUpdate($id);
    return $app['twig']->render('order_modal.twig', ['modal' => $modalConfig]);
});

/**
 * Efetua o cadastro de um pedido
 */
$app->post('/api/order', function(Silex\Application $app, Request $request) {
    try {
        $insert   = new \App\Order\OrderInsert($app, $request);
        $insert->execute();
        $response = $insert->getResponse();
    } catch (\Exception $e) {
        $response = ['message' => $e->getMessage(), 'type' => 'error'];
    }
    return $app->json($response);
});

/**
 * Atualiza um pedido
 */
$app->put('/api/order/{id}', function(Silex\Application $app, Request $request, $id) {
    try {
        $update   = new \App\Order\OrderUpdate($app, $request);
        $update->execute($id);
        $response = $update->getResponse();
    } catch (\Exception $e) {
        $response = ['message' => $e->getMessage(), 'type' => 'error'];
    }

    return $app->json($response);
});

/**
 * Deleta um pedido
 */
$app->delete('/api/order/{id}', function($id) use($app) {
    try {
        $delete   = new \App\Order\OrderDelete($app);
        $delete->execute($id);
        $response = $delete->getResponse();
    } catch (\Exception $e) {
        $response = ['message' => $e->getMessage(), 'type' => 'error'];
    }

    return $app->json($response);
});

/**
 * Carrega listagem da tabela de pedidos, utilizado quando efetua um filtro
 */
$app->post('/api/orders/loadtb', function(Silex\Application $app, Request $request) {
    $data = $request->request->all();

    if (isset($data['result'])) {
        $result = unserialize($data['result']);

        return $app['twig']->render('orders_line.twig', ['orders' => $result]);
    } else {
        return 'Nenhum registro encontrado.';
    }
});

/**
 * Consulta pedidos por nome de usuario ou produto, filtra tudo, por dia ou semana
 */
$app->get('/api/order/{id}', function(Silex\Application $app, Request $request) {
    try {
        $search = new App\Order\OrderDAO($app, $request);
        $result = $search->getOrderByFilter();
        if (!$result)
            throw new \Exception('Nenhum resultado encontrado.');

        $result = serialize($result);

        $response = ['message' => 'OK', 'type' => 'success', 'result' => $result];
    } catch (\Exception $e) {
        $response = ['message' => $e->getMessage(), 'type' => 'error'];
    }

    return $app->json($response);
})->value('id', NULL);

/**
 * Página de Erro
 */
$app->error(function (\Exception $e, Request $request, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});

$app->run();
