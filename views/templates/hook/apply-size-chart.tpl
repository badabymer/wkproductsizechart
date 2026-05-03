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

{if !(isset($idProduct) && $idProduct)}
    <div class="product-tab-content" id="product-tab-content-modulesizechart">
        <div id="product-tab-content-Images" class="product-tab-content wk_pre_warn">
            <div class="alert alert-warning">
                <button data-dismiss="alert" class="close" type="button">×</button>
                {l s='There is 1 warning.' mod='wkproductsizechart'}
                <ul id="seeMore" style="display:block;">
                    <li>{l s='You must save this product before selecting size chart.' mod='wkproductsizechart'}</li>
                </ul>
            </div>
        </div>
    </div>
{else}
    <div class="product-tab-content" id="product-tab-content-modulesizechart">
        <div class="panel product-tab" id="product-modulesizechart" style="padding:20px;">
            <h3>{l s='Product Size Chart' mod='wkproductsizechart'}</h3>
            {if isset($sizeCharts) && !empty($sizeCharts)}
                <div class="form-group">
                    <label class="col-sm-12 col-md-6 col-lg-4 required">
                        <span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Apply size chart">
                            {l s='Apply Size Chart on Product' mod='wkproductsizechart'}
                        </span>
                    </label>
                    <div class="col-md-6 col-lg-9">
                        <span class="fixed-width-lg">
                            <input type="radio" name="apply_chart" id="apply_chart_on" value="1"{if isset($selectedSizeChart) && ($selectedSizeChart)}checked="checked"{/if}>
                            <label for="apply_chart_on">{l s='Yes' mod='wkproductsizechart'}</label>
                            <input type="radio" name="apply_chart" id="apply_chart_off" value="0" {if !isset($selectedSizeChart) && !($selectedSizeChart)}checked="checked"{/if}>
                            <label for="apply_chart_off">{l s='No' mod='wkproductsizechart'}</label>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-md-5 col-lg-3">
                        <fieldset class="form-group">
                            <label>{l s='Select Size Chart' mod='wkproductsizechart'}</label>
                            <select id="form_hooks_size_chart_list" name="size_chart_list" class="custom-select fixed-width-xl form-control">
                                <option value="0">{l s='Select' mod='wkproductsizechart'}</option>
                                {foreach $sizeCharts as $sizeChart}
                                    {if $sizeChart.active}
                                        <option value="{$sizeChart.id_size_chart|escape:'htmlall':'UTF-8'}" {if isset($selectedSizeChart) && ($selectedSizeChart == $sizeChart.id_size_chart)}selected="selected"{/if}>{$sizeChart.title|escape:'htmlall':'UTF-8'}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div id="size_chart_table" name="size_chart_table" class="form-group">
                            {if isset($selectedSizeChart) && ($selectedSizeChart)}
                                {include file='./display-size-chart.tpl'}
                            {/if}
                        </div>
                    </div>
                </div>
            {else}
                <div class="col-sm-12 col-md-8 col-lg-5">
                    <div class="alert alert-warning">
                        {l s='You have to create the size chart before applying.' mod='wkproductsizechart'}
                    </div>
                </div>
            {/if}
            <div class="form-group">
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <a href="{$createSizeChartLink|escape:'htmlall':'UTF-8'}" target="_blank" class="btn btn-primary">
                        {l s='Create New Size Chart' mod='wkproductsizechart'}
                    </a>
                </div>
            </div>
        </div>
    </div>
{/if}
