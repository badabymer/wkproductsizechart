<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_4_3_0($module)
{
    $objSizeChartDb = new WkProductSizeChartDb();
    $objSizeChartDb->upgradeTables('4.3.0');
    return true;
}
