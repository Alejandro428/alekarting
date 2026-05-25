$(document).ready(function() {
  const hamburguesa = document.querySelector('.hamburguesa');
  const navMenu = document.querySelector('ul.enlaces-nav.mobile-menu');
  const hamburguesaIcon = hamburguesa?.querySelector('i');
  const submenus = document.querySelectorAll('li.submenu');

  if (hamburguesa && navMenu) {
    hamburguesa.addEventListener('click', function() {
      navMenu.classList.toggle('active');

      if (hamburguesaIcon) {
        hamburguesaIcon.classList.toggle('bi-list');
        hamburguesaIcon.classList.toggle('bi-x');
      }

      if (!navMenu.classList.contains('active')) {
        submenus.forEach(submenu => submenu.classList.remove('active'));
      }
    });

    const navLinks = navMenu.querySelectorAll('li.elemento-enlace > a, li.submenu > .dropdown-toggle');
    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        const isDropdownToggle = link.classList.contains('dropdown-toggle');
        if (isDropdownToggle) return;

        if (window.innerWidth < 768) {
          navMenu.classList.remove('active');
          if (hamburguesaIcon) {
            hamburguesaIcon.classList.add('bi-list');
            hamburguesaIcon.classList.remove('bi-x');
          }

          // Cerrar todos los submenús por si acaso
          submenus.forEach(submenu => submenu.classList.remove('active'));
        }
      });
    });
  }

  const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

  if (isTouchDevice) {
    submenus.forEach(submenu => {
      const toggle = submenu.querySelector('.dropdown-toggle');

      if (toggle) {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();

          const isActive = submenu.classList.contains('active');

          submenus.forEach(otherSubmenu => {
            if (otherSubmenu !== submenu) {
              otherSubmenu.classList.remove('active');
            }
          });

          if (isActive) {
            submenu.classList.remove('active');
          } else {
            submenu.classList.add('active');
          }
        });
      }
    });

    document.addEventListener('click', function(e) {
      if (e.target.closest('.dropdown-toggle')) return;

      if (!e.target.closest('li.submenu')) {
        submenus.forEach(submenu => submenu.classList.remove('active'));
      }
    });
  }
});
