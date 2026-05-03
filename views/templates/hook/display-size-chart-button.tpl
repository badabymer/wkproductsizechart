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

{if isset($call_ajax) && $call_ajax != 'quickview'}
	<div style="display: flex">
		<div style="margin-right: 8px;">
        {literal}
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="12" viewBox="0 0 18 12" style="margin-top: 3px;">
						<defs>
							<style>.cls-1 {
                  fill: #83c127;
                  fill-rule: evenodd;
                }</style>
						</defs>
						<path data-name="Rounded Rectangle 1044 copy" class="cls-1"
						      d="M1345,711h-14a2,2,0,0,1-2-2v-8a2,2,0,0,1,2-2h14a2,2,0,0,1,2,2v8A2,2,0,0,1,1345,711Zm0-10h-2v4a1,1,0,0,1-2,0v-4h-2v2a1,1,0,0,1-2,0v-2h-2v4a1,1,0,0,1-2,0v-4h-2v8h14v-8Z"
						      transform="translate(-1329 -699)"></path>
					</svg>
        {/literal}
		</div>
		<a href="#" data-toggle="modal" data-target="#my-size-chart-modal" id='size-chart-button'>
        {l s='Size Chart' mod='wkproductsizechart'}
        {if !(isset($popUpView) && $popUpView)}
					<i id="size-chart-arrow" class="material-icons">keyboard_arrow_down</i>
        {/if}
		</a>
	</div>
	<br>
    {if isset($popUpView) && $popUpView}
        {include file='./pop-up-view.tpl'}
    {else}
        {include file='./display-size-chart.tpl'}
    {/if}
	<br>
{/if}
