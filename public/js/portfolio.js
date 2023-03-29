croixMenu = document.getElementById('croixMenuMobile');
menuMobile = document.getElementById('menuMobile');
navMenu = document.getElementById('navMenu');

menuMobile.addEventListener('touchstart', () => {
    croixMenu.style.display = 'block';
    navMenu.style.display = 'flex';
    menuMobile.style.display = 'none';
})

croixMenu.addEventListener('touchstart', () => {
    croixMenu.style.display = 'none';
    navMenu.style.display = 'none';
    menuMobile.style.display = 'block';
})