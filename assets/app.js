import './bootstrap.js';
import './styles/app.css';
document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('.faq-toggle');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const content = this.nextElementSibling;
            const arrow = this.querySelector('.faq-arrow');

            // Toggle content visibility
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        });
    });

    // Rolagem suave para as âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const headerOffset = 80; // Ajuste conforme necessário para o menu fixo
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});