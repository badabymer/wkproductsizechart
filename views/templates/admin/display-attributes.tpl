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

<div class="row wk-size-chart-data">
    <div class="col-lg-9 col-lg-offset-3">
        <div id="wk_size_chart_container">
            <div class="row wk_set_row_margin back_btn_row">
                <div class="wk-float-left wk-col-margin">
                    <button id="back" type="button" class="btn btn-primary">
                        {l s='Back' mod='wkproductsizechart'}
                    </button>
                </div>
                <div class="wk-float-left wk-col-margin">
                    <button id="add_column_btn" type="button" class="btn btn-primary">
                        {l s='Add Column' mod='wkproductsizechart'}
                    </button>
                </div>
            </div>
            <div id="wk_attr_option" class="wk-row-width row wk_set_row_margin">
                <div class="wk-float-left wk-col-margin">
                    <div name="pre_attributes" class="fixed-width-sm wk-border-radius wk-set-option">
                        {l s='Options' mod='wkproductsizechart'}
                    </div>
                </div>
                {foreach $languages as $key => $language}
                    <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"
                    {if $current_lang.id_lang != $language.id_lang}style="display:none;"{/if}>
                        {if isset($edit_chart)}
                            {if isset($attributeNameData) && is_array($attributeNameData)}
                                {assign var="attributeKey" value=1}
                                {foreach $attributeNameData as $wkAttributeId => $name}
                                    <div class="wk-float-left wk-col-margin wk_attr_heading_{$wkAttributeId|escape:'htmlall':'UTF-8'} wk-attr-{$language.id_lang|escape:'htmlall':'UTF-8'}">
                                        <input type="text" name="pre_attributes_value{$attributeKey|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="fixed-width-sm wk-border-radius" value="{$name[$key]|escape:'htmlall':'UTF-8'}" {if !($attributeType)}readonly{/if}>
                                    </div>
                                    {assign var="attributeKey" value=$attributeKey + 1}
                                {/foreach}
                            {/if}
                        {/if}
                        <div class="col-sm-2 col-md-2 col-lg-2 wk-size-chart-lang-btn" data-lang-id="{$language.id_lang|escape:'htmlall':'UTF-8'}">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'htmlall':'UTF-8'}
                                <i class="icon-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach $languages as $language}
                                    <li><a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});"
                                    tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/foreach}
            </div>
            {if isset($edit_chart)}
                {assign "measureIndex" "0"}
                {if isset($measurementData)}
                    {foreach $measurementData as $measurementKey => $measurement}
                        {assign var="measureIndex" value=$measureIndex + 1}
                        <div class="wk-row-width row wk_set_row_margin wk-measurement-row wk_measurement_{$measurementKey|escape:'htmlall':'UTF-8'}">
                            <div class="wk-float-left wk-col-margin">
                                {foreach $languages as $key => $language}
                                    <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $current_lang.id_lang != $language.id_lang}style="display:none;"{/if}>
                                        <input type="text" name="measurement{$measureIndex|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="fixed-width-sm wk-border-radius" value="{$measurement['name'][$key]|escape:'htmlall':'UTF-8'}">
                                    </div>
                                {/foreach}
                            </div>
                            {assign var="valueKey" value=1}
                            {foreach $measurement['value'] as $key => $value}
                                <div class="wk-float-left wk-col-margin wk_attr_value_{$value['id_attribute']|escape:'htmlall':'UTF-8'}">
                                    <input type="text" name="measurement_value{$measureIndex|escape:'htmlall':'UTF-8'}_{$attributeId[$key]|escape:'htmlall':'UTF-8'}" class="fixed-width-sm wk-border-radius" value="{$value['value']|escape:'htmlall':'UTF-8'}">
                                </div>
                                {assign var="valueKey" value=$valueKey+1}
                            {/foreach}
                            <div class="wk-float-left wk-col-margin wk-delete-measurement"
                            data-measure-index="{$measureIndex|escape:'htmlall':'UTF-8'}">
                                <button id="delete_row_btn{$measureIndex|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default">
                                    <i class="icon-trash-o"></i>
                                </button>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            {/if}
            <div class="row wk_set_row_margin add_btn_row">
                <div class="wk-float-left wk-col-margin">
                    <button id="add_row_btn" type="button" class="btn btn-primary">
                        {l s='Add Row' mod='wkproductsizechart'}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
