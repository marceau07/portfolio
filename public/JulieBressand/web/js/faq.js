// Accordion
var action = 'click';
var speed = "500";

$(document).ready(function () {
    // Question handler
    $('li.q').on(action, function () {
        // Get next element
        $(this).next()
                .slideToggle(speed)
                .siblings('li.a')
                .slideUp();
        // Get arrow for active question
        var arrow = $(this).children('.fa');
        // Remove the 'rotate' class for all images except the active.
        $('.fa').not(arrow).removeClass('rotate');
        // Toggle rotate class
        arrow.toggleClass('rotate');
    });
});