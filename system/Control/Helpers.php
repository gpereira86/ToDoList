<?php

namespace system\Control;

/**
 * A classe de funções gerais de tratamento e preparação de dados
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class Helpers
{

    /**
     * Redirecionamento com url amigável
     * 
     * @param string $url
     * @return void
     */
    public static function redirecionar(string $url = null): void
    {
        header('HTTP/1.1 302 found');

        $local = ($url ? self::url($url) : self::url());

        header("location: {$local}");
        exit();
    }

    /**
     * Monta URL de acordo com Ambiente desenvolvimento ou produção
     * 
     * @param string $url
     * @return string
     */
    public static function url(string $url = null): string
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $ambiente = ($servidor == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);

        if (str_starts_with($url, '/')) {
            return$ambiente . $url;
        }

        return$ambiente . '/' . $url;
    }

    /**
     * Retorna Verdadeiro ou falso para ambiente localhost
     * 
     * @return bool
     */
    public static function localhost(): bool
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

        if ($servidor == 'localhost') {
            return true;
        }
        return false;
    }

    
    /**
     * Gera data em formato pt-br
     * 
     * @param string $data
     * @return string
     */
    public static function dataBr(string $data): string
    {
        return date('d/m/Y H:i:s', strtotime($data));
    }
}
