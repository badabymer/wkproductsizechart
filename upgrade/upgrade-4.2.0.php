<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_4_2_0($module)
{
    $objSizeChartDb = new WkProductSizeChartDb();
    $objSizeChartDb->upgradeTables();
    return true;
}
