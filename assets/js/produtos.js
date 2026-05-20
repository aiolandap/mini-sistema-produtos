document.addEventListener("DOMContentLoaded", function () {
    carregarProdutos();

    const formProduto = document.getElementById("formProduto");

    formProduto.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(formProduto);

        fetch("../actions/produto_action.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                mostrarMensagem(data.mensagem, data.status);

                if (data.status === "sucesso") {
                    formProduto.reset();
                    carregarProdutos();
                }
            })
            .catch(error => {
                mostrarMensagem("Erro ao cadastrar produto.", "erro");
                console.error(error);
            });
    });
});

function carregarProdutos() {
    fetch("../ajax/listar_produtos.php")
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById("listaProdutos");

            if (data.status !== "sucesso") {
                lista.innerHTML = `<div class="alert alert-danger">${data.mensagem}</div>`;
                return;
            }

            if (data.dados.length === 0) {
                lista.innerHTML = `<div class="alert alert-info">Nenhum produto cadastrado.</div>`;
                return;
            }

            let html = `
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produto</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Fornecedor</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.dados.forEach(produto => {
                html += `
                    <tr>
                        <td>${produto.id}</td>
                        <td>${produto.nome}</td>
                        <td>${produto.descricao ?? ""}</td>
                        <td>R$ ${parseFloat(produto.preco).toFixed(2).replace(".", ",")}</td>
                        <td>${produto.fornecedor_nome}</td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            lista.innerHTML = html;
        })
        .catch(error => {
            document.getElementById("listaProdutos").innerHTML =
                `<div class="alert alert-danger">Erro ao carregar produtos.</div>`;

            console.error(error);
        });
}

function mostrarMensagem(mensagem, status) {
    const divMensagem = document.getElementById("mensagem");

    const classe = status === "sucesso" ? "success" : "danger";

    divMensagem.innerHTML = `
        <div class="alert alert-${classe}">
            ${mensagem}
        </div>
    `;

    setTimeout(() => {
        divMensagem.innerHTML = "";
    }, 3000);
}