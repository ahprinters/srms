document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Handle submenu toggles
    const submenuToggles = document.querySelectorAll('.has-submenu > a');
    
    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const parent = this.parentElement;
            const submenu = parent.querySelector('.submenu');
            
            // Close other open submenus when opening a new one
            if (!parent.classList.contains('open')) {
                document.querySelectorAll('.has-submenu.open').forEach(function(openMenu) {
                    if (openMenu !== parent) {
                        openMenu.classList.remove('open');
                        const openSubmenu = openMenu.querySelector('.submenu');
                        if (openSubmenu) {
                            openSubmenu.style.maxHeight = null;
                        }
                    }
                });
            }
            
            // Toggle current submenu
            parent.classList.toggle('open');
            
            // Use direct style manipulation for better compatibility
            if (submenu) {
                if (submenu.style.maxHeight) {
                    submenu.style.maxHeight = null;
                } else {
                    submenu.style.maxHeight = submenu.scrollHeight + "px";
                }
            }
        });
    });
    
    // Collapse All button functionality
    const collapseAllBtn = document.getElementById('collapseAll');
    if (collapseAllBtn) {
        collapseAllBtn.addEventListener('click', function() {
            const openMenus = document.querySelectorAll('.has-submenu.open');
            
            if (openMenus.length > 0) {
                // If any menu is open, close all
                openMenus.forEach(function(menu) {
                    menu.classList.remove('open');
                    const submenu = menu.querySelector('.submenu');
                    if (submenu) {
                        submenu.style.maxHeight = null;
                    }
                });
                this.innerHTML = '<i class="fas fa-expand-alt me-2"></i> Expand Menu';
            } else {
                // If all menus are closed, open all
                document.querySelectorAll('.has-submenu').forEach(function(menu) {
                    menu.classList.add('open');
                    const submenu = menu.querySelector('.submenu');
                    if (submenu) {
                        submenu.style.maxHeight = submenu.scrollHeight + "px";
                    }
                });
                this.innerHTML = '<i class="fas fa-compress-alt me-2"></i> Collapse Menu';
            }
        });
    }
    
    // Make submenu links directly clickable
    const submenuLinks = document.querySelectorAll('.submenu li a');
    submenuLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Allow default navigation behavior
            // Just stop propagation to parent elements
            e.stopPropagation();
        });
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        }
    });
    
    // Automatically open submenu if a child is active
    const activeLink = document.querySelector('.submenu a.active');
    if (activeLink) {
        let parent = activeLink.parentElement;
        while (parent) {
            if (parent.classList.contains('has-submenu')) {
                parent.classList.add('open');
                const submenu = parent.querySelector('.submenu');
                if (submenu) {
                    submenu.style.maxHeight = submenu.scrollHeight + "px";
                }
            }
            parent = parent.parentElement;
        }
    }
});