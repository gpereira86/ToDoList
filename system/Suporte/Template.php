<?php

namespace system\Suporte;

use Twig\Lexer;
use system\Control\Helpers;


/**
 * Class do Twig Template: Construtor (exigida pela biblioteca) | Funções personalizadas
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Template
{

    private \Twig\Environment $twig;

    /**
     * construtor padrão Twig Template
     * 
     * @param string $diretorio
     */
    public function __construct(string $diretorio)
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twig = new \Twig\Environment($loader);

        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);
    }

    /**
     * Renderizar o view
     * 
     * @param string $view
     * @param array $dados
     * @return string
     */
    public function renderizar(string $view, array $dados): string
    {
        return $this->twig->render($view, $dados);
    }

    /**
     * Funções personalizadas do Twig Template
     * 
     * @return void
     */
    private function helpers(): void
    {
        array(
            $this->twig->addFunction(
                    /**
                     * Consultar documentação em Helpers
                     */
                    new \Twig\TwigFunction('url', function (string $url = null) {
                                return Helpers::url($url);
                            }),
                    
                   
                    /**
                     * Consultar documentação em Helpers
                     */
                    $this->twig->addFunction(
                            new \Twig\TwigFunction('dataBr', function (string $data) {
                                        return Helpers::dataBr($data);
                                    })
                    ),
            )
        );
    }
}
