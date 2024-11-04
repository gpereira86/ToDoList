<?php

namespace system\model;

use system\Control\Modelo;

/**
 * Classe PostModelo: Define tabela do banco para posts
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class TarefasModelo extends Modelo
{

    /**
     * Envia ao construtor (super classe) a tabela de consulta de banco para posts
     */
    public function __construct()
    {
        parent::__construct('tasks');
    }

}
