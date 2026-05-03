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

class WkSizeChartFilter extends ObjectModel
{
    public $search_product_name;
    public $id_categories;
    public $id_suppliers;
    public $id_manufacturers;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'wk_size_chart_filter',
        'primary' => 'id_size_chart_filter',
        'fields' => array(
            'search_product_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName'),
            'id_categories' => array('type' => self::TYPE_STRING),
            'id_suppliers' => array('type' => self::TYPE_STRING),
            'id_manufacturers' => array('type' => self::TYPE_STRING),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
        )
    );

    public function getFilteredProducts($searchPattern, $idCategories, $idManufacturers, $idSuppliers, $idLang)
    {
        $sql = 'SELECT pl.`id_product`, pl.`name` FROM `'._DB_PREFIX_.'product_lang` pl
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = pl.`id_product`
                LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON cp.`id_product` = pl.`id_product`
                LEFT JOIN `'._DB_PREFIX_.'product_supplier` ps ON ps.`id_product` = pl.`id_product`
                WHERE `id_lang` = '.(int)$idLang.' AND `active` = 1';
        if ($searchPattern) {
            $sql .= ' AND `name` LIKE "%'.pSQL($searchPattern).'%"';
        }
        if ($idCategories) {
            $sql .= ' AND (';
            if (is_array($idCategories)) {
                $countIdCategories = count($idCategories);
                foreach ($idCategories as $categoryKey => $idCategory) {
                    if ($categoryKey+1 === $countIdCategories) {
                        $sql .= 'cp.`id_category` = '.(int)$idCategory.')';
                    } else {
                        $sql .= 'cp.`id_category` = '.(int)$idCategory.' OR ';
                    }
                }
            } else {
                $sql .= 'cp.`id_category` = '.(int)$idCategories.')';
            }
        }
        if ($idManufacturers) {
            $sql .= ' AND (';
            if (is_array($idManufacturers)) {
                $countIdManufacturers = count($idManufacturers);
                foreach ($idManufacturers as $ManfctrKey => $idManufacturer) {
                    if ($ManfctrKey+1 === $countIdManufacturers) {
                        $sql .= 'p.`id_manufacturer` = '.(int)$idManufacturer.')';
                    } else {
                        $sql .= 'p.`id_manufacturer` = '.(int)$idManufacturer.' OR ';
                    }
                }
            } else {
                $sql .= 'p.`id_manufacturer` = '.(int)$idManufacturers.')';
            }
        }
        if ($idSuppliers) {
            $sql .= ' AND (';
            if (is_array($idSuppliers)) {
                $countIdSuppliers = count($idSuppliers);
                foreach ($idSuppliers as $Supplierkey => $idSupplier) {
                    if ($Supplierkey+1 === $countIdSuppliers) {
                        $sql .= 'ps.`id_supplier` = '.(int)$idSupplier.')';
                    } else {
                        $sql .= 'ps.`id_supplier` = '.(int)$idSupplier.' OR ';
                    }
                }
            } else {
                $sql .= 'ps.`id_supplier` = '.(int)$idSuppliers.')';
            }
        }
        $sql .= ' GROUP BY pl.`id_product`';
        return Db::getInstance()->executeS($sql);
    }

    public function getDataByFilterId($idSizeChartFilter)
    {
        if ($idSizeChartFilter) {
            return Db::getInstance()->executeS(
                'SELECT * From `'._DB_PREFIX_.'wk_size_chart_product`
                WHERE `id_size_chart_filter` = '.(int)$idSizeChartFilter
            );
        }
        return false;
    }

    /**
     * Validate label filter befor add
     *
     * @param array $filterData
     * @return array
     */
    public function validateChartFilterData($filterData)
    {
        $objModule = new WkProductSizeChart();
        $objModule->errors = array();
        if (!empty($filterData['id_size_chart_filter'])) {
            $filterValue = $this->checkFilterExist($filterData, $filterData['id_size_chart_filter']);
            if (!empty($filterValue)) {
                $objModule->errors[] = $objModule->l('This filter is already created.', 'WkSizeChartFilter');
            }
        }

        if (!empty(trim($filterData['search_product_name']))) {
            if (!Validate::isCatalogName($filterData['search_product_name'])) {
                $objModule->errors[] = $objModule->l('Invalid search product.', 'WkSizeChartFilter');
            }
            if (Tools::strlen($filterData['search_product_name']) < 3) {
                $objModule->errors[] = $objModule->l('Search product must be at least three characters.', 'WkSizeChartFilter');
            }
        }

        if (!empty($filterData['wk_id_categories'])) {
            if (!is_array($filterData['wk_id_categories'])) {
                $objModule->errors[] = $objModule->l('Invalid category choosen.', 'WkSizeChartFilter');
            }
        }

        if (!empty($filterData['id_manufacturers'])) {
            if (!is_array($filterData['id_manufacturers'])) {
                $objModule->errors[] = $objModule->l('Invalid manufacturers choosen.', 'WkSizeChartFilter');
            }
        }

        if (!empty($filterData['id_suppliers'])) {
            if (!is_array($filterData['id_suppliers'])) {
                $objModule->errors[] = $objModule->l('Invalid suppliers choosen.', 'WkSizeChartFilter');
            }
        }

        if (!$filterData['applied_chart'] && empty($filterData['applied_chart'])) {
            $objModule->errors[] = $objModule->l('Select a size chart.', 'WkSizeChartFilter');
        }

        if (!$filterData['applied_products'] && empty($filterData['applied_products'])) {
            $objModule->errors[] = $objModule->l('Select product.', 'WkSizeChartFilter');
        }

        if (empty(trim($filterData['search_product_name']))
            && empty($filterData['wk_id_categories'])
            && empty($filterData['id_manufacturers'])
            && empty($filterData['id_suppliers'])
        ) {
            $objModule->errors[] = $objModule->l('At least one filter is required.', 'WkSizeChartFilter');
        }

        return $objModule->errors;
    }

    /**
     * Check filter existing or not
     *
     * @param [type] $filterData
     * @return void
     */
    public function checkFilterExist($filterData)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('wk_size_chart_filter', 'scf');
        $valueStatus = false;
        if (!empty($filterData['wk_id_categories'])) {
            $valueStatus = true;
            $sql->where('scf.id_categories = \''.pSQL(json_encode($filterData['wk_id_categories'])).'\'');
        }
        if (!empty($filterData['search_product_name'])) {
            $valueStatus = true;
            $sql->where('scf.search_product_name = \''.pSQL($filterData['search_product_name']).'\'');
        }
        if (!empty($filterData['id_suppliers'])) {
            $valueStatus = true;
            $sql->where('scf.id_suppliers = \''.pSQL(json_encode($filterData['id_suppliers'])).'\'');
        }
        if (!empty($filterData['id_manufacturers'])) {
            $valueStatus = true;
            $sql->where('scf.id_manufacturers = \''.pSQL(json_encode($filterData['id_manufacturers'])).'\'');
        }
        if (!empty($filterData['id_size_chart_filter'])) {
            $sql->where('scf.id_size_chart_filter != '.(int) $filterData['id_size_chart_filter']);
        }
        if ($valueStatus) {
            return Db::getInstance()->executeS($sql);
        } else {
            return array();
        }
    }

    public function delete()
    {
        if (!$this->actionBeforeFilterDelete($this->id)
            || !parent::delete()) {
            return false;
        }
        return true;
    }

    public function actionBeforeFilterDelete($idSizeChartFilter)
    {
        if ($idSizeChartFilter) {
            Db::getInstance()->delete(
                'wk_size_chart_product',
                'id_size_chart_filter = '.(int)$idSizeChartFilter
            );
            return true;
        }
        return false;
    }
}
