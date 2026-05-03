{*
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
* @author Webkul IN <support@webkul.com>
* @copyright 2010-2019 Webkul IN
* @license https://store.webkul.com/license.html
*}

<div class="row" id="wk-search-result">
    <div id="wk-filtered-list" class="col-lg-6 col-md-6 col-sm-6">
    </div>
    {if isset($sizeCharts) && !empty($sizeCharts)}
        {include file="$wkself/../../views/templates/hook/size-chart-list.tpl"}
    {else}
        <div class="col-sm-3 col-md-3 col-lg-3">
            <div class="alert alert-warning">
                {l s='You have to create the size chart before applying.' mod='wkproductsizechart'}
            </div>
        </div>
    {/if}
</div>
