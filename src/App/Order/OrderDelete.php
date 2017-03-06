<?php

namespace App\Order;

/**
 *     
 * 
 * Classe: OrderDelete
 * 
 * @filesource OrderDelete.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 06/03/2017 10:56:22
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                  
 */
class OrderDelete {

    private $response = ['message' => 'Nada processado', 'type' => 'warning'];

    /** @var \Silex\Application */
    private $app;

    /** @var \Doctrine\DBAL\Connection */
    private $db;

    public function __construct(\Silex\Application $app) {
        $this->app = $app;
        $this->db  = $this->app['db'];
    }

    public function getResponse() {
        return $this->response;
    }

    public function execute($id) {
        $delete = $this->db->delete('orders', ['id' => $id]);

        if (!$delete)
            throw new \Exception('Opss, falha excluir os dados');

        $this->response = ['message' => 'Pedido excluido com sucesso', 'type' => 'success'];
    }

}
