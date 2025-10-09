jQuery(document).ready(function($) {
    $('.variations_form').each(function() {
        var $form = $(this);

        $form.on('click', '.swatch-attribute,.button-attribute', function(e) {
            e.preventDefault();
            console.log('clicked target');
            let target = e.target;
            window.target = target
            value = target.getAttribute('data-value')
            let attribute_name = target.closest('[data-attribute_name]').getAttribute('data-attribute_name')
            let select = document.querySelector(`select[data-attribute_name="${attribute_name}"]`)
            let container = target.closest('.swatches,.buttons');

            let variationName = $(container).closest('.variation-row').find('select option[value="' + value + '"]').text();

            $(container).closest('.variation-row').find('.selected-variation-name').text(variationName);
            jQuery(container).find('.swatch-attribute,.button-attribute').removeClass('selected');
            jQuery(target).addClass('selected');

            if(select){
                $(select).val(value).trigger('change');
            } else {
                console.log('oops')
            }
            console.log(target);


            // var $swatch = $(this);

            // var attributeName = $swatchContainer.data('attribute_name');
            // var value = e.target.getAttribute('title');
            // console.log({attributeName, value});

            // // Set the value in the hidden dropdown
            // $form.find('select[name="' + attributeName + '"]').val(value).trigger('change');

            // // Update selected class and variation name display
            // $swatchContainer.find('.swatch').removeClass('selected');
            // $swatch.addClass('selected');

            // var variationName = $swatch.closest('.variation-row').find('select[name="' + attributeName + '"] option:selected').text();
            // $swatch.closest('.variation-row').find('.selected-variation-name').text(variationName);
            // console.log({variationName});
        });

        // Update swatch selection when variation is found
        // $form.on('found_variation', function(event, variation) {
        //     for (var attribute in variation.attributes) {
        //         var value = variation.attributes[attribute];
        //         var $swatchContainer = $form.find('.swatches[data-attribute_name="' + attribute + '"],.buttons[data-attribute_name="' + attribute + '"]');
        //         console.log({variation, attribute, value})
        //         if (value) {
        //             $swatchContainer.find('.swatch-attribute[data-value="' + value + '"],.button-attribute[data-value="' + value + '"]').addClass('selected');
        //             var variationName = $swatchContainer.closest('.variation-row').find('select option[value="' + value + '"]').text();
        //             $swatchContainer.closest('.variation-row').find('.selected-variation-name').text(variationName);
        //         }
        //     }
        // });

        // Clear selection display on reset
        $form.on('reset_data', function() {
            $(this).find('.swatch').removeClass('selected');
            $(this).find('.selected-variation-name').text('');
        });
    });
});


















// tabs
jQuery(document).ready(function($) {

    // --- FINAL & Conflict-Free Accordion Logic ---

    // 1. Find the very first accordion item and its content.
    var $firstItem = $('.luxury-jewels-tabs .lj-accordion-item:first');
    var $firstItemContent = $firstItem.find('.lj-accordion-content');

    // 2. Mark the first item as active and show its content by default.
    $firstItem.addClass('is-active');
    $firstItemContent.show();


    // 3. Handle the click event on any of the accordion titles.
    $('.luxury-jewels-tabs .lj-accordion-title').on('click', function() {
        var $clickedItem = $(this).closest('.lj-accordion-item');
        var $content = $clickedItem.find('.lj-accordion-content');

        // Check if the clicked item is already active.
        if ($clickedItem.hasClass('is-active')) {
            // If it is, close it.
            $content.slideUp(400);
            $clickedItem.removeClass('is-active');
        } else {
            // If it's not active:
            // First, close any other item that is currently open.
            $('.luxury-jewels-tabs .lj-accordion-item.is-active')
                .removeClass('is-active')
                .find('.lj-accordion-content').slideUp(400);

            // Then, open the one that was clicked.
            $content.slideDown(400);
            $clickedItem.addClass('is-active');
        }
    });

});