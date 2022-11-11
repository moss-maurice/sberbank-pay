class TransactionsTabScripts {
    constructor() {
        this.initActionButtons();
        this.initStatusButtons();
        this.initAmountEditButtons();
        this.initPagination();
    }

    initActionButtons() {
        jQuery(document)
            .on('click', '.btn.tr-action-buttons', function(e) {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    id: jQuery(this).attr('rel-id'),
                    page: jQuery(this).attr('rel-page'),
                };

                moduleObject.getTabContent(pageDomObject, tabName, methodName, data);
            });
    }

    initStatusButtons() {
        jQuery(document)
            .on('click', '.btn.tr-status-buttons', function(e) {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    id: jQuery(this).attr('rel-id'),
                    page: jQuery(this).attr('rel-page'),
                };

                moduleObject.getTabContent(pageDomObject, tabName, methodName, data);
            });
    }

    initAmountEditButtons() {
        jQuery(document)
            .on('click', '.btn.tr-edit-total-amount-button', function(e) {
                jQuery(this).closest('td').find('.public-block').addClass('hide');
                jQuery(this).closest('td').find('.hidden-block').removeClass('hide');
            })
            .on('click', '.btn.tr-save-total-amount-button', function(e) {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    id: jQuery(this).attr('rel-id'),
                    page: jQuery(this).attr('rel-page'),
                    totalAmount: jQuery(this).closest('td').find('.hidden-block').find('input[type=number]').val(),
                };

                moduleObject.getTabContent(pageDomObject, tabName, methodName, data);

                jQuery(this).closest('td').find('.public-block').removeClass('hide');
                jQuery(this).closest('td').find('.hidden-block').addClass('hide');
            });
    }

    initPagination() {
        jQuery(document)
            .on('click', '.btn.tr-pagination-buttons', function(e) {
                if (jQuery(this).hasClass('btn-success')) {
                    e.preventDefault();

                    return false;
                }

                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    page: jQuery(this).attr('rel-page'),
                };

                moduleObject.getTabContent(pageDomObject, tabName, methodName, data);
            });
    }
}