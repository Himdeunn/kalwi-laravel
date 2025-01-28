// code for navbar
document.getElementById('dropdown-button').addEventListener('click', function() {
    const navbar = document.getElementById('navbar-sticky');
    navbar.classList.toggle('hidden');
    if (navbar.classList.contains('hidden')) {
        navbar.classList.add('opacity-0', 'max-h-0');
        setTimeout(() => {
            navbar.classList.remove('transition-all', 'duration-300', 'ease-in-out', 'transform');
        }, 300);
    } else {
        navbar.classList.remove('opacity-0', 'max-h-0');
        setTimeout(() => {
            navbar.classList.add('transition-all', 'duration-300', 'ease-in-out', 'transform');
        }, 10);
    }
});