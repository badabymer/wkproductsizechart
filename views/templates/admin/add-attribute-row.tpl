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

{if (isset($isBlankRow) && ($isBlankRow))}
    <div class="wk-row-width row wk_set_row_margin wk_measurement wk-measurement-row">
        <div class="wk-float-left wk-col-margin">
            {foreach $languages as $language}
                <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"
                    {if $current_lang.id_lang != $language.id_lang}style="display:none;"{/if}>
                    <input type="text" name="measurement{$countMeasure|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                     class="fixed-width-sm wk-border-radius">
                </div>
            {/foreach}
        </div>
        {foreach $attributes as $attrKey => $attribute}
            {assign var="attrKey" value=$attrKey+1}
            <div class="wk-float-left wk-col-margin wk_attr_value_{$attribute|escape:'htmlall':'UTF-8'}">
                <input type="text" name="measurement_value{$countMeasure|escape:'htmlall':'UTF-8'}_{$attribute|escape:'htmlall':'UTF-8'}"
                class="fixed-width-sm wk-border-radius">
            </div>
        {/foreach}
        <div class="wk-float-left wk-col-margin wk-delete-measurement" data-measure-index="{$countMeasure|escape:'htmlall':'UTF-8'}">
            <button id="delete_row_btn{$countMeasure|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default">
                <i class="icon-trash-o"></i>
            </button>
        </div>
    </div>
{else}
    {foreach $languages as $language}
        <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"
            {if $current_lang.id_lang != $language.id_lang}style="display:none;"{/if}>
            {if is_array($attributes)}
                {foreach $attributes as $attrKey => $attribute}
                    {assign var="attrIndex" value=$attrKey+1}
                    <div class="wk-float-left wk-col-margin wk-attr-{$language.id_lang|escape:'htmlall':'UTF-8'}">
                        <input type="text" name="pre_attributes_value{$attrIndex|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                        class="fixed-width-sm wk-border-radius" value="{if isset($attributeNames[$attrKey])}{$attributeNames[$attrKey]|escape:'htmlall':'UTF-8'}{/if}"
                        {if !($attributeType)}readonly{/if}>
                    </div>
                {/foreach}
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
{/if}
