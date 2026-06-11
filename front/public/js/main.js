import setupActiveMenu from "./modules/sidebar.js";
import initModal from "./modules/modal.js";
import sidebarToggle from "./modules/sidebarToggle.js";
import inputSearchTest from "./modules/inputSearch.js";

document.addEventListener("DOMContentLoaded", () => {
    setupActiveMenu();
    initModal();
    sidebarToggle();
    inputSearchTest();

    const btnNotificacao = document.getElementById('btn-notificacao');
    const dropdownNotificacao = document.getElementById('dropdown-notificacao');

    console.log("Botão:", btnNotificacao, "Caixa:", dropdownNotificacao);

    if (btnNotificacao && dropdownNotificacao) {
        
        btnNotificacao.addEventListener('click', (event) => {

            console.log("CLICOU NO SININHO!");

            dropdownNotificacao.classList.toggle('mostrar');
            event.stopPropagation(); 
        });

        document.addEventListener('click', (event) => {
            if (!dropdownNotificacao.contains(event.target)) {
                dropdownNotificacao.classList.remove('mostrar');
            }
        });
    }
});


document.addEventListener('DOMContentLoaded', () => {
    const botoesEditar = document.querySelectorAll('.btn-editar-produto');
    const modalEditar = document.getElementById('modal-editar');
    const btnFecharEditar = document.getElementById('btn-fechar-editar');
    const btnCancelarEditar = document.getElementById('btn-cancelar-editar');

    botoesEditar.forEach(botao => {
        botao.addEventListener('click', () => {
            
            const id = botao.getAttribute('data-id');
            const nome = botao.getAttribute('data-nome');
            const sku = botao.getAttribute('data-sku');
            const preco = botao.getAttribute('data-preco');
            const estoque = botao.getAttribute('data-estoque');
            const status = botao.getAttribute('data-status');
            const categoria = botao.getAttribute('data-categoria');

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nome').value = nome;
            document.getElementById('edit_sku').value = sku;
            document.getElementById('edit_preco').value = preco;
            document.getElementById('edit_estoque').value = estoque;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_categoria').value = categoria;

            modalEditar.style.display = 'flex';
        });
    });

    const fecharModalEdit = () => {
        modalEditar.style.display = 'none';
    };

    btnFecharEditar.addEventListener('click', fecharModalEdit);
    btnCancelarEditar.addEventListener('click', fecharModalEdit);
});



document.addEventListener('DOMContentLoaded', () => {
    const botoesEditarForn = document.querySelectorAll('.btn-editar-fornecedor');
    const modalEditarForn = document.getElementById('modal-editar-fornecedor');
    const btnFecharForn = document.getElementById('btn-fechar-editar-forn');
    const btnCancelarForn = document.getElementById('btn-cancelar-editar-forn');

    if (botoesEditarForn && modalEditarForn) {
        botoesEditarForn.forEach(botao => {
            botao.addEventListener('click', () => {
                document.getElementById('edit_forn_id').value = botao.getAttribute('data-id');
                document.getElementById('edit_forn_polo').value = botao.getAttribute('data-polo');
                document.getElementById('edit_forn_razao').value = botao.getAttribute('data-razao');
                document.getElementById('edit_forn_fantasia').value = botao.getAttribute('data-fantasia');
                document.getElementById('edit_forn_cnpj').value = botao.getAttribute('data-cnpj');
                document.getElementById('edit_forn_localidade').value = botao.getAttribute('data-localidade');

                modalEditarForn.style.display = 'flex';
            });
        });

        // Fechar Modal
        const fecharModalForn = () => modalEditarForn.style.display = 'none';
        btnFecharForn.addEventListener('click', fecharModalForn);
        btnCancelarForn.addEventListener('click', fecharModalForn);
    }
});