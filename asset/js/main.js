document.addEventListener('change', function(event) {
    if (event.target.matches('.form-check-input')) {
        const listItem = event.target.closest('.list-group-item');
        listItem.classList.add('checked-animation');
        setTimeout(() => listItem.classList.remove('checked-animation'), 500);
    }
});

document.addEventListener('click', function(event) {
    if (event.target.matches('.card-link img')) {
        event.preventDefault(); // Prévenir le rechargement immédiat
        const listItem = event.target.closest('.list-group-item');
        listItem.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        listItem.style.opacity = '0';
        listItem.style.transform = 'scale(0.9)';
        setTimeout(() => {
            window.location.href = event.target.parentElement.href; // Redirige vers l'URL de suppression
        }, 500);
    }
});

window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
