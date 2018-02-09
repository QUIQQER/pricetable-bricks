<?php

/**
 * This file contains QUI\PriceTableBricks\Controls\PriceTable
 */

namespace QUI\PriceTableBricks\Controls;

use QUI;
use QUI\Control;

/**
 * Price table class
 *
 * @author  www.pcsg.de (Michael Danielczok)
 * @package quiqqer/pricetable-bricks
 */
class PriceTable extends Control
{
    /**
     * PriceTable constructor.
     *
     * @param array $params
     */
    public function __construct($params = array())
    {
        // default options
        $this->setAttributes(array(
            'class'                   => 'qui-pricetable',
            'nodeName'                => 'section',
            'inherit-template-colors' => true,
            'entries'                 => array(),
            'display'                 => 'default'
        ));

        parent::__construct($params);
    }

    /**
     * @return string
     *
     * @throws QUI\Exception
     */
    public function getBody()
    {
        $this->testData();

        $Engine  = QUI::getTemplateManager()->getEngine();
        $entries = $this->getAttribute('entries');
        $siteLang    = $this->getSite()->getProject()->getLang();

        if (is_string($entries)) {
            $entries = json_decode($entries, true);
        }

        switch ($this->getAttribute('display')) {
            case 'default':
            default:
                $css      = dirname(__FILE__) . '/PriceTable.Default.css';
                $template = dirname(__FILE__) . '/PriceTable.Default.html';
                break;
        }

        if (!$this->getAttribute('inherit-template-colors')) {
            $noInheritTemplateColors = 'no-inherit-template-colors';
        }

        $Engine->assign(array(
            'this'                    => $this,
            'entries'                 => $entries,
            'noInheritTemplateColors' => 'no-inherit-template-colors'
        ));

        $this->addCSSFile($css);

        return $Engine->fetch($template);
    }

    public function testData()
    {

        $entries = array(
            0 => array(
                'title'    => 'Super Paket',
                'subtitle' => 'Billig aber gut',
                'price'    => '25,99',
                'srp'      => '39,99',
                'img'      => '',
                'url'      => '#',
                'features' => '[{"1":"Nulla porttitor accumsan","2":"Vestibulum ac diam sit amet quam", "3":"Lorem ipsum dolor"}]'
            ),
            1 => array(
                'title'    => 'Mega',
                'subtitle' => 'Lohnt sich',
                'price'    => '99,99',
                'srp'      => '',
                'img'      => '',
                'url'      => '#',
                'features' => '[{"1":"Quisque velit nisi","2":"Curabitur aliquet", "3":"Vestibulum ante ipsum"}]'
            ),
            2 => array(
                'title'    => 'Lupsim lerum',
                'subtitle' => 'Donec sollicitudin',
                'price'    => '500',
                'srp'      => '501',
                'img'      => '',
                'url'      => '#',
                'features' => '[{"1":"Curabitur arcu erat","2":"Quisque velit nisi", "3":"Donec sollicitudin"}]'
            )
        );

        $entries = '[{"title":"Basic","subtitle":"Perfect for small companies","price":"29,99","srp":"39,99","priceinfo":"","img":"","url":"#","highlight":"0","features":{"1":"Nulla porttitor accumsan","2":"Vestibulum ac diam sit amet quam", "3":"Lorem ipsum dolor"}},';
        $entries .= '{"title":"Standard","subtitle":"Perfect for medium budget","price":"99,99","srp":"","priceinfo":"pro Monat","img":"","url":"#","highlight":"1","features":{"1":"Quisque velit nisi","2":"Curabitur aliquet", "3":"Vestibulum ante ipsum"}},';
        $entries .= '{"title":"Advanced","subtitle":"Perfect for large budget","price":"500","srp":"501","priceinfo":"pro Monat","img":"","url":"#","highlight":"0","features":{"1":"Curabitur arcu erat","2":"Quisque velit nisi", "3":"Donec sollicitudin"}},';
        $entries .= '{"title":"Advanced","subtitle":"Perfect for large budget","price":"500","srp":"501","priceinfo":"pro Monat","img":"","url":"#","highlight":"0","features":{"1":"Curabitur arcu erat","2":"Quisque velit nisi", "3":"Donec sollicitudin"}}]';


        $this->setAttribute('entries', $entries);
    }

    /**
     * @return mixed|QUI\Projects\Site
     */
    protected function getSite()
    {
        if ($this->getAttribute('Site')) {
            return $this->getAttribute('Site');
        }

        $Site = QUI::getRewrite()->getSite();

        $this->setAttribute('Site', $Site);

        return $Site;
    }
}
