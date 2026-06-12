export default function initModal() {
  const botaoAbrir = document.querySelector('[data-modal="abrir"]');
  const botoesFechar = document.querySelectorAll('[data-modal="fechar"]');
  const containerModal = document.querySelector('[data-modal="container"]');

  if (botaoAbrir && containerModal) {
    // Função exclusiva para ABRIR o modal
    function abrirModal(event) {
      event.preventDefault();
      containerModal.classList.add("ativo");
    }

    // Função exclusiva para FECHAR o modal
    function fecharModal(event) {
      if (event) {
        event.preventDefault();
        event.stopPropagation(); // Evita que o evento suba para o container
      }
      containerModal.classList.remove("ativo");
    }

    // Função para fechar se clicar na área escura (fora do modal)
    function clickForaModal(event) {
      if (event.target === this) {
        fecharModal(event);
      }
    }

    // Ouve o evento de abrir
    botaoAbrir.addEventListener("click", abrirModal);

    // Ouve o evento de fechar em TODOS os botões que têm o atributo data-modal="fechar"
    if (botoesFechar.length > 0) {
      botoesFechar.forEach((botao) => {
        botao.addEventListener("click", fecharModal);
      });
    }

    // Ouve o clique fora
    containerModal.addEventListener("click", clickForaModal);
  }
}
