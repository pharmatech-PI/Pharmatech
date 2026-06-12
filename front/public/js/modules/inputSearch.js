export default function inputSearchTest() {
    const inputFornecedor = document.getElementById('busca-fornecedor');
    const inputProduto = document.getElementById('busca-produto');
    const inputMovimentacao = document.getElementById('busca-movimentacao');
    
    if (inputFornecedor) {
        inputFornecedor.addEventListener('input', function() {
            const termo = this.value.toLowerCase();
            const linhas = document.querySelectorAll('.fornecedor-table tbody tr');

            linhas.forEach(linha => {
                const fornecedor = linha.cells[2].textContent.toLowerCase();
                const cnpj = linha.cells[4].textContent.toLowerCase();

                if (fornecedor.includes(termo) || cnpj.includes(termo)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        });
    }

    if (inputProduto) {
        inputProduto.addEventListener('input', function() {
            const termoinput = this.value.toLowerCase();
            const linhas = document.querySelectorAll('.produto-table tbody tr');

            linhas.forEach(linha => {
                const produto = linha.cells[1].textContent.toLowerCase();
                const sku = linha.cells[2].textContent.toLowerCase();

                if (produto.includes(termoinput) || sku.includes(termoinput)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        });
    }

    if (inputMovimentacao) {
        inputMovimentacao.addEventListener('input', function() {
            const termoMovimentacao = this.value.toLowerCase();
            const linhas = document.querySelectorAll('.table-movimentacao tbody tr');

            linhas.forEach(linha => {
                const produto = linha.cells[3].textContent.toLowerCase();
                const lote = linha.cells[4].textContent.toLowerCase();

                if (produto.includes(termoMovimentacao) || lote.includes(termoMovimentacao)) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        });
    }
    
}