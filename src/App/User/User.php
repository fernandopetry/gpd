<?php

namespace App\User;

/**
 *     
 * 
 * Classe: User
 * 
 * @filesource User.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 19:51:58
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                  
 */
class User {

    private $id;
    private $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Carrega uma linha do banco de dados
     * @param array $row Resultado do banco de dados
     * @return User
     */
    public static function loadDb($row) {
        if (!is_array($row))
            return false;

        $user = new User();
        $user->setId($row['id']);
        $user->setName($row['name']);
        return $user;
    }

    /**
     * Carrega todos os resultados do banco de dados
     * @param array $rows Lista de resultados do banco de dados
     * @return User
     */
    public static function loadDbAll($rows) {
        if (!is_array($rows))
            return false;

        foreach ($rows as $row) {
            $users[] = self::loadDb($row);
        }

        return $users;
    }

}
