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

<div class="col-sm-3 col-md-3 col-lg-3">
    <fieldset class="form-group">
        <label>{l s='Select Size Chart' mod='wkproductsizechart'}</label>
        <select id="size_chart_list" name="size_chart_list" class="custom-select fixed-width-xl form-control">
            <option value="0">{l s='Select' mod='wkproductsizechart'}</option>
            {foreach $sizeCharts as $sizeChart}
                {if $sizeChart.active}
                    <option value="{$sizeChart.id_size_chart|escape:'htmlall':'UTF-8'}" {if isset($selectedSizeChart) && ($selectedSizeChart == $sizeChart.id_size_chart)}selected="selected"{/if}>{$sizeChart.title|escape:'htmlall':'UTF-8'}</option>
                {/if}
            {/foreach}
        </select>
    </fieldset>
</div>
