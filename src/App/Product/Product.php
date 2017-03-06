<?php

namespace App\Product;

/**
 *     
 * 
 * Classe: Product
 * 
 * @filesource Product.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 19:57:41
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                  
 */
class Product {

    private $id;
    private $name;
    private $price;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * Carrega um produto do banco de dados
     * @param array $row Uma linha do banco de dados
     * @return Product
     */
    public static function loadDb($row) {
        if (!is_array($row))
            return false;

        $product = new Product();
        $product->setId($row['id']);
        $product->setName($row['name']);
        $product->setPrice($row['price']);
        return $product;
    }

    /**
     * Carrega varios registros dos banco de dados
     * @param array $rows linha do banco de dados
     * @return Product
     */
    public static function loadDbAll($rows) {
        if (!is_array($rows))
            return false;

        foreach ($rows as $row) {
            $products[] = self::loadDb($row);
        }

        return $products;
    }

}
