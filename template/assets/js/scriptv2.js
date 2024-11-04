const myModal = document.getElementById('myModal');
const myInput = document.getElementById('myInput');

myModal.addEventListener('shown.bs.modal', () => {
    myInput.focus();
});


function formatarDataDDMMYYYY(data) {
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    return `${ano}-${mes}-${dia}`;
}

document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('tr.bg-yellow-test');
    rows.forEach(row => {
        const headers = row.querySelectorAll('th');
        headers.forEach(header => {
            header.style.backgroundColor = 'yellow';
        });
    });
});

