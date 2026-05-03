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

class WkProductSizeChartDb
{
    /**
     * Execute SQL query
     */
    public function createTables()
    {
        if ($sql = $this->getModuleSql()) {
            foreach ($sql as $query) {
                if ($query) {
                    if (!Db::getInstance()->execute(trim($query))) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     *  SQL query for Table Creation
     */
    public function getModuleSql()
    {
        return array(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_size_chart` (
                `id_size_chart` int(10) unsigned NOT NULL auto_increment,
                `image` varchar(50),
                `size_chart_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
                `id_attribute_group` int(10) unsigned NOT NULL DEFAULT '0',
                `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_size_chart`)
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8",
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_size_chart_lang` (
                `id_size_chart` int(10) unsigned NOT NULL,
                `id_lang` int(11) unsigned NOT NULL,
                `title` varchar(32) NOT NULL,
                `description` text,
                PRIMARY KEY (`id_size_chart`, `id_lang`)
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8",
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_size_chart_attribute` (
                `id_size_chart_attribute` int(10) unsigned NOT NULL auto_increment,
                `id_size_chart` int(10) unsigned NOT NULL,
                `id_attribute` int(10) unsigned NOT NULL,
                `is_custom` tinyint(1),
                PRIMARY KEY (`id_size_chart_attribute`),
                FOREIGN KEY (`id_size_chart`) REFERENCES `"._DB_PREFIX_."wk_size_chart`(`id_size_chart`)
                ON DELETE CASCADE
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8",
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_custom_attribute_lang` (
                `id_custom_attribute` int(10) unsigned NOT NULL,
                `id_lang` int(11) unsigned NOT NULL,
                `name` varchar(32) NOT NULL,
                PRIMARY KEY (`id_custom_attribute`, `id_lang`)
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8",
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_measurement_lang` (
                `id_measurement` int(10) unsigned NOT NULL,
                `id_lang` int(11) unsigned NOT NULL,
                `name` varchar(32) NOT NULL,
                PRIMARY KEY (`id_measurement`, `id_lang`)
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8",
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_size_chart_measurement` (
                `id_size_chart_measurement` int(10) unsigned NOT NULL auto_increment,
                `id_size_chart_attribute` int(10) unsigned NOT NULL,
                `id_measurement` int(10) unsigned NOT NULL,
                `value` varchar(32) NOT NULL,
                PRIMARY KEY (`id_size_chart_measurement`),
                FOREIGN KEY (`id_size_chart_attribute`) REFERENCES `"
                ._DB_PREFIX_."wk_size_chart_attribute`(`id_size_chart_attribute`),
                FOREIGN KEY (`id_measurement`) REFERENCES `"._DB_PREFIX_."wk_measurement_lang`(`id_measurement`)
                ON DELETE CASCADE
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8",
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_size_chart_filter` (
                `id_size_chart_filter` INT(10) unsigned NOT NULL auto_increment,
                `search_product_name` VARCHAR(220),
                `id_categories` VARCHAR(150),
                `id_suppliers` VARCHAR(150),
                `id_manufacturers` VARCHAR(150),
                `date_add` DATETIME NOT NULL,
                `date_upd` DATETIME NOT NULL,
                PRIMARY KEY (`id_size_chart_filter`)
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8",
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_size_chart_product` (
                `id_size_chart_product` int(10) unsigned NOT NULL auto_increment,
                `id_size_chart` int(10) unsigned NOT NULL,
                `id_product` int(10) unsigned NOT NULL,
                `id_size_chart_filter` INT(10) unsigned NOT NULL,
                PRIMARY KEY (`id_size_chart_product`),
                FOREIGN KEY (`id_size_chart`) REFERENCES `"._DB_PREFIX_."wk_size_chart`(`id_size_chart`),
                FOREIGN KEY (`id_product`) REFERENCES `"._DB_PREFIX_."product`(`id_product`)
            ) ENGINE = "._MYSQL_ENGINE_." DEFAULT CHARSET = utf8"
        );
    }

    /**
     * Execute SQL query for Table Deletion
     */
    public function deleteTables()
    {
        Db::getInstance()->execute('SET FOREIGN_KEY_CHECKS=0');
        $sql = array(
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_size_chart_product`",
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_size_chart_filter`",
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_size_chart_measurement`",
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_measurement_lang`",
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_custom_attribute_lang`",
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_size_chart_attribute`",
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_size_chart_lang`",
            "DROP TABLE IF EXISTS `"._DB_PREFIX_."wk_size_chart`",
        );
        $result = true;
        foreach ($sql as $query) {
            if (!Db::getInstance()->execute(trim($query))) {
                $result = false;
            }
        }
        Db::getInstance()->execute('SET FOREIGN_KEY_CHECKS=1');
        return $result;
    }

    public function updateMeasurementLangData($newIdLang)
    {
        $measurementIds = Db::getInstance()->executeS(
            'SELECT `id_measurement` FROM `'._DB_PREFIX_.'wk_measurement_lang` GROUP BY `id_measurement`'
        );
        if ($measurementIds) {
            foreach ($measurementIds as $measurement) {
                $tableLangs = Db::getInstance()->getRow(
                    'SELECT * FROM `'._DB_PREFIX_.'wk_measurement_lang`
                    WHERE `id_measurement` = '.(int) $measurement['id_measurement'].'
                    AND `id_lang` = '.(int) Configuration::get('PS_LANG_DEFAULT')
                );
                if ($tableLangs) {
                    $tableValue = '';
                    foreach ($tableLangs as $key => $value) {
                        if ($key == 'id_measurement') {
                            $tableValue = "'".(int) $value."'";
                        } elseif ($key == 'id_lang') {
                            $tableValue = $tableValue.', '."'".(int) $newIdLang."'";
                        } else {
                            $content = str_replace("'", "\'", $value);
                            $tableValue = $tableValue.', '."'".pSQL($content)."'";
                        }
                    }

                    Db::getInstance()->execute(
                        'INSERT INTO `'._DB_PREFIX_.'wk_measurement_lang` VALUES ('.$tableValue.')'
                    );
                }
            }
        }
    }

    public function updateCustomAttrLangData($newIdLang)
    {
        $attributeIds = Db::getInstance()->executeS(
            'SELECT `id_custom_attribute` FROM `'._DB_PREFIX_.'wk_custom_attribute_lang` GROUP BY `id_custom_attribute`'
        );
        if ($attributeIds) {
            foreach ($attributeIds as $attribute) {
                $tableLangs = Db::getInstance()->getRow(
                    'SELECT * FROM `'._DB_PREFIX_.'wk_custom_attribute_lang`
                    WHERE `id_custom_attribute` = '.(int) $attribute['id_custom_attribute'].'
                    AND `id_lang` = '.(int) Configuration::get('PS_LANG_DEFAULT')
                );
                if ($tableLangs) {
                    $tableValue = '';
                    foreach ($tableLangs as $key => $value) {
                        if ($key == 'id_custom_attribute') {
                            $tableValue = "'".(int) $value."'";
                        } elseif ($key == 'id_lang') {
                            $tableValue = $tableValue.', '."'".(int) $newIdLang."'";
                        } else {
                            $content = str_replace("'", "\'", $value);
                            $tableValue = $tableValue.', '."'".pSQL($content)."'";
                        }
                    }

                    Db::getInstance()->execute(
                        'INSERT INTO `'._DB_PREFIX_.'wk_custom_attribute_lang` VALUES ('.$tableValue.')'
                    );
                }
            }
        }
    }

    public function updateSizeChartLangData($newIdLang)
    {
        $chartIds = Db::getInstance()->executeS(
            'SELECT `id_size_chart` FROM `'._DB_PREFIX_.'wk_size_chart_lang` GROUP BY `id_size_chart`'
        );
        if ($chartIds) {
            foreach ($chartIds as $chart) {
                $tableLangs = Db::getInstance()->getRow(
                    'SELECT * FROM `'._DB_PREFIX_.'wk_size_chart_lang`
                    WHERE `id_size_chart` = '.(int) $chart['id_size_chart'].'
                    AND `id_lang` = '.(int) Configuration::get('PS_LANG_DEFAULT')
                );
                if ($tableLangs) {
                    $tableValue = '';
                    foreach ($tableLangs as $key => $value) {
                        if ($key == 'id_size_chart') {
                            $tableValue = "'".(int) $value."'";
                        } elseif ($key == 'id_lang') {
                            $tableValue = $tableValue.', '."'".(int) $newIdLang."'";
                        } elseif ($key == 'title') {
                            $content = str_replace("'", "\'", $value);
                            $tableValue = $tableValue.', '."'".pSQL($content)."'";
                        }
                    }

                    Db::getInstance()->execute(
                        'INSERT INTO `'._DB_PREFIX_.'wk_size_chart_lang` VALUES ('.$tableValue.')'
                    );
                }
            }
        }
    }
}
