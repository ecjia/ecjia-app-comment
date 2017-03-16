<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.comment_manage.init();
</script>
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
				<div class="control-group formSep">
					<label class="control-label">{t}评论是否开启：{/t}</label>
					<div class="controls">
			            <div class="info-toggle-button">
			                <input class="nouniform" name="close_comment" type="checkbox"  {if $close_comment eq 1}checked="checked"{/if}  value="1"/>
			            </div>
					</div>
				</div>
				<div class="control-group formSep">
					<label class="control-label">{t}用户评论是否需要审核：{/t}</label>
					<div class="controls">
			            <div class="info-toggle-button">
			                <input class="nouniform" name="comment_check" type="checkbox"  {if $comment_check eq 1}checked="checked"{/if}  value="1"/>
			            </div>
					</div>
				</div>
				<div class="control-group formSep">
					<label class="control-label">{t}商品评论的条件：{/t}</label>
					<div class="controls">
						<label>
			 				<input name="comment_factor" type="radio" value="0" {if $comment_factor eq 0}checked="checked"{/if} />{t}所有用户{/t}&nbsp;&nbsp;
			 				<input name="comment_factor" type="radio" value="1" {if $comment_factor eq 1}checked="checked"{/if} />{t}仅登录用户{/t}&nbsp;&nbsp;
			 				<input name="comment_factor" type="radio" value="2" {if $comment_factor eq 2}checked="checked"{/if} />{t}有过一次以上购买行为的用户{/t}&nbsp;&nbsp;
		 					<input name="comment_factor" type="radio" value="3" {if $comment_factor eq 3}checked="checked"{/if} />{t}仅购买过该商品的用户{/t}&nbsp;&nbsp;
		 					<span class="help-block">{t}选取较高的评论条件可以有效的减少垃圾评论的产生。只有用户订单完成后才认为该用户有购买行为{/t}</span>
		 				</label>
					</div>	
				</div>
				<div class="control-group formSep">
					<label class="control-label">{t}评论送积分是否开启：{/t}</label>
					<div class="controls">
			            <div class="info-toggle-button">
			                <input class="nouniform" name="comment_award_open" type="checkbox"  {if $comment_award_open eq 1}checked="checked"{/if}  value="1"/>
			            </div>
					</div>
				</div>
				<div class="control-group formSep">
					<label class="control-label">{t}评论一次获得积分奖励：{/t}</label>
					<div class="controls">
					    <div class="goods_type">
						      <input class="w200" name="comment_award" type="text" value="{$comment_award}" />
					          <span class="help-block">{t}按照会员等级可设置评论后获得的积分数量{/t}</span>
						</div>
						<!-- {foreach from=$user_rank_list item=item} -->
							<div class="goods_type">
								<label class="control-label membership">{t}{$item.rank_name}：{/t}</label>
								<input class="w200" name="comment_award_rules[{$item.rank_id}]" type="text" value="{$item.comment_award}" />
							</div>
						<!-- {/foreach} -->
						<span class="help-block">{t}不用此规则，则设置为0或不填，否则以规格为准。{/t}</span>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button class="btn btn-gebo" type="submit">{t}确定{/t}</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- {/block} -->
