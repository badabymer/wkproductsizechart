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

class WkSizeChart extends ObjectModel
{
    public $title;
    public $description;
    public $image;
    public $size_chart_type;
    public $id_attribute_group;
    public $active;
    public $date_add;
    public $date_upd;

    const WK_PREDEFINED_SIZE_CHART = 0;
    const WK_CUSTOM_SIZE_CHART = 1;
    const WK_IMAGE_SIZE_CHART = 2;

    public static $definition = array(
        'table' => 'wk_size_chart',
        'primary' => 'id_size_chart',
        'multilang' => true,
        'fields' => array(
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 50),
            'size_chart_type' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_attribute_group' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
            'title' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size' => 32,
                'lang' => true,
                'required' => true
            ),
            'description' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'lang' => true),
        ),
    );

    public function getAllSizeChart($id_lang)
    {
        return Db::getInstance()->executeS(
            'SELECT * From `'._DB_PREFIX_.'wk_size_chart_lang` s_l
            INNER JOIN `'._DB_PREFIX_.'wk_size_chart` s
            ON s.`id_size_chart` = s_l.`id_size_chart`
            WHERE `active` = 1 AND `id_lang` = '.(int)$id_lang
        );
    }

    public function checkChartActive($idSizeChart)
    {
        return Db::getInstance()->getValue(
            'SELECT `active` FROM `'. _DB_PREFIX_.'wk_size_chart`
            WHERE `id_size_chart` = ' .(int)$idSizeChart
        );
    }

    public function delete()
    {
        if (!$this->actionBeforeSizeChartDelete($this->id)
            || !parent::delete()) {
            return false;
        }
        return true;
    }

    public function actionBeforeSizeChartDelete($idSizeChart)
    {
        if ($idSizeChart) {
            $objSizeChartAttribute = new WkSizeChartAttribute();
            $sizeChartAttrs = $objSizeChartAttribute->getSizeChartAttribute($idSizeChart);
            if ($sizeChartAttrs) {
                foreach ($sizeChartAttrs as $sizeChartAttr) {
                    $idSizeChartAttribute = (int)$sizeChartAttr['id_size_chart_attribute'];
                    if ($idSizeChartAttribute) {
                        Db::getInstance()->delete(
                            'wk_size_chart_measurement',
                            'id_size_chart_attribute = '.(int)$idSizeChartAttribute
                        );
                    }
                }
                Db::getInstance()->delete(
                    'wk_size_chart_attribute',
                    'id_size_chart = '.(int)$idSizeChart
                );
            }
            Db::getInstance()->delete(
                'wk_size_chart_product',
                'id_size_chart = '.(int)$idSizeChart
            );
            return true;
        }
        return false;
    }
}
