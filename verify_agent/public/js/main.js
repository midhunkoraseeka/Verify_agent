//  <!-- Start JavaScript Section -->
/* Start Variable Initialization */
const toggleBtn = document.getElementById('toggleBtn');
const sidebar = document.querySelector('.sidebar');
const content = document.querySelector('.content');
const header = document.querySelector('.header');
const overlay = document.getElementById('overlay');
let isCollapsed = false;
/* End Variable Initialization */

/* Start Dropdown Toggle Functionality */
document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
  toggle.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation(); // Prevent event bubbling to parent elements

    const submenuId = toggle.getAttribute('data-submenu');
    const submenu = document.getElementById(submenuId);
    const isOpen = submenu.classList.contains('show');

    // Get the parent container for sibling toggles (either sidebar-content or parent submenu)
    const parentContainer = toggle.closest('.submenu') || document.querySelector('.sidebar-content');

    // Close sibling submenus at the same level, but preserve parent submenus
    const siblingToggles = parentContainer.querySelectorAll(':scope > .dropdown-toggle, :scope > .submenu > .dropdown-toggle');
    siblingToggles.forEach(sibling => {
      if (sibling !== toggle) {
        const siblingSubmenuId = sibling.getAttribute('data-submenu');
        const siblingSubmenu = document.getElementById(siblingSubmenuId);
        if (siblingSubmenu) {
          siblingSubmenu.classList.remove('show');
          sibling.classList.remove('show');
        }
      }
    });

    // Toggle the current submenu
    submenu.classList.toggle('show');
    toggle.classList.toggle('show');

    // On mobile, ensure sidebar is visible when opening a submenu
    if (window.innerWidth <= 768) {
      sidebar.classList.remove('hidden');
      content.classList.remove('full-width');
      header.classList.remove('full-width');
      overlay.classList.add('active');
    }
  });
});
/* End Dropdown Toggle Functionality */

/* Start Sidebar Toggle Functionality */
toggleBtn.addEventListener('click', () => {
  if (window.innerWidth > 768) {
    // Desktop: Toggle between expanded and collapsed
    isCollapsed = !isCollapsed;
    sidebar.classList.toggle('collapsed', isCollapsed);
    content.classList.toggle('collapsed', isCollapsed);
    header.classList.toggle('collapsed', isCollapsed);
  } else {
    // Mobile: Toggle between hidden and expanded
    sidebar.classList.toggle('hidden');
    content.classList.toggle('full-width');
    header.classList.toggle('full-width');
    overlay.classList.toggle('active');
  }
});
/* End Sidebar Toggle Functionality */

/* Start Overlay Click Handler */
overlay.addEventListener('click', () => {
  sidebar.classList.add('hidden');
  content.classList.add('full-width');
  header.classList.add('full-width');
  overlay.classList.remove('active');
  // Close all submenus when closing sidebar
  document.querySelectorAll('.submenu').forEach(submenu => {
    submenu.classList.remove('show');
  });
  document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
    toggle.classList.remove('show');
  });
});
/* End Overlay Click Handler */

/* Start Window Resize Handler */
window.addEventListener('resize', () => {
  if (window.innerWidth <= 768) {
    sidebar.classList.add('hidden');
    content.classList.add('full-width');
    header.classList.add('full-width');
    overlay.classList.remove('active');
    isCollapsed = false;
    sidebar.classList.remove('collapsed');
    content.classList.remove('collapsed');
    header.classList.remove('collapsed');
  } else {
    sidebar.classList.remove('hidden');
    content.classList.remove('full-width');
    header.classList.remove('full-width');
    overlay.classList.remove('active');
    if (isCollapsed) {
      sidebar.classList.add('collapsed');
      content.classList.add('collapsed');
      header.classList.add('collapsed');
    } else {
      header.classList.remove('collapsed');
    }
  }
});
/* End Window Resize Handler */

/* Start Initial State Setup */
if (window.innerWidth <= 768) {
  sidebar.classList.add('hidden');
  content.classList.add('full-width');
  header.classList.add('full-width');
}
/* End Initial State Setup */

/* Start Icon Loading Debug */
document.querySelectorAll('.bi').forEach(icon => {
  if (!window.getComputedStyle(icon, ':before').content) {
    console.warn(`Icon ${icon.className} failed to load.`);
  }
});
/* End Icon Loading Debug */
// <!-- End JavaScript Section -->

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.multi-select-dropdown').forEach(dropdown => {

        const button = dropdown.querySelector('.multiSelectBtn');
        const selectAll = dropdown.querySelector('.select-all');
        const options = dropdown.querySelectorAll('.option-checkbox');
        const hiddenInput = dropdown.querySelector('.multiSelectValue');

        function updateDropdown() {
            const selected = [];

            options.forEach(opt => {
                if (opt.checked) selected.push(opt.value);
            });

            hiddenInput.value = selected.join(',');

            button.textContent = selected.length
                ? selected.join(', ')
                : 'Select Options';

            selectAll.checked = selected.length === options.length;
        }

        selectAll.addEventListener('change', () => {
            options.forEach(opt => opt.checked = selectAll.checked);
            updateDropdown();
        });

        options.forEach(opt => {
            opt.addEventListener('change', updateDropdown);
        });

    });

});
