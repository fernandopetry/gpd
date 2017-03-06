<?php

namespace App\Order;

/**
 *     
 * 
 * Classe: OrderInsert
 * 
 * @filesource OrderInsert.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 21:56:29
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                  
 */
class OrderInsert {

    private $response = ['message' => 'Nada processado', 'type' => 'warning'];
    private $data;

    /** @var \Silex\Application */
    private $app;

    /** @var \Symfony\Component\HttpFoundation\Request */
    private $request;

    /** @var \Doctrine\DBAL\Connection */
    private $db;

    public function __construct(\Silex\Application $app, \Symfony\Component\HttpFoundation\Request $request) {
        $this->app     = $app;
        $this->request = $request;
        $this->data    = $this->request->request->all();
        $this->db      = $this->app['db'];

        $this->validateData();
    }

    private function validateData() {
        if ($this->data['user_id'] == 0) {
            throw new \Exception('O usuário deve ser selecionado.');
        }
        if ($this->data['product_id'] == 0) {
            throw new \Exception('O produto deve ser selecionado.');
        }
        if (empty($this->data['quantity']) || $this->data['quantity'] < 1) {
            throw new \Exception('A quantidade deve ser informada e deve ser maior que 0.');
        }
        $this->data['date'] = date('Y-m-d');
    }

    public function getResponse() {
        return $this->response;
    }

    public function execute() {
        $insert = $this->db->insert('orders', $this->data);

        if (!$insert)
            throw new \Exception('Opss, falha gravar os dados');

        $this->response = ['message' => 'Pedido cadastrado com sucesso', 'type' => 'success'];
    }

}
