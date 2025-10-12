jQuery(document).ready(function($) {
    for(let el of document.querySelectorAll('.button-attribute,.swatch-attribute')){
    el.addEventListener('click', function(e){
      let target = e.target
      let container = target.closest('.swatches,.buttons');
      let value = target.getAttribute('data-value')
      let title = target.title || target.innerText || value
      let option = $(container).closest('.variation-row').find('select option').get().find(o => o.value === value || o.value === title)

      let select = option.closest('select')
      select.value = option.value
      $(select).trigger('change');
      $(container).closest('.variation-row').find('.selected-variation-name').text(title)

      $(target).parent().find('.button-attribute,.swatch-attribute').removeClass('selected')

      $(target).addClass('selected')

    })
  }

  // --- Product Gallery Slider ---
  // This function adds/removes slider arrows based on the number of images.
  function setupGallerySlider() {
    console.log('setting up gallery');
    var $gallery = $('.woocommerce-product-gallery');
    if (!$gallery.length) {
      return;
    }

    $gallery.find('.lj-gallery-nav').remove();

    // Use the gallery image wrappers as our slides, not FlexSlider thumbs
    var $images = $gallery.find('.woocommerce-product-gallery__image');

    if ($images.length > 1) {
      $gallery.append('<button class="lj-gallery-nav lj-prev" aria-label="Previous image">&lt;</button>');
      $gallery.append('<button class="lj-gallery-nav lj-next" aria-label="Next image">&gt;</button>');

      // Set initial state for the slider: ensure full width and show only the first image.
      $images.css('width', '100%').not(':first').hide();
      $images.first().show();
    }
  }

  $('.woocommerce-product-gallery__wrapper').height($('.woocommerce-product-gallery__wrapper *').height() + 'px')

  const galleryImages = $('.woocommerce-product-gallery .woocommerce-product-gallery__image img').get().map(img => {
    return $(img).data('large_image') || $(img).data('src');
  })

  console.log({galleryImages})

  $('body').on('click', '.lj-gallery-nav', function() {
    console.log('body click');
    var $button = $(this);
    var $gallery = $button.closest('.woocommerce-product-gallery');
    var $images = $gallery.find('.woocommerce-product-gallery__image');

    // If there's nothing to slide, do nothing.
    if ($images.length <= 1) {
      return;
    }

    // Find the currently visible image to determine the index.
    var $currentImage = $images.filter(':visible');
    if (!$currentImage.length) {
      // If nothing is visible (e.g., during a transition), default to the first image.
      $currentImage = $images.first();
    }
    var currentIndex = $images.index($currentImage);

    // Calculate the next index.
    if ($button.hasClass('lj-next')) {
      currentIndex = (currentIndex + 1) % $images.length;
    } else { // Previous button.
      currentIndex = (currentIndex - 1 + $images.length) % $images.length;
    }

    // Hide the current image and show the new one with a fade effect.
    $currentImage.stop().fadeOut(1000);

    // Get the next image container and the image inside it.
    var $nextImageContainer = $images.eq(currentIndex);
    var $nextImage = $nextImageContainer.find('img');

    // Get the URL for the large image from the data attribute.
    var largeImageUrl = $nextImage.data('large_image') || $nextImage.data('src');
    console.log({largeImageUrl}, $nextImage.attr('src'))

    // If the image src is not already the large version, update it.
    if (largeImageUrl && $nextImage.attr('src') !== largeImageUrl) {
      // To ensure the large image is displayed, we update the src
      // and remove the srcset which might contain smaller images.
      $nextImage.attr('src', largeImageUrl);
    //   $nextImage.attr('opacity', "100");
      $nextImage.attr('srcset', ''); // Clear srcset
    }
    $nextImageContainer.stop().fadeIn(0);
  });

  // Set up the slider on initial page load.
  setupGallerySlider();

  // Re-run setup after variation changes, as WooCommerce replaces the gallery markup.
  $('.variations_form').on('found_variation', function() {
    setTimeout(setupGallerySlider, 100); // A small delay is needed for the DOM to update.
  });

  $('.reset_variations').on('click', function() {
    setTimeout(setupGallerySlider, 100);
  });

});


















// tabs
jQuery(document).ready(function($) {

    // --- FINAL & Conflict-Free Accordion Logic ---

    // 1. Find the very first accordion item and its content.
    var keepOpen = $('.keep-open')[0];

    var $firstItem = keepOpen ? $('.luxury-jewels-tabs .lj-accordion-item.is-active') :  $('.luxury-jewels-tabs .lj-accordion-item:first');
    var $firstItemContent = $firstItem.find('.lj-accordion-content');

    // 2. Mark the first item as active and show its content by default.
    $firstItem.addClass('is-active');
    $firstItemContent.show();


    // 3. Handle the click event on any of the accordion titles.
    $('.luxury-jewels-tabs .lj-accordion-title').on('click', function() {
        var $clickedItem = $(this).closest('.lj-accordion-item');
        var $content = $clickedItem.find('.lj-accordion-content');


        console.log({keepOpen})

        // Check if the clicked item is already active.
        if ($clickedItem.hasClass('is-active')) {
            // If it is, close it.
            $content.slideUp(400);
            $clickedItem.removeClass('is-active');
        } else {
            // If it's not active:
            // First, close any other item that is currently open.
            if(!keepOpen){
              console.log('closing')
              $('.luxury-jewels-tabs .lj-accordion-item.is-active')
                .removeClass('is-active')
                .find('.lj-accordion-content').slideUp(400);

            }

            // Then, open the one that was clicked.
            $content.slideDown(400);
            $clickedItem.addClass('is-active');
        }
    });

});
