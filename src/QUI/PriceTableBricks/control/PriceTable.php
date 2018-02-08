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
            'class'   => 'qui-pricetable',
            'entries' => array(),
            'display' => 'default'
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

        switch ($this->getAttribute('display')) {
            case 'default':
            default:
                $css      = dirname(__FILE__) . '/PriceTable.Default.css';
                $template = dirname(__FILE__) . '/PriceTable.Default.html';
                break;
        }

        $Engine->assign(array(
            'this'    => $this,
            'entries' => $entries
        ));

        $this->addCSSFile(dirname(__FILE__) . $css);

        return $Engine->fetch(dirname(__FILE__) . $template);
    }

    public function testData()
    {

        $entries = array(
            1 => array(
                'title'    => 'Super Paket',
                'subtitle' => 'Man muss ihn kaufen',
                'price'    => '25,99',
                'srp'      => '39,99',
                'url'      => '#'
            )
        );

        $this->setAttribute('entries', $entries);
    }
}
