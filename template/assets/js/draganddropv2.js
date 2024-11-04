function atualizaOrdem() {
    const ids = Array.from(dropZone.querySelectorAll('.draggable-item')).map(item => {
        const row = item.closest('tr');
        return row.children[1].textContent.trim();
    });
    fetch('https://tarefas.glaucopereira.com/updateOrder/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({itens: ids})
    })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao atualizar a ordem');
                }
                return response.json();
            })
            .then(data => {
                console.log('Resposta do servidor:', data);
                if (data.status === 'processado') {
                    console.log('Atualização bem-sucedida:', data.resultados);
                    location.reload();
                } else {
                    console.error('Erro na atualização:', data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro:', error.message);
            });
}


document.addEventListener('DOMContentLoaded', function () {

    
    const draggableItems = document.querySelectorAll('.draggable-item');


    draggableItems.forEach(item => {
        item.addEventListener('dragstart', event => {
            item.classList.add('dragging');
            event.dataTransfer.effectAllowed = 'move';
        });

        item.addEventListener('dragend', () => {
            item.classList.remove('dragging');

            const ids = [...document.querySelectorAll('.draggable-item')].map(item => item.id);
            atualizaOrdem(ids);
        });
    });

    const dropZone = document.getElementById('dropZone');

    dropZone.addEventListener('dragover', event => {
        event.preventDefault();
        const draggingItem = document.querySelector('.dragging');
        const afterElement = getDragAfterElement(dropZone, event.clientY);

        if (afterElement == null) {
            dropZone.appendChild(draggingItem);
        } else {
            dropZone.insertBefore(draggingItem, afterElement);
        }

    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.draggable-item:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return {offset: offset, element: child};
            } else {
                return closest;
            }
        }, {offset: Number.NEGATIVE_INFINITY}).element;
    }

});
