defer(function() {
    jQuery(document).ready(function($) {
        jQuery(document).on('click', 'span.payform-price', function() {
            setPriceFromElement(jQuery(this));

            if (jQuery(formContainer) !== undefined) {
                jQuery([document.documentElement, document.body]).animate({
                    scrollTop: jQuery(formContainer).offset().top
                }, 500);
            }
        });

        setPriceFromElement(jQuery(document).find('span.payform-price').eq(0));

        console.log('Payform loaded in "' + formContainer + '" context!');
    });
});

function defer(method) {
    if (window.jQuery) {
        method();
    } else {
        setTimeout(function() {
            defer(method)
        }, 50);
    }
}

function setPriceFromElement(element) {
    var pagePrice = parseFloat(element.text().replace(/([^\d\.])/ig, ''));

    if (!isNaN(pagePrice)) {
        jQuery(document).find(formContainer).find('input[name=amount]').val(pagePrice);

        console.log('Set price ' + pagePrice + ' to payform in "' + formContainer + '" context!');
    }
}