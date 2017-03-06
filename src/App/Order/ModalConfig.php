<?php

namespace App\Order;

/**
 *     
 * 
 * Classe: ModalConfig
 * 
 * @filesource ModalConfig.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 19:47:59
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                 
 */
class ModalConfig {

    private $ID     = 'mdNew';
    private $label  = 'Novo Pedido';
    private $action = '/api/order';
    private $method = 'POST';

    /** @var Order */
    private $order;

    /** @var \App\User\User */
    private $userList;

    /** @var \App\Product\Product */
    private $productList;

    /** @var \App\User\User */
    private $user;

    /** @var \App\Product\Product */
    private $product;

    /** @var \Silex\Application */
    private $app;

    /** @var \Symfony\Component\HttpFoundation\Request */
    private $request;

    public function __construct(\Silex\Application $app, \Symfony\Component\HttpFoundation\Request $request) {
        $this->app     = $app;
        $this->request = $request;

        $userDAO        = new \App\User\UserDAO($app);
        $this->userList = $userDAO->getUsers();

        $productDAO        = new \App\Product\ProductDAO($app);
        $this->productList = $productDAO->getProducts();

        $this->user    = new \App\User\User();
        $this->product = new \App\Product\Product();

        $this->order = new Order();
        $this->order->setUser($this->user);
        $this->order->setProduct($this->product);
    }

    public function setModalInsert() {
        $this->user    = new App\User\User();
        $this->product = new \App\Product\Product();
    }

    public function setModalUpdate($id) {

        $orderDAO = new OrderDAO($this->app, $this->request);
        $order    = $orderDAO->getOrder($id);

        $this->ID      = 'mdUpdate';
        $this->label   = 'Editar Pedido';
        $this->action  = '/api/order/' . $order->getId();
        $this->method  = 'PUT';
        $this->order   = $order;
        $this->user    = $order->getUser();
        $this->product = $order->getProduct();
    }

    public function getID() {
        return $this->ID;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getAction() {
        return $this->action;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getUserList() {
        return $this->userList;
    }

    public function getProductList() {
        return $this->productList;
    }

    public function getUser() {
        return $this->user;
    }

    public function getProduct() {
        return $this->product;
    }

}

//$modal = [
//    'modalID'    => 'mdNew',
//    'modalLabel' => 'Novo Pedido',
//    'action'     => '/api/order',
//    'method'     => 'POST',
//    'qtd'        => '',
//    'user'       => $user,
//    'userID'     => 0,
//    'product'    => $product,
//    'productID'  => 0
//];
//$modal = [
//    'modalID'    => 'mdUpdate',
//    'modalLabel' => 'Editar Pedido',
//    'action'     => '/api/order/' . $id,
//    'method'     => 'PUT',
//    'qtd'        => 20,
//    'user'       => $user,
//    'userID'     => 1,
//    'product'    => $product,
//    'productID'  => 1
//];
