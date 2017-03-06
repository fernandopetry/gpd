<?php

namespace App\User;

/**
 *     
 * 
 * Classe: UserDAO
 * 
 * @filesource UserDAO.php
 * @package GPD
 * @subpackage 
 * @category
 * @version v1.0
 * @since 05/03/2017 20:25:27
 * @copyright (cc) 2017, Fernando Petry
 * 
 * @author Fernando Petry <fernando@ideia.ppg.br>                                                  
 */
class UserDAO {

    /** @var \Silex\Application */
    private $app;

    public function __construct(\Silex\Application $app) {
        $this->app = $app;
    }

    /**
     * Seleciona um usuário
     * @param integer $id Código do usuário
     * @return User
     */
    public function getUser($id) {
        $select = $this->app['db']->fetchAssoc('SELECT * FROM users WHERE id = :id', [':id' => $id]);

        if (!is_array($select))
            return false;

        return User::loadDb($select);
    }

    /**
     * Lista de usuários
     * @return User
     */
    public function getUsers() {
        $users = $this->app['db']->fetchAll('SELECT * FROM users');
        return User::loadDbAll($users);
    }

}
