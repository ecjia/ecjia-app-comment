<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.comment_manage.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading"> 
		<!-- {if $ur_here}{$ur_here}{/if} -->
		{if $action_link} 
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a">
			<i class="fontello-icon-plus"></i>{$action_link.text}
		</a> 
		{/if} 
	</h3>
</div>
<div class="row-fluid batch">
	<ul class="nav nav-pills">
		<li class="{if $comment_list.filter.status eq ''}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}"}'>{lang key='comment::comment_manage.all'}<span class="badge badge-info">{$comment_list.com_count.count}</span> </a></li>
		<li class="{if $comment_list.filter.status eq '0'}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}&status=0"}'>{lang key='comment::comment_manage.waitcheck'}<span class="badge badge-info use-plugins-num">{$comment_list.com_count.waitcheck}</span></a></li>
		<li class="{if $comment_list.filter.status eq '1'}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}&status=1"}'>{lang key='comment::comment_manage.checked'}<span class="badge badge-info unuse-plugins-num">{$comment_list.com_count.checked}</span></a></li>
		<!-- {if $dropback_comment} -->
		<li class="{if $comment_list.filter.status eq '2'}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}&status=2"}' >{lang key='comment::comment_manage.trash_msg'}<span class="badge badge-info unuse-plugins-num">{$comment_list.com_count.trash_msg}</span></a></li>
		<li class="{if $comment_list.filter.status eq '3'}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}&status=3"}'>{lang key='comment::comment_manage.trashed_msg'}<span class="badge badge-info unuse-plugins-num">{$comment_list.com_count.trashed_msg}</span></a></li>
		<!-- {/if} -->
	</ul>
	<div class="btn-group f_l m_r5">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fontello-icon-cog"></i>{lang key='comment::comment_manage.batch_operation'}
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<!-- {if $comment_list.filter.status lt '2'} -->
			<!-- {if $comment_list.filter.status neq '1'} -->
			<li><a class="batch-sale-btn"  data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=allow&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_allow_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}" href="javascript:;"><i class="fontello-icon-eye"></i>{lang key='comment::comment_manage.allow'}</a></li>
			<!-- {/if} -->
			<!-- {if $comment_list.filter.status neq '0'} -->
			<li><a class="batch-notsale-btn" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=deny&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_fobid_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}"  href="javascript:;"><i class="fontello-icon-eye-off"></i>{lang key='comment::comment_manage.forbid'}</a></li>
			<!-- {/if} -->
			<li><a data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=trash_comment&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_trash_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}"  href="javascript:;"><i class="fontello-icon-heart-empty"></i>{lang key='comment::comment_manage.trash_msg'}</a></li>
			<li><a data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=trashed_comment&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_move_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}"  href="javascript:;"><i class="fontello-icon-box"></i>{lang key='comment::comment_manage.move_to_recycle'}</a></li>
			<!-- {/if} -->
			<!-- {if $dropback_comment} -->
				<!-- {if $comment_list.filter.status eq '2'} -->
				<li><a data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=deny&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_cancel_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}" href="javascript:;"><i class="fontello-icon-heart"></i>{lang key='comment::comment_manage.no_trash_msg'}</a></li>
				<li><a class="batch-trash-btn" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=remove&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.remove_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}" href="javascript:;"> <i class="fontello-icon-trash"></i>{lang key='comment::comment_manage.drop_select'}</a></li>
				<!-- {/if} -->
				<!-- {if $comment_list.filter.status eq '3'} -->
				<li><a data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=deny&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_restore_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}"  href="javascript:;"><i class="fontello-icon-reply-all"></i>{lang key='comment::comment_manage.restore_review'}</a></li>
				<li><a class="batch-trash-btn" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=remove&type={$comment_list.filter.type}&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.remove_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}" href="javascript:;"> <i class="fontello-icon-trash"></i>{lang key='comment::comment_manage.drop_select'}</a></li>
				<!-- {/if} -->
			<!-- {/if} -->
		</ul>
	</div>
	<div class="choose_list f_r" >
		<form class="f_r form-inline" action='{url path="comment/admin/init" args="type={$comment_list.filter.type}"}'  method="post" name="searchForm">
			<!--<span>{lang key='comment::comment_manage.search_comment'}：</span>-->
			<input type="text" name="keyword" value="{$smarty.get.keywords}" placeholder="{lang key='comment::comment_manage.search_comment'}" size="15" />
			<button class="btn search_comment" type="button">{lang key='system::system.button_search'}</button>
		</form>
	</div>
