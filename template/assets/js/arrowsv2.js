document.querySelectorAll('.fa-chevron-down').forEach(downArrow => {
    downArrow.addEventListener('click', () => {
        const row = downArrow.closest('tr');
        const nextRow = row.nextElementSibling;
        if (nextRow) {
            row.parentNode.insertBefore(nextRow, row); 
            atualizaOrdem();
        }
    });
});

document.querySelectorAll('.fa-chevron-up').forEach(upArrow => {
    upArrow.addEventListener('click', () => {
        const row = upArrow.closest('tr'); 
        const previousRow = row.previousElementSibling; 
        if (previousRow) {
            row.parentNode.insertBefore(row, previousRow);
            atualizaOrdem(); 
        }
    });
});