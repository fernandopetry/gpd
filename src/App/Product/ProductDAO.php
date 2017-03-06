<?php

namespace App\Product;

/**
 *     
 * 
 * Classe: ProductDAO
 * 
 * @filesource ProductDAO.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 20:17:29
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                 
 */
class ProductDAO {

    /** @var \Silex\Application */
    private $app;

    public function __construct(\Silex\Application $app) {
        $this->app = $app;
    }

    /**
     * Seleciona um produto
     * @param integer $id Código do produto
     * @return Product
     */
    public function getProduct($id) {
        $select = $this->app['db']->fetchAssoc('SELECT * FROM products WHERE id = :id', [':id' => $id]);

        if (!is_array($select))
            return false;

        return Product::loadDb($select);
    }

    /**
     * Lista de produtos
     * @return Product
     */
    public function getProducts() {
        $products = $this->app['db']->fetchAll('SELECT * FROM products');
        return Product::loadDbAll($products);
    }

}
