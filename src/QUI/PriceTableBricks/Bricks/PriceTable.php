<?php

/**
 * This file contains QUI\PriceTableBricks\Bricks\PriceTable
 */

namespace QUI\PriceTableBricks\Bricks;

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
     */
    public function getBody()
    {
        $Control = new QUI\PriceTableBricks\Controls\PriceTable(array(
            'inherit-template-colors' => $this->getAttribute('inherit-template-colors'),
            'entries'                 => $this->getAttribute('entries'),
            'display'                 => $this->getAttribute('pricetable.display'),
            'content'                 => $this->getAttribute('content'),
            'frontendTitle'           => $this->getAttribute('frontendTitle'),
            'showTitle'               => $this->getAttribute('showTitle')
        ));

        return $Control->create();
    }
}
