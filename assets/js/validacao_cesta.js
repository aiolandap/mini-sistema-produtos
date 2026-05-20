document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formSelecionarProdutos");

    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {
        const selecionados = document.querySelectorAll(".produto-checkbox:checked");

        if (selecionados.length === 0) {
            event.preventDefault();

            document.getElementById("mensagemValidacao").innerHTML = `
                <div class="alert alert-warning">
                    Selecione pelo menos um produto antes de adicionar à cesta.
                </div>
            `;
        }
    });
});