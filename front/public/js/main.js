import setupActiveMenu from "./modules/sidebar.js";
import initModal from "./modules/modal.js";
import sidebarToggle from "./modules/sidebarToggle.js";
import inputSearchTest from "./modules/inputSearch.js";

document.addEventListener("DOMContentLoaded", () => {
    // 1. Inicializa todos os seus módulos importados
    setupActiveMenu();
    initModal();
    sidebarToggle();
    inputSearchTest();

    // 2. Lógica das Notificações (Sininho)
    const btnNotificacao = document.getElementById('btn-notificacao');
    const dropdownNotificacao = document.getElementById('dropdown-notificacao');

    console.log("Botão:", btnNotificacao, "Caixa:", dropdownNotificacao);

    if (btnNotificacao && dropdownNotificacao) {
        
        // Abre ou fecha a caixa ao clicar no sino
        btnNotificacao.addEventListener('click', (event) => {

            console.log("CLICOU NO SININHO!");

            dropdownNotificacao.classList.toggle('mostrar');
            event.stopPropagation(); 
        });

        // Fecha a caixa se clicar fora dela
        document.addEventListener('click', (event) => {
            if (!dropdownNotificacao.contains(event.target)) {
                dropdownNotificacao.classList.remove('mostrar');
            }
        });
    }
});





document.addEventListener('DOMContentLoaded', () => {
    // 1. Pega todos os ícones de lápis e os elementos do Modal de Edição
    const botoesEditar = document.querySelectorAll('.btn-editar-produto');
    const modalEditar = document.getElementById('modal-editar');
    const btnFecharEditar = document.getElementById('btn-fechar-editar');
    const btnCancelarEditar = document.getElementById('btn-cancelar-editar');

    // 2. Adiciona o evento de clique em cada lápis
    botoesEditar.forEach(botao => {
        botao.addEventListener('click', () => {
            
            // Pega os dados do lápis clicado
            const id = botao.getAttribute('data-id');
            const nome = botao.getAttribute('data-nome');
            const sku = botao.getAttribute('data-sku');
            const preco = botao.getAttribute('data-preco');
            const estoque = botao.getAttribute('data-estoque');
            const status = botao.getAttribute('data-status');
            const categoria = botao.getAttribute('data-categoria');

            // Preenche os campos do modal
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nome').value = nome;
            document.getElementById('edit_sku').value = sku;
            document.getElementById('edit_preco').value = preco;
            document.getElementById('edit_estoque').value = estoque;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_categoria').value = categoria;

            // Mostra o modal na tela (se o seu modal ativo usa classe CSS, mude aqui)
            modalEditar.style.display = 'flex';
        });
    });

    // 3. Lógica para fechar o modal
    const fecharModalEdit = () => {
        modalEditar.style.display = 'none';
    };

    btnFecharEditar.addEventListener('click', fecharModalEdit);
    btnCancelarEditar.addEventListener('click', fecharModalEdit);
});