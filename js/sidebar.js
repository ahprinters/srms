document.addEventListener('DOMContentLoaded', function() {
    console.log('Sidebar JS loaded');
    
    // Get all submenu parent items
    const hasSubmenuItems = document.querySelectorAll('.has-submenu');
    console.log('Found submenu items:', hasSubmenuItems.length);
    
    // Add click event to each submenu parent
    hasSubmenuItems.forEach(item => {
        const link = item.querySelector('a');
        
        link.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Submenu clicked:', this.textContent);
            
            // Get the submenu
            const submenu = this.parentNode.querySelector('.submenu');
            
            // Toggle submenu display
            if (submenu) {
                if (submenu.style.display === 'block') {
                    submenu.style.display = 'none';
                    item.classList.remove('active');
                } else {
                    submenu.style.display = 'block';
                    item.classList.add('active');
                }
            }
        });
    });
    
    // Toggle sidebar if button exists
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    }
});