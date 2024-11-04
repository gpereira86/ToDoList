document.addEventListener('DOMContentLoaded', function () {

    const novaTarefaBtn = document.getElementById("novaTarefaBtn");
    const modalTitle = document.getElementById("exampleModalLabel");
    const saveButton = document.getElementById("saveButton");

    novaTarefaBtn.addEventListener('click', function () {

        const tarefa = document.getElementById('tarefa').value;
        const custo = document.getElementById('custo').value;
        const dataLimite = document.getElementById('dtLimite').value;

        modalTitle.textContent = "NOVA TAREFA";
        saveButton.id = "createButton"; 
        saveButton.textContent = "Criar";

        document.getElementById('tarefa').value = "";
        document.getElementById('custo').value = "";
        document.getElementById('dtLimite').value = "";
        document.getElementById('tarefaId').value = "";
    });

    document.querySelectorAll('.btn-warning').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const row = this.closest('tr');
            const tarefa = row.children[2].textContent;
            const custo = row.children[3].textContent; 
            const dataLimite = row.children[4].textContent; 
            const dataLimiteISO = new Date(dataLimite.split('/').reverse().join('-')).toISOString().split('T')[0];

            document.getElementById('tarefa').value = tarefa;
            document.getElementById('custo').value = custo;
            document.getElementById('dtLimite').value = dataLimiteISO;
            document.getElementById('tarefaId').value = id;

            modalTitle.textContent = "EDITAR TAREFA";
            saveButton.id = "saveButton"; 
            saveButton.textContent = "Salvar";
        });
    });

    document.getElementById('saveButton').addEventListener('click', function () {
    
        const tarefaId = document.getElementById('tarefaId').value;
        const tarefa = document.getElementById('tarefa').value;
        const custo = document.getElementById('custo').value;
        const dataLimite = document.getElementById('dtLimite').value;
        let url = '';
        let dados = '';


        if (!validateData()) {
            return; // Para a execução se a validação falhar
        }
        if (!tarefaId) {
            url = 'https://tarefas.glaucopereira.com/salvar/';
            dados = {tarefa: tarefa, custo: custo, data_limite: dataLimite};
        } else {
            url = 'https://tarefas.glaucopereira.com/editar/';
            dados = {id: tarefaId, tarefa: tarefa, custo: custo, data_limite: dataLimite};
        }


        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dados)
        })
                .then(response => {
                    return response.text();                    
                })
                .then(text => {
                    const jsonResponses = text.match(/\{.*?\}/g); 

                    if (jsonResponses) {
                        jsonResponses.forEach(jsonString => {
                            try {
                                const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                                const data = JSON.parse(jsonString); 
                               
                                if (data.status === 'processado') {

                                    alert(data.mensagem); 
                                    modal.hide();
                                    location.reload();

                                } else if (data.status == 'erro') {
                                    alert(data.mensagem); 
                                }

                            } catch (error) {
                                console.error('Erro ao analisar resposta do servidor: ', error);
                            }
                        });
                    } else {
                        console.error('Erro! Nenhuma resposta do servidor.');
                    }
                })
                .catch(error => {
                    alert('Erro ao atualizar a tarefa.');
                    console.error('Erro:', error);
                });
    });

});
