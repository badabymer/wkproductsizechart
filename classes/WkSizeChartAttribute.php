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

class WkSizeChartAttribute extends ObjectModel
{
    public $id_size_chart;
    public $id_attribute;
    public $is_custom;

    public static $definition = array(
        'table' => 'wk_size_chart_attribute',
        'primary' => 'id_size_chart_attribute',
        'multilang' => false,
        'fields' => array(
            'id_size_chart' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'size' => 10,
            'required' => true),
            'id_attribute' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'size' => 10,
            'required' => true),
            'is_custom' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
        ),
    );

    public function getAttributeName($isCustom, $idAttribute, $idLang)
    {
        if ($isCustom) {
            return Db::getInstance()->getValue(
                'SELECT `name` FROM `'._DB_PREFIX_.'wk_custom_attribute_lang`
                WHERE `id_lang` = '.(int)$idLang.' AND `id_custom_attribute` = '.(int)$idAttribute
            );
        } else {
            return Db::getInstance()->getValue(
                'SELECT `name` FROM `'._DB_PREFIX_.'attribute_lang`
                WHERE `id_lang` = '.(int)$idLang.' AND `id_attribute` = '.(int)$idAttribute
            );
        }
    }

    public function getSizeChartAttribute($idSizeChart)
    {
        if ($idSizeChart) {
            return Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'wk_size_chart_attribute`
                WHERE `id_size_chart` = '.(int)$idSizeChart
            );
        }
        return false;
    }

    public function getSizeChartMeasurement($idSizeChartAttribute)
    {
        if ($idSizeChartAttribute) {
            return Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'wk_size_chart_measurement`
                WHERE `id_size_chart_attribute` = '.(int)$idSizeChartAttribute
            );
        }
        return false;
    }

    public function getCustomId($idLang, $name)
    {
        return Db::getInstance()->getValue(
            'SELECT `id_custom_attribute` From `'._DB_PREFIX_.'wk_custom_attribute_lang`
            WHERE `id_lang` = '. (int)$idLang. ' AND `name` = "'.pSQL($name).'"'
        );
    }

    public function getMaxIdCustom()
    {
        return Db::getInstance()->getValue(
            'SELECT max(`id_custom_attribute`) From `'._DB_PREFIX_.'wk_custom_attribute_lang`'
        );
    }

    public function insertCustomAttribute($idCustom, $idLang, $name)
    {
        if ($idCustom && $idLang) {
            return Db::getInstance()->insert('wk_custom_attribute_lang', array(
                'id_custom_attribute' => (int)$idCustom,
                'id_lang' => (int)$idLang,
                'name' => pSQL($name)
            ));
        }
        return false;
    }

    public function updateCustomAttribute($idCustom, $idLang, $name)
    {
        if ($idCustom && $idLang) {
            return Db::getInstance()->update(
                'wk_custom_attribute_lang',
                array(
                    'name' => pSQL($name)
                ),
                'id_custom_attribute =' .(int)$idCustom. ' AND id_lang = ' .(int)$idLang
            );
        }
        return false;
    }

    public function getMeasurementId($idLang, $name)
    {
        return Db::getInstance()->getValue(
            'SELECT `id_measurement` From `'._DB_PREFIX_.'wk_measurement_lang`
            WHERE `id_lang` = '. (int)$idLang. ' AND `name` = "'.pSQL($name).'"'
        );
    }

    public function getMaxIdMeasurement()
    {
        return Db::getInstance()->getValue(
            'SELECT max(`id_measurement`) From `'._DB_PREFIX_.'wk_measurement_lang`'
        );
    }

    public function insertMeasurement($idMeasurement, $idLang, $name)
    {
        if ($idMeasurement && $idLang) {
            return Db::getInstance()->insert('wk_measurement_lang', array(
                'id_measurement' => (int)$idMeasurement,
                'id_lang' => (int)$idLang,
                'name' => pSQL($name)
            ));
        }
    }

    public function updateMeasurement($idMeasurement, $idLang, $name)
    {
        if ($idMeasurement && $idLang) {
            return Db::getInstance()->update(
                'wk_measurement_lang',
                array(
                    'name' => pSQL($name)
                ),
                'id_measurement =' .(int)$idMeasurement. ' AND id_lang = ' .(int)$idLang
            );
        }
    }
}
