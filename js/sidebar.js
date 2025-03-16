$(document).ready(function() {
    console.log('Sidebar JS loaded');
    
    // Toggle submenu
    $('.sidebar-menu .has-submenu > a').on('click', function(e) {
        e.preventDefault();
        console.log('Submenu clicked');
        
        var parent = $(this).parent();
        parent.toggleClass('open');
        
        var submenu = $(this).next('.submenu');
        if (parent.hasClass('open')) {
            submenu.slideDown(300);
        } else {
            submenu.slideUp(300);
        }
    });
    
    // Collapse all menu
    $('#collapseAll').on('click', function() {
        $('.has-submenu.open').removeClass('open');
        $('.submenu').slideUp(300);
    });
    
    // Auto-highlight current page
    var currentPath = window.location.pathname;
    console.log('Current path:', currentPath);
    
    // Highlight active menu item
    $('.sidebar-menu a').each(function() {
        var href = $(this).attr('href');
        if (href && currentPath.indexOf(href.split('/').pop()) !== -1) {
            $(this).addClass('active');
            
            // If it's in a submenu, open the parent
            var parentMenu = $(this).closest('.has-submenu');
            if (parentMenu.length) {
                parentMenu.addClass('open');
                parentMenu.find('.submenu').slideDown(300);
            }
        }
    });
});