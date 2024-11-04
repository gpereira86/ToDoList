<?php

namespace system\WebControl;

use system\Control\Controlador;
use system\model\TarefasModelo;
use system\Control\Helpers;

//use sistema\Nucleo\Controlador;
//use sistema\Modelo\PostModelo;
//use sistema\Modelo\CategoriaModelo;
//use sistema\Biblioteca\Paginar;

/**
 * Controle Geral: Renderizações de HTML
 * 
 * @author Glauco Pereira <eu@glaucopereira.com>
 * @copyright Copyright (c) 2024, Glauco Pereira
 */
class SiteControlador extends Controlador
{

    /**
     * Construtor chama o contrutor do controlador (parente) passando um parâmetro 
     * 
     */
    public function __construct()
    {
        parent::__construct('template/');
    }

    /**
     * Renderizador da página inicial
     * 
     * @return void
     */
    public function index(): void
    {

        $tarefas = new TarefasModelo();

        echo $this->template->renderizar('index.html', [
            'tarefas' => $tarefas->busca()->ordem('ordenar ASC')->resultado(true),
            'ultimo' => $tarefas->ultimoOrdem(),
        ]);
    }

    /**
     * API para atualizar ordem nas movimentações na
     *  tabela por Arrow ou Drag and Drop
     */
    public function updateOrder(): void
    {

        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['itens']) && is_array($data['itens'])) {
            $itens = $data['itens'];
            $resultados = [];
            $result = "";

            foreach ($itens as $index => $id) {
                $tarefas = new TarefasModelo();
                $tempOrdem = $tarefas->ultimoOrdem();
                $tarefas->atualizarOrdens($id, ($tempOrdem + 10));
            }

            foreach ($itens as $index => $id) {
                $id = filter_var($id, FILTER_VALIDATE_INT);
                $ordem = filter_var($index + 1, FILTER_VALIDATE_INT); 
                $tarefas = new TarefasModelo(); 

                $result = $tarefas->atualizarOrdens($id, $ordem);
                
                $resultados[] = [
                    'id' => $id,
                    'ordenar' => $ordem,
                    'id tipo' => gettype($id),
                    'ordem tipo' => gettype($ordem),
                    'Resultado' => $result,
                ];
            }

            echo json_encode(['status' => 'processado', 'resultados' => $resultados]);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Nenhum dado recebido ou formato inválido.']);
        }
    }

    /**
     * 
     */
    public function deletar(array $idRecebido): void
    {
        $tarefas = new TarefasModelo();
        $id = filter_var($idRecebido['id'], FILTER_VALIDATE_INT);

        if (!$tarefas->buscaPorId($id)) {
            echo json_encode(['message' => 'Tarefa não encontrada nos registros, erro ao excluir. ']);
        } else {

            try {
                $result = $tarefas->deletar($id);

                if ($result) {
                    echo json_encode(['message' => 'Tarefa excluída com sucesso. ']);
                } else {
                    echo json_encode(['message' => 'Tarefa não encontrada ou erro ao excluir. ']);
                }
            } catch (Exception $ex) {
                echo json_encode(['message' => 'Erro ao excluir a tarefa: ' . $ex->getMessage()]);
            }
        }
    }

    /**
     * 
     */
    public function editar(): void
    {

        $data = json_decode(file_get_contents("php://input"), true);

        if ($data !== null) {

            $tarefa = new TarefasModelo();
            // Acessando os dados do array
            $tarefa->id = filter_var($data['id'], FILTER_VALIDATE_INT); // Acessa o id
            $tarefa->tarefa = strval($data['tarefa']); // Acessa a tarefa
            $tarefa->custo = $data['custo']; // Acessa o custo
            $tarefa->data_limite = $data['data_limite']; // Acessa a data limite

            try {
                $busca = (new TarefasModelo())->busca("tarefa = '{$tarefa->tarefa}'" . " AND id <> {$tarefa->id}");

                $verificador = $busca->resultado(true);

                if (is_array($verificador)) {
                    echo json_encode(['status' => 'erro', 'mensagem' => 'Essa tarefa já existe, erro ao salvar registro.']);
                }

                $result = $tarefa->salvar();

                if ($result) {
                    echo json_encode(['status' => 'processado', 'mensagem' => "Registro atualizado com sucesso!"]);
                }
            } catch (error) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar registro.']);
            }
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Nenhum dado recebido ou formato inválido.']);
        }
    }
    
    /**
     * 
     */
    public function salvar(): void
    {

        $data = json_decode(file_get_contents("php://input"), true);

        $tarefa = new TarefasModelo();

        $tarefa->tarefa = strval($data['tarefa']);
        $tarefa->custo = $data['custo'];
        $tarefa->data_limite = $data['data_limite'];
        $tarefa->ordenar = (($tarefa->ultimoOrdem()) + 1);

        try {
            $busca = $tarefa->busca("tarefa = '{$tarefa->tarefa}'");
            $verificador = $busca->resultado(true);

            if (is_array($verificador)) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Essa tarefa já existe, erro ao salvar registro.']);
            }

            $result = $tarefa->salvar();

            if ($result) {
                echo json_encode(['status' => 'processado', 'mensagem' => "Registro atualizado com sucesso!"]);
            }
        } catch (Exception $ex) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar registro.']);
        }
    }
}
