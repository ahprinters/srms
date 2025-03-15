document.addEventListener('DOMContentLoaded', function() {
    console.log('Custom sidebar JS loaded');
    
    // Get all submenu parent items
    const hasChildren = document.querySelectorAll('.has-children');
    console.log('Found submenu items:', hasChildren.length);
    
    // Add click event to each submenu parent
    hasChildren.forEach(item => {
        const link = item.querySelector('a');
        const childNav = item.querySelector('.child-nav');
        
        link.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Submenu clicked:', this.textContent);
            
            // Toggle open class on parent
            item.classList.toggle('open');
            
            // Toggle child-nav display
            if (childNav) {
                if (childNav.style.display === 'block') {
                    childNav.style.display = 'none';
                } else {
                    childNav.style.display = 'block';
                }
            }
        });
    });
});