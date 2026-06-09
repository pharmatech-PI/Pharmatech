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