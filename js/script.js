document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const inputQuantidade = document.querySelector('input[name="quantidade"]');

    if (form && inputQuantidade) {
        form.addEventListener('submit', function (e) {
            if (parseInt(inputQuantidade.value) <= 0 || isNaN(inputQuantidade.value)) {
                e.preventDefault();
                alert('Informe uma quantidade válida maior que zero.');
            }
        });
    }
});