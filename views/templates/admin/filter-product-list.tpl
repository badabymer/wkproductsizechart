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

<label>{l s='Select Products' mod='wkproductsizechart'}</label>
<div class="wk-scroll-vertical wk-height wk-min-height">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="fixed-width-xs">
                    <span class="title_box">
                        <input type="checkbox" onclick="checkDelBoxes(this.form, 'searchedProduct[]', this.checked)">
                    </span>
                </th>
                <th class="fixed-width-xs">
                    <label class="title_box">
                        {l s='ID' mod='wkproductsizechart'}
                    </label>
                </th>
                <th>
                    <label class="title_box">
                        {l s='Product name' mod='wkproductsizechart'}
                    </label>
                </th>
            </tr>
        </thead>
        <tbody>
            {if isset($filteredList) && $filteredList}
                {foreach $filteredList as $filterProduct}
                    <tr>
                        <td>
                            <input type="checkbox" name="searchedProduct[]" class="groupBox" id="searchedProduct_{$filterProduct.id_product|escape:'htmlall':'UTF-8'}" value="{$filterProduct.id_product|escape:'htmlall':'UTF-8'}"
                            {if isset($appliedProductId)}
                                {foreach $appliedProductId as $productId}
                                    {if $productId == $filterProduct.id_product}
                                        checked="checked"
                                    {/if}
                                {/foreach}
                            {/if}>
                        </td>
                        <td>{$filterProduct.id_product|escape:'htmlall':'UTF-8'}</td>
                        <td>
                            <span for="searchedProduct_{$filterProduct.id_product|escape:'htmlall':'UTF-8'}">{$filterProduct.name|escape:'htmlall':'UTF-8'}</span>
                        </td>
                    </tr>
                {/foreach}
            {/if}
        </tbody>
    </table>
</div>
<p class="help-block">
    {l s='Size Chart wil be applied on all selected products.' mod='wkproductsizechart'}
</p>
