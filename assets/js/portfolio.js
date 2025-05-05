document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    const loadMoreButton = document.getElementById('load-more');
    let clickCount = 0;

    // Ativa o botão "Todos" inicialmente
    const defaultFilter = document.querySelector('[data-filter="all"]');
    if (defaultFilter) defaultFilter.classList.add('active');

    // Lógica de filtragem
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');

            // Atualiza estilos dos botões
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'text-black');
                btn.classList.add('border-gray-700');
            });
            button.classList.add('active', 'bg-white', 'text-black');
            button.classList.remove('border-gray-700');

            // Mostra/oculta itens
            portfolioItems.forEach(item => {
                const category = item.getAttribute('data-category');
                item.style.display = (filter === 'all' || category === filter) ? 'block' : 'none';
            });
        });
    });

    // Botão "Carregar Mais" (simulação)
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', () => {
            clickCount++;

            if (clickCount >= 2) {
                loadMoreButton.textContent = 'Não há mais projetos';
                loadMoreButton.disabled = true;
                loadMoreButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                // Simula carregamento
                showToast('Carregando mais projetos...');
            }
        });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-black text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    }
});
