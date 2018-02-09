/**
 * @module package/quiqqer/pricetable-bricks/bin/Controls/PriceTable
 * @author www.pcsg.de (Henning Leutz)
 */
define('package/quiqqer/pricetable-bricks/bin/Controls/PriceTable', [

    'qui/controls/elements/FormList',
    'utils/Controls',
    'Locale',
    'Mustache',

    'text!package/quiqqer/pricetable-bricks/bin/Controls/PriceTable.Entry.html',
    'css!package/quiqqer/pricetable-bricks/bin/Controls/PriceTable.css'

], function (QUIFormList, QUIControls, QUILocale, Mustache, EntryTemplate) {
    "use strict";

    var lg = 'quiqqer/pricetable';

    return new Class({

        Extends: QUIFormList,
        Type   : 'package/quiqqer/pricetable-bricks/bin/Controls/PriceTable',

        Binds: [
            '$onImport',
            '$onParsed'
        ],

        initialize: function (options) {
            this.parent(options);

            this.addEvents({
                onParsed : this.$onParsed,
                onImport  : this.$onImport
            });

            var html = Mustache.render(EntryTemplate, {});

            this.setAttributes({
                buttonText: 'neues Entry',
                entry: html
            });

        },

        $onImport: function () {
            this.parent();

            console.log(this.$Elm.getParent('tbody').getElement('input[name="feature-lines"]').value);
        },

        /**
         * Parses QUI controls when a new entry is created
         *
         * Fired after (inherited) FormList has parsed the content
         *
         * @param event
         * @param Element - The element that was previously parsed by (inherited) FormList
         */
        $onParsed: function (event, Element) {
            QUIControls.parse(Element).then(function () {
                // Element is fully parsed so we can finally show it
                Element.getElement('.quiqqe-pricetable-bricks-entry').show();
            });
        }
    });
});

