<?php

use Dotenv\Dotenv;
use system\Control\Helpers;

//arqivo .env disponibilizado no mesmo diretórios deste arquivo
// esta .env serve para guardar dados de produção com maior proteçao
//  como senhas e urls e o que mais for necessário
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

//arquivo de configaração do sistema
//define fuso horário
date_default_timezone_set('America/Sao_Paulo');

//Informações do sistema
define('SITE_NOME', 'Lista de Tarefas');
define('SITE_DESCRICAO', 'Lista para organizar tarefas de maneira simples.');

//Urls do sistema
define('URL_PRODUCAO', 'URL de produção aqui');
define('URL_DESENVOLVIMENTO', 'http://localhost/todolist/');


if (Helpers::localhost()) {
    
    define('DB_HOST', 'localhost');
    define('DB_PORTA', '3306');
    define('DB_NOME', 'todo_list');
    define('DB_USUARIO', 'root');
    define('DB_SENHA', '');
    
    define('URL_SITE', '/todolist/');
            
} else {
    
    define('DB_HOST', $_ENV['DB_HOST']);
    define('DB_PORTA', $_ENV['DB_PORTA']);
    define('DB_NOME', $_ENV['DB_NOME']);
    define('DB_USUARIO', $_ENV['DB_USUARIO']);
    define('DB_SENHA', $_ENV['DB_SENHA']);
    
    define('URL_SITE', '/');
    
}
