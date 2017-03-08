<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
// 	ecjia.merchant.goods_info.init();
</script>
<!-- {/block} -->
<!-- {block name="home-content"} -->

<!-- #BeginLibraryItem "/library/appeal_step.lbi" --><!-- #EndLibraryItem -->

<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
	</div>
  	<div class="pull-right">
		<!-- {if $action_link} -->
			<a class="btn btn-primary data-pjax" id="sticky_a" href="{$action_link.href}"><i class="fa fa-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</div>
  	<div class="clearfix"></div>
</div>

<div class="row-fluid edit-page">
   <div class="panel">
        <div class="panel-body">
			<div class="span12">
				<div class="appeal_top">
					<div class="panel-body">
						<a class="appeal-thumb">
							<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg" >
						</a>
						<div class="appeal-thumb-details">
							<h1>送钱</h1>
							<p>2016-11-04 17：02:33<span>IP:10.10.10.41</span></p><br>
						</div>
						<div class="appeal-goods">
						  	<p>商品评分：</p>
			                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p>
			                <img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
						</div>
		            </div>    
				</div>                        
				<form class="form-horizontal" action='{url path="goods/merchant/add_link_goods" args="goods_id={$smarty.get.goods_id}{if $code}&extension_code={$code}{/if}"}' method="post" name="theForm">
					<div class="form-group">
						<div class="tab-content">
							<fieldset>
								
							</fieldset>
						</div>
					</div>
					<div class="form-group">
						<fieldset class="t_c">
							<input type="hidden" name="goods_id" value="{$goods_id}" />
							<input type="hidden" name="cat_id" />
							{if $step}
							<button class="btn btn-info next_step" disabled type="button" data-url='{url path="goods/merchant/add"}'>{lang key='goods::goods.next_step'}</button>
							<input type="hidden" name="step" value="{$step}" />
							{else}
							<button class="btn btn-info" type="submit">{lang key='goods::goods.save'}</button>
							{/if}
						</fieldset>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->