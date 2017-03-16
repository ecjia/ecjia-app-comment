<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="data-pjax btn plus_or_reply" id="sticky_a" href="{$action_link.href}"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>

<div class="row-fluid">
	<div class="span12">
		<form class="form-horizontal" id="form-privilege" name="theForm" action="{$form_action}" method="post" enctype="multipart/form-data" >
			<fieldset class="comment-config">
				<div class="control-group formSep" >
					<label class="control-label">{'评论是否开启： '}</label>
					<div class="controls">
                        <div style="height: 20px; width: 50px; border: 1px solid #000"></div>
					</div>
				</div>

				<div class="control-group formSep">
					<label class="control-label">{'用户评论是否需要审核： '}</label>
					<div class="controls">
						<div style="height: 20px; width: 50px; border: 1px solid #000"></div>
					</div>
				</div>

				<div class="control-group formSep">
					<label class="control-label">{'商品评论的条件： '}</label>
					<div class="controls">
                        <div class="controls chk_radio term-checkbox">
							<input type="checkbox" name="cat_recommend[]" value="1" /><span>{'所有用户'}</span>
							<input type="checkbox" name="cat_recommend[]" value="2" /><span>{'仅登录用户'}</span>
							<input type="checkbox" name="cat_recommend[]" value="3" /><span>{'有过一次以上购买行为的用户'}</span>
							<input type="checkbox" name="cat_recommend[]" value="4" /><span>{'仅购买过该商品的用户'}</span>
							<span class="help-block">{'选取较高的评论条件可以有效的减少垃圾评论的产生。只有用户订单完成后才认为该用户有购买行为'}</span>
						</div>
					</div>
				</div>
				<div class="control-group formSep">
					<label class="control-label">{'评论送积分是否开启： '}</label>
					<div class="controls">
						<div style="height: 20px; width: 50px; border: 1px solid #000"></div>
					</div>
				</div>
				<div class="control-group formSep">
					<label class="control-label">{'评论一次获得积分奖励： '}</label>
					<div class="controls">
					    <div class="goods_type">
						      <input class="w200" name="email" type="text" value="{$store.email}" />
					          <span class="help-block">{'按照会员等级可设置评论后获得的积分数量'}</span>
						</div>
					    <div class="goods_type">
						      <label class="control-label membership">注册会员： </label>
						      <input class="w200" name="email" type="text" value="{$store.email}" />
						</div>
					    <div class="goods_type">
    					    <label class="control-label membership">VIP： </label>
    						<input class="w200" name="email" type="text" value="{$store.email}" />
						</div>
					    <div class="goods_type">
    					    <label class="control-label membership">代销会员： </label>
    						<input class="w200" name="email" type="text" value="{$store.email}" />
						</div>
					    <div class="goods_type">
    					    <label class="control-label membership">钻石会员： </label>
    						<input class="w200" name="email" type="text" value="{$store.email}" />
						</div>
						<span class="help-block">{'不用此规则，则设置为0或不填，否则以规格为准。'}</span>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<input type="hidden"  name="id" value="{$store.id}" />
						<button class="btn btn-gebo" type="submit">{'确定'}</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- {/block} -->
