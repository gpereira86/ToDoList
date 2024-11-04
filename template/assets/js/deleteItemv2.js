const deleteButtons = document.querySelectorAll('.btn-outline-danger');
let tarefaId;
let tarefaNome;
deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
        tarefaId = this.getAttribute('data-id');
        tarefaNome = this.getAttribute('data-tarefa');
        
        document.getElementById('tarefaName').textContent = tarefaNome; 
    });
});

document.getElementById('confirmDeleteButton').addEventListener('click', function () {
    localStorage.setItem('deleteSuccess', 'true');

    const url = `https://tarefas.glaucopereira.com/delete/${tarefaId}`;
    window.location.href = url;

    const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModalDelete'));
    modal.hide();
    location.reload();
});

window.addEventListener('load', function () {
    if (localStorage.getItem('deleteSuccess') === 'true') {
        alert('Excluído com sucesso!');
        location.reload();
        localStorage.removeItem('deleteSuccess');
    }
});


