export default function setupActiveMenu() {
  const links = document.querySelectorAll(".navegacao-menu li a");
  const currentUrl = window.location.href;

  links.forEach((link) => {
    // 1. Marca como ativo se o link for a página atual (ao carregar)
    if (link.href === currentUrl) {
      link.classList.add("active");
    }

    // 2. Ouve o clique
    link.addEventListener("click", function (event) {
      event.preventDefault();
      links.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");
    });
  });
}
