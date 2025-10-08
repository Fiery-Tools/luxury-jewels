jQuery(document).ready(function($) {
    $('.variations_form').each(function() {
        var $form = $(this);

        $form.on('click', '.swatch', function(e) {
            e.preventDefault();
            console.log('clicked swatch');
            window.target = e.target;
            console.log(e.target);


            var $swatch = $(this);
            var $swatchContainer = $swatch.closest('.swatches');
            var attributeName = $swatchContainer.data('attribute_name');
            var value = e.target.getAttribute('title');
            console.log({attributeName, value});

            // Set the value in the hidden dropdown
            $form.find('select[name="' + attributeName + '"]').val(value).trigger('change');

            // Update selected class and variation name display
            $swatchContainer.find('.swatch').removeClass('selected');
            $swatch.addClass('selected');

            var variationName = $swatch.closest('.variation-row').find('select[name="' + attributeName + '"] option:selected').text();
            $swatch.closest('.variation-row').find('.selected-variation-name').text(variationName);
            console.log({variationName});
        });

        // Update swatch selection when variation is found
        $form.on('found_variation', function(event, variation) {
            for (var attribute in variation.attributes) {
                var value = variation.attributes[attribute];
                var $swatchContainer = $form.find('.swatches[data-attribute_name="' + attribute + '"]');

                if (value) {
                    $swatchContainer.find('.swatch[data-value="' + value + '"]').addClass('selected');
                    var variationName = $swatchContainer.closest('.variation-row').find('select option[value="' + value + '"]').text();
                    $swatchContainer.closest('.variation-row').find('.selected-variation-name').text(variationName);
                }
            }
        });

        // Clear selection display on reset
        $form.on('reset_data', function() {
            $(this).find('.swatch').removeClass('selected');
            $(this).find('.selected-variation-name').text('');
        });
    });
});