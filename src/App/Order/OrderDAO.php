<?php

namespace App\Order;

/**
 *     
 * 
 * Classe: OrderDAO
 * 
 * @filesource OrderDAO.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 21:06:27
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                  
 */
class OrderDAO {

    private $data;

    /** @var \Doctrine\DBAL\Connection */
    private $db;

    /** @var \Silex\Application */
    private $app;

    /** @var \Symfony\Component\HttpFoundation\Request */
    private $request;

    public function __construct(\Silex\Application $app, \Symfony\Component\HttpFoundation\Request $request) {
        $this->app     = $app;
        $this->request = $request;
        $this->data    = $this->request->query->all();
        $this->db      = $this->app['db'];
    }

    /**
     * Consulta um pedido
     * @param integer $id Codigo do pedido
     * @return Order
     */
    public function getOrder($id) {
        $select = $this->app['db']->fetchAssoc('SELECT * FROM orders WHERE id = :id', [':id' => $id]);

        if (!is_array($select))
            return false;

        Order::setApp($this->app);
        return Order::loadDb($select);
    }

    /**
     * Lista de pedidos
     * @return Order
     */
    public function getOrders() {
        $select = $this->app['db']->fetchAll('SELECT * FROM orders');

        if (!is_array($select))
            return false;

        Order::setApp($this->app);
        return Order::loadDbAll($select);
    }

    private function getOrderByFilterPreparePeriod() {
        $period = $this->data['period'];
        $today  = date('Y-m-d');

        $dateNow   = new \DateTime();
        $dateNow->sub(new \DateInterval('P1W'));
        $this_week = $dateNow->format('Y-m-d');

        switch ($period) {
            case 'today':
                $filterDate = "date = '{$today}'";
                break;
            case 'this_week':
                $filterDate = "date(date) between date('{$this_week}') and date('{$today}')";
                break;
            case 'all_time':
                $filterDate = false;
                break;
            default:
                $filterDate = false;
                break;
        }

        return $filterDate;
    }

    private function getOrderByFilterPrepareSearch() {
        $search = $this->data['search'];

        if (empty($search))
            return false;

        return "users.name LIKE :search OR products.name LIKE :search";
    }

    public function getOrderByFilter() {

        $period    = $this->getOrderByFilterPreparePeriod();
        $search    = $this->getOrderByFilterPrepareSearch();
        $searchTxt = $this->data['search'];

        $where = ($period) ? "WHERE ($period)" : false;
        if ($search)
            $where = ($where) ? "{$where} AND ({$search}) " : "WHERE ($search)";

        $prepare = "SELECT orders.*,products.name,users.name FROM orders INNER JOIN products ON orders.product_id = products.id INNER JOIN users ON orders.user_id = users.id " . $where;

        $select = $this->db->prepare($prepare);
        if ($search) {
            $select->bindValue(':search', '%' . $searchTxt . '%');
        }
        $select->execute();

        $result = $select->fetchAll();

        Order::setApp($this->app);
        return Order::loadDbAll($result);
    }

}
