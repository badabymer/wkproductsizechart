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

<div id="size-chart-view">
    {if $sizeChart->size_chart_type == 2}
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                {if $sizeChart->image}
                    <img src="{$smarty.const._MODULE_DIR_|escape:'htmlall':'UTF-8'}/wkproductsizechart/views/img/{$sizeChart->image|escape:'htmlall':'UTF-8'}"
                        alt="{$sizeChart->title[$id_lang]|escape:'htmlall':'UTF-8'}" class="img-responsive" style="width:100%;">
                {/if}
            </div>
        </div>
    {else}
        <div class="row">
            {if $sizeChart->image}
                {if !$sizeChart->description[$id_lang]}<div class="col-lg-4"></div>{/if}
                <div id="wk_img_div" class="col-sm-12 col-md-12 col-lg-4">
                    <div class="wk-border wk-height wk-table-text-align">
                        <img src="{$smarty.const._MODULE_DIR_|escape:'htmlall':'UTF-8'}/wkproductsizechart/views/img/{$sizeChart->image|escape:'htmlall':'UTF-8'}"
                        alt="No Image" class="img-responsive wk-img-height">
                    </div>
                </div>
            {/if}
            {if $sizeChart->description[$id_lang]}
                <div class="col-sm-12 col-md-12 {if $sizeChart->image}col-lg-8{else}col-lg-12{/if}">
                    <div class="wk-row-padding wk-border wk-font-size wk-scroll-vertical wk-height">
                        {$sizeChart->description[$id_lang] nofilter}
                    </div>
                </div>
            {/if}
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="wk-border wk-scroll-horizontal">
                    <table class="table table-striped wk-table-margin">
                        <tbody>
                            {if isset($templateType) && $templateType}
                                <tr class="table-active">
                                    <td class="wk-table-row-border"></td>
                                    {foreach $attributeNames as $attributeName}
                                        <th class="wk-table-row-border wk-table-text-align">{$attributeName|escape:'htmlall':'UTF-8'}</th>
                                    {/foreach}
                                </tr>
                                {foreach $measurementNames as $key => $measurementName}
                                    <tr>
                                        <th class="wk-table-row-border">{$measurementName|escape:'htmlall':'UTF-8'}</th>
                                        {foreach $measurements[$key] as $measurement}
                                            <td class="wk-table-row-border wk-table-text-align">{$measurement.value|escape:'htmlall':'UTF-8'}</td>
                                        {/foreach}
                                    </tr>
                                {/foreach}
                            {else}
                                <tr class="table-active">
                                    <td class="wk-table-row-border"></td>
                                    {foreach $measurementNames as $measurementName}
                                        <th class="wk-table-row-border wk-table-text-align">{$measurementName|escape:'htmlall':'UTF-8'}</th>
                                    {/foreach}
                                </tr>
                                {foreach $attributeNames as $key => $attributeName}
                                    <tr>
                                        <th class="wk-table-row-border">{$attributeName|escape:'htmlall':'UTF-8'}</th>
                                        {foreach $measurements[$key] as $measurement}
                                            <td class="wk-table-row-border wk-table-text-align">{$measurement.value|escape:'htmlall':'UTF-8'}</td>
                                        {/foreach}
                                    </tr>
                                {/foreach}
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    {/if}
</div>
