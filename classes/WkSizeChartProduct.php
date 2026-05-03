<?php
/**
* 2010-2019 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through this link for complete license : https://store.webkul.com/license.html
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to https://store.webkul.com/customisation-guidelines/ for more information.
*
*  @author    Webkul IN <support@webkul.com>
*  @copyright 2010-2019 Webkul IN
*  @license   https://store.webkul.com/license.html
*/

class WkSizeChartProduct extends ObjectModel
{
    public $id_product;
    public $id_size_chart;
    public $id_size_chart_filter;

    public static $definition = array(
        'table' => 'wk_size_chart_product',
        'primary' => 'id_size_chart_product',
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'size' => 10),
            'id_size_chart' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'size' => 10),
            'id_size_chart_filter' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'size' => 10),
        ),
    );

    public function getProductSizeChart($idProduct)
    {
        return Db::getInstance()->getRow(
            'SELECT * FROM `'._DB_PREFIX_.'wk_size_chart_product` WHERE `id_product` = '.(int)$idProduct
        );
    }
}
