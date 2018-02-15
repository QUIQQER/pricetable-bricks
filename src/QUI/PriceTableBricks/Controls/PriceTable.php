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
            'inherit-template-colors' => false,
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
        $Engine  = QUI::getTemplateManager()->getEngine();
        $entries = $this->getAttribute('entries');

        if (is_string($entries)) {
            $entries = json_decode($entries, true);
        }

        foreach ($entries as $key => $value) {
            $entries[$key]['features'] = json_decode($entries[$key]['features'], true);
        }

        switch ($this->getAttribute('display')) {
            case 'default':
            default:
                $css      = dirname(__FILE__) . '/PriceTable.Default.css';
                $template = dirname(__FILE__) . '/PriceTable.Default.html';
                break;
        }

        $noInheritTemplateColors = '';
        if (!$this->getAttribute('inherit-template-colors')) {
            $noInheritTemplateColors = 'no-inherit-template-colors';
        }

        $Engine->assign(array(
            'this'                    => $this,
            'entries'                 => $entries,
            'noInheritTemplateColors' => $noInheritTemplateColors
        ));

        $this->addCSSFile($css);

        return $Engine->fetch($template);
    }

    public function setTestData()
    {

        $entries = array(
            0 => array(
                'highlight' => false,
                'title'     => 'Basic',
                'subtitle'  => 'Perfect for small companies',
                'price'     => '25,99€',
                'srp'       => '39,99€',
                'priceinfo' => 'pro Monat',
                'img'       => '',
                'url'       => '#',
                'features'  => '{"1":"Nulla porttitor accumsan","2":"Vestibulum ac diam sit amet quam", "3":"Lorem ipsum dolor"}'
            ),
            1 => array(
                'highlight' => true,
                'title'     => 'Standard',
                'subtitle'  => 'Perfect for medium budget',
                'price'     => '99,99 €',
                'srp'       => '',
                'priceinfo' => 'pro Monat',
                'img'       => '',
                'url'       => '#',
                'features'  => '{"1":"Quisque velit nisi","2":"Curabitur aliquet", "3":"Vestibulum ante ipsum"}'
            ),
            2 => array(
                'highlight' => false,
                'title'    => 'Advanced',
                'subtitle' => 'Perfect for large budget',
                'price'    => '500 €',
                'srp'      => '501 €',
                'priceinfo' => 'pro Monat',
                'img'      => '',
                'url'      => '#',
                'features' => '{"1":"Curabitur arcu erat","2":"Quisque velit nisi", "3":"Donec sollicitudin"}'
            )
        );

        $entries = json_encode($entries);

        $this->setAttribute('entries', $entries);
    }

    /**
     * @return mixed|QUI\Projects\Site
     * @throws QUI\Exception
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
