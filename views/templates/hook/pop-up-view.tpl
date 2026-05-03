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

{* aria-labelledby="myModalLabel" w3validator*}
<div class="modal fade" id="my-size-chart-modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="text-align:left;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h1>{l s='Size Chart' mod='wkproductsizechart'}</h1>
            </div>
            <div id="size-chart-modal-body" class="modal-body">
                {include file='./display-size-chart.tpl'}
            </div>
        </div>
    </div>
</div>
