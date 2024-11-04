<?php

namespace system\Control;

use system\Suporte\Template;

/**
 * Controlador geral de views e mensagens
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Controlador
{
    protected Template $template;
            
    /**
     * Construtor do controlador
     * 
     * @param string $diretorio
     */
    public function __construct(string $diretorio)
    {
        $this->template = new Template($diretorio);   
    }
}
