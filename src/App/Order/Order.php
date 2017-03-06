<?php

namespace App\Order;

/**
 *     
 * 
 * Classe: Order
 * 
 * @filesource Order.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 20:02:49
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                 
 */
class Order {

    private $id;

    /** @var \App\Product\Product */
    private $product;

    /** @var \App\User\User */
    private $user;
    private $date;
    private $quantity;

    /** @var \Silex\Application */
    private static $app;

    public static function setApp(\Silex\Application $app) {
        self::$app = $app;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Dados do produto
     * @return \App\Product\Product
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Dados do usuario
     * @return \App\User\User
     */
    public function getUser() {
        return $this->user;
    }

    public function getDate() {
        return $this->date;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getTotalPrice() {
        return ($this->quantity * $this->product->getPrice());
    }

    public function getTotalPriceBR() {
        return number_format($this->getTotalPrice(), 2, ',', '.');
    }

    public function getTotalPriceUSA() {
        return number_format($this->getTotalPrice(), 2, '.', '');
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setProduct(\App\Product\Product $product) {
        $this->product = $product;
        return $this;
    }

    public function setUser(\App\User\User $user) {
        $this->user = $user;
        return $this;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Carrega um pedido
     * @param array $row Linha do banco de dados para um pedido
     * @return Order
     * @throws \Exception
     */
    public static function loadDb($row) {
        if (!is_array($row))
            return false;

        $productDAO = new \App\Product\ProductDAO(self::$app);
        $productGet = $productDAO->getProduct($row['product_id']);
        if (!$productGet)
            throw new \Exception('Produto para o pedido não localizado.');

        $userDAO = new \App\User\UserDAO(self::$app);
        $userGet = $userDAO->getUser($row['user_id']);
        if (!$userGet)
            throw new \Exception('Usuário para o pedido não localizado.');

        $user = new Order();
        $user->setId($row['id']);
        $user->setProduct($productGet);
        $user->setUser($userGet);
        $user->setDate($row['date']);
        $user->setQuantity($row['quantity']);
        return $user;
    }

    public static function loadDbAll($rows) {

        if (!is_array($rows) || empty($rows))
            return false;

        foreach ($rows as $row) {
            $orders[] = self::loadDb($row);
        }

        return $orders;
    }

}
