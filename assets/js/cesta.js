document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const cestaId = params.get("cesta_id");

    if (!cestaId) {
        document.getElementById("conteudoCesta").innerHTML = `
            <div class="alert alert-danger">
                Cesta inválida.
            </div>
        `;
        return;
    }

    carregarCesta(cestaId);
});

function carregarCesta(cestaId) {
    fetch(`../ajax/listar_cesta.php?cesta_id=${cestaId}`)
        .then(response => response.json())
        .then(data => {
            const conteudo = document.getElementById("conteudoCesta");

            if (data.status !== "sucesso") {
                conteudo.innerHTML = `
                    <div class="alert alert-danger">
                        ${data.mensagem}
                    </div>
                `;
                return;
            }

            let html = `
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong>Resumo da Cesta #${data.cesta_id}</strong>
                    </div>

                    <div class="card-body">
                        <p><strong>Total de produtos selecionados:</strong> ${data.total_produtos}</p>
                        <p><strong>Valor total:</strong> R$ ${Number(data.valor_total).toFixed(2).replace(".", ",")}</p>
                    </div>
                </div>
            `;

            html += `
                <div class="card shadow">
                    <div class="card-header">
                        <strong>Produtos selecionados</strong>
                    </div>

                    <div class="card-body">
            `;

            if (data.produtos.length === 0) {
                html += `
                    <div class="alert alert-info">
                        Nenhum produto foi adicionado a esta cesta.
                    </div>
                `;
            } else {
                html += `
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Descrição</th>
                                    <th>Preço</th>
                                    <th>Fornecedor</th>
                                    <th>Quantidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                data.produtos.forEach(produto => {
                    html += `
                        <tr>
                            <td>${produto.nome}</td>
                            <td>${produto.descricao ?? ""}</td>
                            <td>R$ ${Number(produto.preco).toFixed(2).replace(".", ",")}</td>
                            <td>${produto.fornecedor_nome}</td>
                            <td>1</td>
                            <td>
                                <button 
                                    class="btn btn-sm btn-danger"
                                    onclick="removerProdutoDaCesta(${data.cesta_id}, ${produto.id})"
                                >
                                    Remover
                                </button>
                            </td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;
            }

            html += `
                    </div>
                </div>
            `;

            conteudo.innerHTML = html;
        })
        .catch(error => {
            document.getElementById("conteudoCesta").innerHTML = `
                <div class="alert alert-danger">
                    Erro ao carregar cesta.
                </div>
            `;

            console.error(error);
        });
}

function removerProdutoDaCesta(cestaId, produtoId) {
    if (!confirm("Tem certeza que deseja remover este produto da cesta?")) {
        return;
    }

    const formData = new FormData();
    formData.append("cesta_id", cestaId);
    formData.append("produto_id", produtoId);

    fetch("../actions/remover_produto_cesta_action.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "sucesso") {
                carregarCesta(cestaId);
            } else {
                alert(data.mensagem);
            }
        })
        .catch(error => {
            alert("Erro ao remover produto da cesta.");
            console.error(error);
        });
}