/**
 * Created by Geo on 24.04.2017.
 */
$(document).ready(function() {

    var options = {
        ovalWidth: 300,
        ovalHeight: 40,
        offsetX: 45,
        offsetY: 210,
        angle: 0,
        activeItem: 0,
        duration: 350,
        className: 'item'
    }

    carousel = $('.carousel').CircularCarousel(options);

    /* Fires when an item is about to start it's activate animation */
    carousel.on('itemBeforeActive', function(e, item) {
        $(item).css('box-shadow', '0 0 20px');
    });

    /* Fires after an item finishes it's activate animation */
    carousel.on('itemActive', function(e, item) {
        $(item).css('box-shadow', '0 0 20px');
    });

    /* Fires when an active item starts it's de-activate animation */
    carousel.on('itemBeforeDeactivate', function(e, item) {
        $(item).css('box-shadow', '0 0 20px');
    })

    /* Fires after an active item has finished it's de-activate animation */
    carousel.on('itemAfterDeactivate', function(e, item) {
        $(item).css('box-shadow', '');
    })


    /* Previous Entry */
    $('.controls .previous').click(function(e) {
        carousel.cycleActive('previous');
        e.preventDefault();
    });

    /* Next button */
    $('.controls .next').click(function(e) {
        carousel.cycleActive('next');
        e.preventDefault();
    });

    /* Manaully click an item anywhere in the carousel */
    $('.carousel .item').click(function(e) {
        var index = $(this).index('li');
        carousel.cycleActiveTo(index);
        e.preventDefault();
    });

});