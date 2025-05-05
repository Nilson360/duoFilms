import './bootstrap.js';
import './styles/app.css';

// header
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.image-slide');
    const dots = document.querySelectorAll('.image-dot');
    const prevButton = document.querySelector('.image-prev');
    const nextButton = document.querySelector('.image-next');

    if (slides.length === 0 || dots.length === 0) return;

    let currentSlide = 0;

    function showSlide(n) {
        slides.forEach(slide => slide.classList.add('hidden'));
        dots.forEach(dot => {
            dot.classList.remove('opacity-100');
            dot.classList.add('opacity-50');
        });

        slides[n].classList.remove('hidden');
        dots[n].classList.remove('opacity-50');
        dots[n].classList.add('opacity-100');

        currentSlide = n;
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    if (nextButton) nextButton.addEventListener('click', nextSlide);
    if (prevButton) prevButton.addEventListener('click', prevSlide);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    showSlide(0); // Inicializa

    setInterval(nextSlide, 2000); // Auto-play
});

// Media gallery

document.addEventListener('DOMContentLoaded', () => {
    // Tabs de fotos e vídeos
    const galleryTabs = document.querySelectorAll('.gallery-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    galleryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            galleryTabs.forEach(t => {
                t.classList.remove('active', 'text-white', 'border-white');
                t.classList.add('text-gray-400', 'border-transparent');
            });

            tab.classList.add('active', 'text-white', 'border-white');
            tab.classList.remove('text-gray-400', 'border-transparent');

            tabContents.forEach(content => content.classList.add('hidden'));

            const targetId = tab.getAttribute('data-tab') + '-content';
            document.getElementById(targetId).classList.remove('hidden');
        });
    });

    // Filtro de fotos
    const photoFilters = document.querySelectorAll('.photo-filter');
    const galleryItems = document.querySelectorAll('.gallery-item');

    photoFilters.forEach(filter => {
        filter.addEventListener('click', () => {
            photoFilters.forEach(f => {
                f.classList.remove('active', 'bg-white', 'text-black');
                f.classList.add('bg-black', 'text-white', 'border', 'border-gray-700');
            });

            filter.classList.add('active', 'bg-white', 'text-black');
            filter.classList.remove('bg-black', 'text-white', 'border', 'border-gray-700');

            const category = filter.textContent.trim().toLowerCase();

            galleryItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category').toLowerCase();
                item.style.display = category === 'todos' || itemCategory === category ? 'block' : 'none';
            });
        });
    });

    // Filtro de vídeos
    const videoFilters = document.querySelectorAll('.video-filter');
    const videoItems = document.querySelectorAll('.video-item');

    videoFilters.forEach(filter => {
        filter.addEventListener('click', () => {
            videoFilters.forEach(f => {
                f.classList.remove('active', 'bg-white', 'text-black');
                f.classList.add('bg-black', 'text-white', 'border', 'border-gray-700');
            });

            filter.classList.add('active', 'bg-white', 'text-black');
            filter.classList.remove('bg-black', 'text-white', 'border', 'border-gray-700');

            const category = filter.textContent.trim().toLowerCase();

            videoItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category').toLowerCase();
                item.style.display = category === 'todos' || itemCategory === category ? 'block' : 'none';
            });
        });
    });

    // Modal da galeria
    const modal = document.getElementById('gallery-modal');
    const modalImage = document.getElementById('modal-image');
    const modalCaption = document.getElementById('modal-caption');
    const modalCounter = document.getElementById('modal-counter');
    const closeButton = document.getElementById('close-modal');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');
    const zoomButtons = document.querySelectorAll('.gallery-zoom');
    const galleryImages = Array.from(document.querySelectorAll('.gallery-item img'));

    let currentIndex = 0;

    zoomButtons.forEach((btn, i) => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const src = btn.getAttribute('data-src');
            const alt = galleryImages[i]?.alt || '';
            openModal(src, alt, i);
        });
    });

    galleryImages.forEach((img, i) => {
        img.addEventListener('click', () => {
            openModal(img.src, img.alt, i);
        });
    });

    closeButton?.addEventListener('click', closeModal);
    prevButton?.addEventListener('click', showPrevImage);
    nextButton?.addEventListener('click', showNextImage);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
        if (e.key === 'ArrowLeft') showPrevImage();
        if (e.key === 'ArrowRight') showNextImage();
    });

    modal?.addEventListener('click', e => {
        if (e.target === modal) closeModal();
    });

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
        const img = galleryImages[currentIndex];
        modalImage.src = img.src;
        modalImage.alt = img.alt;
        modalCaption.textContent = img.alt;
        updateCounter();
    }

    function showNextImage() {
        currentIndex = (currentIndex + 1) % galleryImages.length;
        const img = galleryImages[currentIndex];
        modalImage.src = img.src;
        modalImage.alt = img.alt;
        modalCaption.textContent = img.alt;
        updateCounter();
    }

    function updateCounter() {
        modalCounter.textContent = `${currentIndex + 1} / ${galleryImages.length}`;
    }
});
