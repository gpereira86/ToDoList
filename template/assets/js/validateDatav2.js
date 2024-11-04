function validateData() {
    const tarefa = document.getElementById('tarefa').value.trim();
    const custo = document.getElementById('custo').value.trim();
    const data_limite = document.getElementById('dtLimite').value;

    if (!tarefa) {
        alert("O campo não pode ser vazio.");
        return false;
    }

    if (!custo || isNaN(custo) || parseFloat(custo) <= 0) {
        alert("Por favor, insira um valor positivo válido e não vazio.");
        return false;
    }

    if (!data_limite) {
        alert("O campo data não pode ser vazio.");
        return false;
    }

    return true;
}
