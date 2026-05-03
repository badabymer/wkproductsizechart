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

<div class="form-group" id="attribute_checkbox">
    <label class="control-label col-sm-3 col-md-3 col-lg-3 required">
        {l s='Choose Attribute Value' mod='wkproductsizechart'}
    </label>
    {if !empty($attributes) && $attributes}
        <div class="col-sm-9 col-md-9 col-lg-9">
            {foreach $attributes as $attribute}
                <div class="checkbox col-sm-3 col-md-3 col-lg-3">
                    <label for="attribute_{$attribute.id_attribute|escape:'htmlall':'UTF-8'}">
                        <input type="checkbox" name="attribute_list[]"
                        id="attribute_{$attribute.id_attribute|escape:'htmlall':'UTF-8'}"
                        data-name="{$attribute.attributeName|escape:'htmlall':'UTF-8'}"
                        value="{$attribute.id_attribute|escape:'htmlall':'UTF-8'}"
                        class="attribute_list"
                        {if ($selectedAttributes)}
                            {if in_array($attribute.id_attribute, $selectedAttributes)}checked="checked"{/if}
                        {/if}>
                        {$attribute.name|escape:'htmlall':'UTF-8'}
                    </label>
                </div>
            {/foreach}
        </div>
    {else}
        <div class="col-sm-3 col-md-3 col-lg-3">
            <label class="control-label"><b>
                {l s='No attribute values found.' mod='wkproductsizechart'}
            </b></label>
        </div>
    {/if}
</div>
