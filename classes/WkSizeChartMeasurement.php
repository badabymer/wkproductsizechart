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

class WkSizeChartMeasurement extends ObjectModel
{
    public $id_size_chart_attribute;
    public $id_measurement;
    public $value;

    public static $definition = array(
        'table' => 'wk_size_chart_measurement',
        'primary' => 'id_size_chart_measurement',
        'multilang' => false,
        'fields' => array(
            'id_size_chart_attribute' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId',
            'size' => 10, 'required' => true),
            'id_measurement' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId',
            'size' => 10, 'required' => true),
            'value' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 32),
        ),
    );

    public function getMeasurementById($idSizeChartAttribute, $idMeasurement)
    {
        if ($idSizeChartAttribute && $idMeasurement) {
            $result = Db::getInstance()->getRow(
                'SELECT * FROM `'._DB_PREFIX_.'wk_size_chart_measurement`
                WHERE `id_size_chart_attribute` = '.(int)$idSizeChartAttribute .
                ' AND `id_measurement` = '.(int)$idMeasurement
            );
            if ($result) {
                $result['id_attribute'] = Db::getInstance()->getValue(
                    'SELECT `id_attribute` FROM `'._DB_PREFIX_.'wk_size_chart_attribute`
                    WHERE `id_size_chart_attribute` = '.(int)$idSizeChartAttribute
                );
            }
            return $result;
        }
        return false;
    }

    public function getMeasurementName($idMeasurement, $idLang)
    {
        if ($idMeasurement && $idLang) {
            return Db::getInstance()->getValue(
                'SELECT `name` FROM `'._DB_PREFIX_.'wk_measurement_lang`
                WHERE `id_lang` = '.(int)$idLang.' AND `id_measurement` = '.(int)$idMeasurement
            );
        }
        return false;
    }
}
