document.addEventListener("DOMContentLoaded", function () {
    carregarFornecedores();

    const formFornecedor = document.getElementById("formFornecedor");

    formFornecedor.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(formFornecedor);

        fetch("../actions/fornecedor_action.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                mostrarMensagem(data.mensagem, data.status);

                if (data.status === "sucesso") {
                    formFornecedor.reset();
                    carregarFornecedores();
                }
            })
            .catch(error => {
                mostrarMensagem("Erro ao cadastrar fornecedor.", "erro");
                console.error(error);
            });
    });
});

function carregarFornecedores() {
    fetch("../ajax/listar_fornecedores.php")
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById("listaFornecedores");

            if (data.status !== "sucesso") {
                lista.innerHTML = `<div class="alert alert-danger">${data.mensagem}</div>`;
                return;
            }

            if (data.dados.length === 0) {
                lista.innerHTML = `<div class="alert alert-info">Nenhum fornecedor cadastrado.</div>`;
                return;
            }

            let html = `
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th>Endereço</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.dados.forEach(fornecedor => {
                html += `
                    <tr>
                        <td>${fornecedor.id}</td>
                        <td>${fornecedor.nome}</td>
                        <td>${fornecedor.cnpj ?? ""}</td>
                        <td>${fornecedor.telefone ?? ""}</td>
                        <td>${fornecedor.email ?? ""}</td>
                        <td>${fornecedor.endereco ?? ""}</td>
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
            document.getElementById("listaFornecedores").innerHTML =
                `<div class="alert alert-danger">Erro ao carregar fornecedores.</div>`;

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