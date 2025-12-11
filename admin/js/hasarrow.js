$(document).ready(function() {
    $('.has-arrow > a').on('click', function(e) {
        e.preventDefault(); // Prevent the default anchor click behavior
        $(this).parent().toggleClass('active'); // Toggle the active class on the parent li
        $(this).siblings('ul').slideToggle(); // Toggle the visibility of the submenu
    });
});