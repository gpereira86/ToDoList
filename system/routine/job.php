<?php

date_default_timezone_set('America/Sao_Paulo'); 

$log_file = __DIR__ . '/cron_log.log'; // Define o caminho para o arquivo de log
$sql_file_path = __DIR__ . '/tasks.sql'; // Caminho para o arquivo SQL

$servername = "localhost";
$username = "u811913753_gpereira_pjt2";
$password = "Pjt2_todo_list_598654";
$dbname = "u811913753_todo_list";


// Função para registrar mensagens no arquivo de log
function log_message($message) {
    global $log_file;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($log_file, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
log_message("------- Conexao iniciada -------");

// Verifica a conexão
if ($conn->connect_error) {
    log_message("Conexão falhou: " . $conn->connect_error);
    exit();
}

// Inicia a transação
$conn->begin_transaction();

try {
    // SQL para apagar a tabela tasks
    $drop_sql = "DROP TABLE IF EXISTS tasks;";
    if ($conn->query($drop_sql) === TRUE) {
        log_message("Tabela 'tasks' apagada com sucesso.");
    } else {
        throw new Exception("Erro ao apagar a tabela 'tasks': " . $conn->error);
    }
    
    // Aguarda 2 segundos antes de continuar
    sleep(2);
    
    // "Refresh" no banco de dados executando uma consulta simples
    $conn->query("SELECT 1");
    log_message("Refresh no banco de dados executado.");
    
    // Lê o conteúdo do arquivo SQL
    $sql_commands = file_get_contents($sql_file_path);
    if ($sql_commands === false) {
        throw new Exception("Não foi possível ler o arquivo SQL.");
    }
    
    

    // Executa o conteúdo do arquivo SQL para recriar a tabela
    if ($conn->multi_query($sql_commands)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->next_result());
        log_message("Tabela 'tasks' recriada com sucesso a partir do arquivo SQL.");
    } else {
        throw new Exception("Erro ao recriar a tabela 'tasks': " . $conn->error);
    }

    // Faz o commit
    $conn->commit();
} catch (Exception $e) {
    // Se houve uma exceção, faz o rollback
    $conn->rollback();
    log_message("Exceção: " . $e->getMessage());
}

// Fecha a conexão
$conn->close();
log_message("------- Conexao encerrada -------");
?>
