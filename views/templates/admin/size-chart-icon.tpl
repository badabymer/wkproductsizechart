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

{if isset($chartImage) && $chartImage}
    <img src='{$smarty.const._MODULE_DIR_|escape:'htmlall':'UTF-8'}/wkproductsizechart/views/img/{$chartImage|escape:'htmlall':'UTF-8'}'
    class="img-responsive">
{else}
    <img class='img-responsive' src='{$smarty.const._MODULE_DIR_|escape:'htmlall':'UTF-8'}/wkproductsizechart/views/img/home-default.jpg'>
{/if}
