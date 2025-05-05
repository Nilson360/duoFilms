document.addEventListener('DOMContentLoaded', function () {
    // Sistema de tabs
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remover classe active de todos os botões
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-white', 'border-white');
                btn.classList.add('text-gray-400', 'border-transparent');
            });

            // Adicionar classe active ao botão clicado
            this.classList.add('active', 'text-white', 'border-white');
            this.classList.remove('text-gray-400', 'border-transparent');

            // Esconder todos os conteúdos de tab
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Mostrar o conteúdo de tab selecionado
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.remove('hidden');
        });
    });

    // Filtros de fotos
    const photoFilters = document.querySelectorAll('.photo-filter');
    const galleryItems = document.querySelectorAll('.gallery-item');

    if (photoFilters.length > 0) {
        photoFilters.forEach(filter => {
            filter.addEventListener('click', function () {
                // Remover classe active de todos os filtros
                photoFilters.forEach(f => {
                    f.classList.remove('active', 'bg-black', 'text-white');
                    f.classList.add('text-gray-400');
                });

                // Adicionar classe active ao filtro clicado
                this.classList.add('active', 'bg-black', 'text-white');
                this.classList.remove('text-gray-400');

                // Filtrar os itens da galeria
                const category = this.textContent.trim().toLowerCase();

                galleryItems.forEach(item => {
                    if (category === 'todas' || item.getAttribute('data-category').toLowerCase() === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    }

    // Galeria em modo fullscreen
    const modal = document.getElementById('gallery-modal');
    const modalImage = document.getElementById('modal-image');
    const modalCaption = document.getElementById('modal-caption');
    const modalCounter = document.getElementById('modal-counter');
    const closeButton = document.getElementById('close-modal');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');
    const zoomButtons = document.querySelectorAll('.gallery-zoom');

    let currentIndex = 0;
    const galleryImages = Array.from(document.querySelectorAll('.gallery-item img'));

    // Abrir modal ao clicar no botão de zoom
    zoomButtons.forEach((button, index) => {
        button.addEventListener('click', function () {
            const src = this.getAttribute('data-src');
            const imgElement = galleryImages[index];
            const alt = imgElement.alt;

            openModal(src, alt, index);
        });
    });

    // Clicar na imagem também deve abrir o modal
    galleryImages.forEach((img, index) => {
        img.addEventListener('click', function () {
            openModal(this.src, this.alt, index);
        });
    });

    // Fechar modal
    closeButton.addEventListener('click', closeModal);

    // Fechar modal ao pressionar ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }

        if (e.key === 'ArrowLeft') {
            showPrevImage();
        }

        if (e.key === 'ArrowRight') {
            showNextImage();
        }
    });

    // Clicar fora do conteúdo também fecha o modal
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Navegar entre imagens
    prevButton.addEventListener('click', showPrevImage);
    nextButton.addEventListener('click', showNextImage);

    function openModal(src, alt, index) {
        modal.classList.remove('hidden');
        modalImage.src = src;
        modalImage.alt = alt;
        modalCaption.textContent = alt;
        currentIndex = index;
        updateCounter();
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function showPrevImage() {
        currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
        modalImage.src = galleryImages[currentIndex].src;
        modalImage.alt = galleryImages[currentIndex].alt;
        modalCaption.textContent = galleryImages[currentIndex].alt;
        updateCounter();
    }

    function showNextImage() {
        currentIndex = (currentIndex + 1) % galleryImages.length;
        modalImage.src = galleryImages[currentIndex].src;
        modalImage.alt = galleryImages[currentIndex].alt;
        modalCaption.textContent = galleryImages[currentIndex].alt;
        updateCounter();
    }

    function updateCounter() {
        modalCounter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
    }
});
