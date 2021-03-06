/**
 * @module package/quiqqer/pricetable-bricks/bin/Controls/PriceTable
 * @author www.pcsg.de (Henning Leutz)
 */
define('package/quiqqer/pricetable-bricks/bin/Controls/PriceTable', [

    'qui/controls/elements/FormList',
    'utils/Controls',
    'Locale',
    'Mustache',
    'qui/controls/windows/Confirm',
    'qui/utils/Form',

    'text!package/quiqqer/pricetable-bricks/bin/Controls/PriceTable.Entry.html',
    'css!package/quiqqer/pricetable-bricks/bin/Controls/PriceTable.css'

], function (
    QUIFormList,
    QUIControls,
    QUILocale,
    Mustache,
    QUIConfirm,
    QUIFormUtils,
    EntryTemplate
) {
    "use strict";

    var lg = 'quiqqer/pricetable-bricks';

    return new Class({

        Extends: QUIFormList,
        Type   : 'package/quiqqer/pricetable-bricks/bin/Controls/PriceTable',

        Binds: [
            '$onImport',
            'createEntry',
            '$onParsed',
            '$openFeatures',
            '$getFeaturesData',
            '$renderFeatures',
            '$saveFeaturesData',
            '$updateFeaturesList'
        ],

        initialize: function (options) {
            this.parent(options);

            this.addEvents({
                onParsed: this.$onParsed
            });

            var html = Mustache.render(EntryTemplate, {
                'highlight'     : QUILocale.get(lg, 'bricks.pricetable.entry.highlight'),
                'title'         : QUILocale.get(lg, 'bricks.pricetable.entry.entryTitle'),
                'subtitle'      : QUILocale.get(lg, 'bricks.pricetable.entry.subtitle'),
                'image'         : QUILocale.get(lg, 'bricks.pricetable.entry.image'),
                'price'         : QUILocale.get(lg, 'bricks.pricetable.entry.price'),
                'srp'           : QUILocale.get(lg, 'bricks.pricetable.entry.srp'),
                'priceInfo'     : QUILocale.get(lg, 'bricks.pricetable.entry.priceInfo'),
                'url'           : QUILocale.get(lg, 'bricks.pricetable.entry.url'),
                'featuresTitle' : QUILocale.get(lg, 'bricks.pricetable.entry.featuresTitle'),
                'featuresButton': QUILocale.get(lg, 'bricks.pricetable.entry.featuresButton')
            });

            this.setAttributes({
                buttonText: QUILocale.get(lg, 'bricks.pricetable.addEntry'),
                entry     : html
            });
        },

        /**
         * event: on import
         */
        $onImport: function () {
            // look if some value exist
            var value = this.getElm().value;

            // fix title
            if (value !== '') {
                this.$onImportParseInput(value);
            }

            this.parent();

            this.$Elm.getElement('button').addEvent('click', function () {
                this.createEntry();
            }.bind(this));

            this.createEntry();


            this.HiddenInputs = this.$Elm.getElements('input[name="features"]');

            this.HiddenInputs.each(function (HiddenInput) {
                this.$updateFeaturesList(HiddenInput);
            }.bind(this));
        },

        /**
         * Fix title
         * https://dev.quiqqer.com/quiqqer/package-bricks/issues/97
         *
         * @param value | string
         */
        $onImportParseInput: function (value) {
            if (!value) {
                return;
            }
            
            value = JSON.decode(value);

            if (typeOf(value) !== 'array') {
                return;
            }

            for (var i = 0, len = value.length; i < len; i++) {
                if (typeof value[i].title !== 'undefined') {
                    value[i].priceTableTitle = value[i].title;
                }
            }

            this.getElm().value = JSON.encode(value);
        },

        /**
         * create new entry with input fields
         */
        createEntry: function () {
            this.FeaturesBtn = this.$Elm.getElements('.features-button');
            this.maxFeatures = this.$Elm.getParent('tbody').getElement('input[name="feature-lines"]').value;

            this.FeaturesBtn.addEvent('click', this.$openFeatures);
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
                Element.getElement('.quiqqer-pricetable-bricks-entry').show();
            });
        },

        /**
         * Create and open the features popup
         *
         * @param event
         */
        $openFeatures: function (event) {
            var Target = event.target;
            var HiddenInput = Target.getParent('label').getElement('.features-input');

            this.maxFeatures = this.$Elm.getParent('tbody').getElement('input[name="feature-lines"]').value;

            var self = this;

            new QUIConfirm({
                class             : 'features-popup',
                maxWidth          : 500,
                maxHeight         : 400,
                titleicon         : false,
                icon              : false,
                title             : QUILocale.get(lg, 'bricks.pricetable.entry.featuresPopup.title'),
                autoclose         : false,
                backgroundClosable: false,
                titleCloseButton  : true,
                events            : {
                    onOpen : function (Win) {
                        var Content = Win.getContent(),
                            data    = self.$getFeaturesData(HiddenInput);

                        Content.set({
                            html: '<header>' +
                            QUILocale.get(lg, 'bricks.pricetable.featureLines') + ': ' + self.maxFeaturs +
                            '</header>'
                        });

                        self.$renderFeatures(Content, data);
                    },
                    onClose: function () {
                        // needed because of chrome render bug
                        document.body.setStyle('transform', 'translateZ(0)');

                        (function () {
                            document.body.setStyle('transform', null);
                        }).delay(100);
                    },

                    onSubmit: function (Win) {
                        self.$saveFeaturesData(Win, HiddenInput);
                        self.$updateFeaturesList(HiddenInput);
                        Win.close();
                    }
                }
            }).open();
        },

        /**
         * Get the saved data from given HTML Object
         *
         * @param HiddenInput
         * @returns {*}
         */
        $getFeaturesData: function (HiddenInput) {
            var data = HiddenInput.value;

            return data ? JSON.parse(data) : data;
        },

        /**
         * Render inputs of the features popup.
         * Number of inputs is depend on maxFeatures
         *
         * @param Content
         * @param data
         */
        $renderFeatures: function (Content, data) {
            var Container = new Element('div', {
                'class': 'popup-features-container'
            });

            for (var i = 0; i < this.maxFeatures; i++) {
                new Element('input', {
                    'class'    : 'popup-features-input',
                    'type'     : 'text',
                    'data-json': data[i],
                    'value'    : data[i]
                }).inject(Container);
            }

            Container.inject(Content);
        },

        /**
         * Save the features in a hidden input
         *
         * @param Win
         * @param HiddenInput
         */
        $saveFeaturesData: function (Win, HiddenInput) {
            var Content    = Win.getContent(),
                inputs     = Content.getElements('input'),
                jsonObject = {};

            inputs.each(function (input, key) {
                if (input.value) {
                    jsonObject[key] = input.value;
                }
            });

            /*var jsonString = JSON.stringify(jsonObject);

            if (jsonString.length < 3) {
                jsonString = '';
            }*/

            HiddenInput.value = JSON.encode(jsonObject);

            // need to save the data after click in "save" button
            this.$refreshData();
        },

        /**
         * Update the list with features (after closing the features popup)
         *
         * @param HiddenInput
         */
        $updateFeaturesList: function (HiddenInput) {
            var List = HiddenInput.getParent('label').getElement('ul'),
                Data = this.$getFeaturesData(HiddenInput);


            // todo kann man besser machen, als die Liste löschen und neue li Elemente erstellen
            List.set('html', '');

            Object.each(Data, function (value) {
                new Element('li', {
                    html: value
                }).inject(List);
            });
        }
    });
});