</div>
<div class="row-fluid list-page">
	<div class="span12">
		<table class="table table-striped smpl_tbl table-hide-edit">
			<thead>
				<tr>
					<th class="table_checkbox w50"> 
						<input class="uni_style" type="checkbox" data-toggle="selectall" data-children=".checkbox"/>
					</th>
					<th class="w100">{lang key='comment::comment_manage.user_name'}</th>
					<th>{lang key='comment::comment_manage.list_comment_info'}</th>
					<th class="w100">{lang key='comment::comment_manage.ip_address'}</th>
				</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$comment_list.item item=comment} -->
				<tr>
					<td><input class="checkbox" type="checkbox" name="checkboxes[]" value="{$comment.comment_id}"></td>
					<td>
						{if $comment.user_name}
							<a href='{url path="user/admin/info" args="id={$comment.user_id}"}'>{$comment.user_name}</a>
						{else}
							{lang key='comment::comment_manage.anonymous'}
						{/if}
					</td>
					<td class="hide-edit-area">
						<div><a href='{$comment.url}' target="_blank">{$comment.comment_name}</a></div>
						<div>{lang key='comment::comment_manage.comment_on'}&nbsp;&nbsp;{$comment.add_time}</div>
						{$comment.content|truncate:100|escape:html}
					
						<div class="edit-list">
							{if $comment.status lt 2}
							<a class="toggle_view" href='{url path="comment/admin/check" args="id={$comment.comment_id}&type={$comment_list.filter.type}"}' data-val="{if $comment.status eq 0}allow{else}forbid{/if}" data-status="{$smarty.get.status}">
								{if $comment.status eq '0'}{lang key='comment::comment_manage.approval'}{elseif $comment.status eq '1'}<span class="ecjiafc-red">{lang key='comment::comment_manage.reject'}</span>{/if}
							</a>&nbsp;|&nbsp;
							<a class="data-pjax" href='{url path="comment/admin/reply" args="id={$comment.comment_id}"}'>
								{lang key='comment::comment_manage.view_reply'}
							</a>&nbsp;|&nbsp;
							<a class="ecjiafc-red toggle_view" href='{url path="comment/admin/check" args="id={$comment.comment_id}&type={$comment_list.filter.type}"}' data-msg="{lang key='comment::comment_manage.trash_confirm'}" data-val="trash_comment" data-status="{$smarty.get.status}">{lang key='comment::comment_manage.trash_msg'}</a>&nbsp;|&nbsp;
							<a class="ecjiafc-red toggle_view" href='{url path="comment/admin/check" args="id={$comment.comment_id}&type={$comment_list.filter.type}"}' data-msg="{lang key='comment::comment_manage.move_confirm'}" data-val="trashed_comment" data-status="{$smarty.get.status}">{lang key='comment::comment_manage.move_to_recycle'}</a>
							{/if}
							{if $comment.status eq 2}
							<a class="toggle_view" href='{url path="comment/admin/check" args="id={$comment.comment_id}"}&type={$comment_list.filter.type}' data-val="no_trash" data-status="{$smarty.get.status}">{lang key='comment::comment_manage.no_trash_msg'}</a>&nbsp;|&nbsp;
							<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{lang key='comment::comment_manage.remove_confirm'}" href='{url path="comment/admin/remove" args="id={$comment.comment_id}"}' title="{lang key='comment::comment_manage.remove_forever'}">
								{lang key='comment::comment_manage.remove_forever'}
							</a>
							{/if}
							{if $comment.status eq 3}
							<a class="toggle_view" href='{url path="comment/admin/check" args="id={$comment.comment_id}"}&type={$comment_list.filter.type}' data-val="no_trashed" data-status="{$smarty.get.status}">{lang key='comment::comment_manage.restore_review'}</a>&nbsp;|&nbsp;
							<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{lang key='comment::comment_manage.remove_confirm'}" href='{url path="comment/admin/remove" args="id={$comment.comment_id}"}' title="{lang key='comment::comment_manage.remove_forever'}">
								{lang key='comment::comment_manage.remove_forever'}
							</a>
							{/if}
						</div>
					</td>
					<td>
						<div>{$comment.ip_address}</div>
						{$comment.ip_area}
					</td>
				</tr>
				<!-- {foreachelse} -->
				<tr>
					<td class="no-records" colspan="6">{lang key='system::system.no_records'}</td>
				</tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
		<!-- {$comment_list.page} --> 
	</div>
</div>
<!-- {/block} -->