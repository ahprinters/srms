// Add this to your existing JavaScript file
$(document).ready(function() {
    // Remove duplicate arrows from sidebar menu
    $('.side-nav .has-children > a i.fas.fa-chevron-right').remove();
    $('.side-nav .has-children > a i.fa-chevron-right.ms-auto').remove();
});