<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.comment_manage_info.init();
</script>
<!-- {/block} -->
<!-- {block name="main_content"} -->
<!-- {if $reply_info.content} -->
<div class="alert alert-info">	
	<strong>{lang key='comment::comment_manage.have_reply_content'}</strong>
</div>
<!-- {/if} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a href="{$action_link.href}" class="btn plus_or_reply data-pjax" id="sticky_a"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>
<!-- comment content list -->
<div class="row-fluid">
	<div class="span12 formSep comment-content">
		<div>
			<b><!-- {if $msg.user_id gt 0} --><a href='{url path="user/admin/info" args="id={$msg.user_id}"}' target="_blank">{$msg.user_name}</a><!-- {else} -->{lang key='comment::comment_manage.anonymous'}<!-- {/if} --></b>
			&nbsp;{lang key='comment::comment_manage.to'}&nbsp;<b><a href="{$msg.url}" target="_blank">{$id_value}</a></b>&nbsp;{lang key='comment::comment_manage.send_comment'}
		</div>
		<div>
			<span class="help-block">
				{lang key='comment::comment_manage.published_in'}&nbsp;{$msg.add_time}&nbsp;{lang key='comment::comment_manage.comment_rank'}{$msg.comment_rank}&nbsp;&nbsp;{lang key='comment::comment_manage.ip_address_lable'}{$msg.ip_address}&nbsp;&nbsp;
				<!-- {if $msg.status lt 2} -->
				<a class="toggle_view_info" href='{url path="comment/admin/check" args="id={$msg.comment_id}&type={$msg.type}"}' data-val="{if $msg.status eq 0}reply_allow{else}reply_forbid{/if}">
					<!-- {if $msg.status eq 0} -->{lang key='comment::comment_manage.approval'}<!-- {else} -->
					<span class="ecjiafc-red">{lang key='comment::comment_manage.reject'}</span><!-- {/if} -->
				</a>
				<!-- {/if} -->
				&nbsp;|&nbsp;
				<a class="toggle_view_info ecjiafc-red" href='{url path="comment/admin/check" args="id={$msg.comment_id}&type={$msg.type}"}' data-msg="{lang key='comment::comment_manage.trash_confirm'}" data-val="trash_comment" data-status="2">{lang key='comment::comment_manage.trash_msg'}</a>
				&nbsp;|&nbsp;
				<a class="toggle_view_info ecjiafc-red" href='{url path="comment/admin/check" args="id={$msg.comment_id}&type={$msg.type}"}' data-msg="{lang key='comment::comment_manage.move_confirm'}" data-val="trashed_comment" data-status="3">{lang key='comment::comment_manage.move_to_recycle'}</a>
			</span>
		</div>
		<div>
			{$msg.content}
		</div>
	</div>
	<!-- {if $reply_info.content} -->
	<div class="formSep comment-content">
		<div>
			<span>{lang key='comment::comment_manage.admin_user_name'}</span>&nbsp;<b>{$reply_info.user_name}</b>
		</div>
		<div>
			<span class="help-block">
				{lang key='comment::comment_manage.reply_to'}&nbsp;{$reply_info.add_time}&nbsp;&nbsp;{lang key='comment::comment_manage.ip_address'}{$reply_info.ip_address}&nbsp;&nbsp;
				<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{lang key='comment::comment_manage.remove_confirm'}" href='{url path="comment/admin/remove" args="id={$reply_info.comment_id}"}' title="{lang key='system::system.remove'}">{lang key='system::system.drop'}</a>
			</span>
		</div>
		<div>
			{$reply_info.content}
		</div>
	</div>
	<!-- {/if} -->
</div>
<div class="row-fluid">
	<!-- reply content list -->
	<div class="span12">
		<form class="form-horizontal well" method="post" action="{$form_action}" name="theForm" data-edit-url='{url path="comment/admin/reply" args="id={$msg.comment_id}"}'>
			<fieldset>
				<h3 class="heading">
					<strong>{lang key='comment::comment_manage.reply_comment'}</strong>
				</h3>
				<div class="control-group">
					<input name="email" type="text" value="{$msg.email}" disabled="disabled" placeholder="{lang key='comment::comment_manage.email'}"/>
				</div>
				<div class="control-group">
					<textarea cols="30" rows="5" name="content" class="span10" placeholder="{lang key='comment::comment_manage.reply_content'}">{$reply_info.content}</textarea>
				</div>
				<div class="chk_radio">
					<input type="checkbox" name="send_email_notice" value="1" style="opacity: 0;">
					<span class="replyemail">{lang key='comment::comment_manage.send_email_notice'}</span>&nbsp;&nbsp;
					<!-- {if $reply_info.content} --><a class="ecjiaf-csp" data-url="{$form_action}" id="rmail"><strong>{lang key='comment::comment_manage.remail'}</strong></a><!-- {/if} -->
				</div><br>
				<div class="control-group">
					<button class="btn btn-gebo" type="submit">{lang key='comment::comment_manage.reply'}</button>
					<input type="hidden" name="comment_id" value="{$msg.comment_id}">
					<input type="hidden" name="comment_type" value="{$msg.comment_type}">
					<input type="hidden" name="id_value" value="{$msg.id_value}">
				</div>
			</fieldset>
		</form>
	</div>
</div>
<!-- {/block} -->