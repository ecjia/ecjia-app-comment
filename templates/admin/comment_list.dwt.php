<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
// 	ecjia.admin.comment_manage.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading"> 
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>
<div class="nav-heading">
	<ul class="nav-status">
		<li><span>全部</span></li>
		
		<li><span>></span></li>
		<li><a class="data-pjax btn" href='{url path="comment/admin/init" args="{if $smarty.get.status}status={$smarty.get.status}{/if}{if $smarty.get.rank}&rank={$smarty.get.rank}{/if}{if $smarty.get.has_img}&has_img={$smarty.get.has_img}{/if}&close_select={1}"}' style="padding:3px 5px;">待审核
			<i class=" close-status fontello-icon-cancel cursor_pointer"></i></a>
		</li>
		
		<li><span>></span></li>
		<li><a class="data-pjax btn" href='{url path="comment/admin/init" args="{if $smarty.get.rank}rank={$smarty.get.rank}{/if}{if $smarty.get.status}&status={$smarty.get.status}{/if}{if $smarty.get.has_img}&has_img={$smarty.get.has_img}{/if}&close_select={2}"}' style="padding:3px 5px;">好评
			<i class=" close-status fontello-icon-cancel cursor_pointer"></i></a>
		</li>
		
		<li><span>></span></li>
		<li><a class="data-pjax btn" href='{url path="comment/admin/init" args="{if $smarty.get.has_img}has_img={$smarty.get.has_img}{/if}{if $smarty.get.status}&status={$smarty.get.status}{/if}{if $smarty.get.rank}&rank={$smarty.get.rank}{/if}&close_select={3}"}' style="padding:3px 5px;">有图
			<i class=" close-status fontello-icon-cancel cursor_pointer"></i></a>
		</li>
	</ul>
	<div class="trash-btn">
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a">
			<i class=""></i>{$action_link.text}
		</a> 
	</div>
</div>
<div class="heading-table">
	<table class="table table-oddtd table-bordered">
		<tr>
			<td class="status-td" style="text-align:right; width:9%;">审核状态：</td>
			<td>
				<div class="data-pjax status-distance"><a  href='{url path="comment/admin/init" args="status=0{if $smarty.get.rank}&rank={$smarty.get.rank}{/if}{if $smarty.get.has_img}&has_img={$smarty.get.has_img}{/if}"}'>待审核</a></div>
				<div class="status-distance"><a href='{url path="comment/admin/init" args="status=1{if $smarty.get.rank}&rank={$smarty.get.rank}{/if}{if $smarty.get.has_img}&has_img={$smarty.get.has_img}{/if}"}'>已批准</a></div>
			</td>
		</tr>
		<tr>
			<td class="status-td" style="text-align:right; width:9%;">评分级别：</td>
			<td>
				<div class="status-distance"><a href='{url path="comment/admin/init" args="rank=1{if $smarty.get.status}&status={$smarty.get.status}{/if}{if $smarty.get.has_img}&has_img={$smarty.get.has_img}{/if}"}'>好评</a></div>
				<div class="status-distance"><a href='{url path="comment/admin/init" args="rank=2{if $smarty.get.status}&status={$smarty.get.status}{/if}{if $smarty.get.has_img}&has_img={$smarty.get.has_img}{/if}"}'>中评</a></div>
				<div class="status-distance"><a href='{url path="comment/admin/init" args="rank=3{if $smarty.get.status}&status={$smarty.get.status}{/if}{if $smarty.get.has_img}&has_img={$smarty.get.has_img}{/if}"}'>差评</a></div>
			</td>
		</tr>
		<tr>
			<td class="status-td" style="text-align:right; width:9%;">有无晒图：</td>
			<td>
				<div class="status-distance"><a href='{url path="comment/admin/init" args="has_img=1{if $smarty.get.status}&status={$smarty.get.status}{/if}{if $smarty.get.rank}&rank={$smarty.get.rank}{/if}"}'>有</a></div>
				<div class="status-distance"><a href='{url path="comment/admin/init" args="has_img=0{if $smarty.get.status}&status={$smarty.get.status}{/if}{if $smarty.get.rank}&rank={$smarty.get.rank}{/if}"}'>无</a></div>
			</td>
		</tr>
	</table>
</div>
<div class="row-fluid batch">
	<div class="btn-group f_l m_r5 row-batch">
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
		<form class="f_r form-inline" action='{$form_search}'  method="post" name="searchForm">
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
					<th class="w100">用户名</th>
					<th class='w100'>商家名称</th>
					<th class="w500">商品详情</th>
					<th class="w150">星级</th>
				</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$comment_list.item item=comment} -->
				<tr>
					<td><input class="checkbox" type="checkbox" name="checkboxes[]" value="{$comment.comment_id}"></td>
					<td>
						{if $comment.user_name}
							{$comment.user_name}
						{else}
							{lang key='comment::comment_manage.anonymous'}
						{/if}
					</td>
					<td>
						{$comment.merchants_name}
					</td>
					<td class="hide-edit-area">
						<div><a href='{url path="goods/admin/edit" args="goods_id={$comment.id_value}"}'' target="_blank">{$comment.goods_name}</a></div>
						<div>{lang key='comment::comment_manage.comment_on'}&nbsp;&nbsp;{$comment.add_time}</div>
						{$comment.content|truncate:100|escape:html}
						<img class="thumbnail" alt="" src="http://10.10.10.47/o2o/content/system/statics/images/nopic.png">
							<img class="thumbnail" alt="" src="http://10.10.10.47/o2o/content/system/statics/images/nopic.png">
							<img class="thumbnail" alt="" src="http://10.10.10.47/o2o/content/system/statics/images/nopic.png">
						<div class="edit-list">
						    {if $comment.status lt 2}
								<a class="toggle_view" href='{url path="comment/admin/check" args="comment_id={$comment.comment_id}"}' data-pjax-url='{url path="comment/admin/init" args="type={$comment_list.filter.type}"}&status={$comment_list.filter.status}' data-val="{if $comment.status eq 0}allow{else}forbid{/if}">
									{if $comment.status eq 0} {t}批准{/t} {elseif $comment.status eq 1} <span class="ecjiafc-red">{t}驳回{/t}</span> {/if}
								</a>&nbsp;|&nbsp;
								<a class="data-pjax" href='{url path="comment/admin/reply" args="comment_id={$comment.comment_id}"}'>
									{t}查看及回复{/t}
								</a>&nbsp;|&nbsp;
								<a class="ecjiafc-red toggle_view" href='{url path="comment/admin/check" args="comment_id={$comment.comment_id}"}' data-msg="{t}您确定要将该用户[{$comment.user_name|default:{lang key='comment::comment_manage.anonymous'}}]的评论移至回收站吗？{/t}" data-pjax-url='{url path="comment/admin/init" args="type={$comment_list.filter.type}"}&status={$comment_list.filter.status}&page={$smarty.get.page}' data-val="trashed_comment">{t}移至回收站{/t}</a>
						    {/if}
							{if $comment.status eq 3}
								<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{t}您确定要删除该用户[{$comment.user_name}]的评论吗？{/t}" href='{url path="comment/admin/remove" args="id={$comment.comment_id}"}' title="{t}永久移除{/t}">
									{t}永久删除{/t}
								</a>
							{/if}
						</div>
					</td>
					<td>
						{section name=loop loop=$comment.comment_rank}   
							<i class="fontello-icon-star"></i>
						{/section}
					</td>
				</tr>
				<tr style="border-top:none;">
					<td colspan="5" style="border-top:none;">
						<div style="border-top: 2px dashed #ddd;">
							<input class="form-control small span12" style="width:94.5%;margin-bottom:3px;margin-top:12px;" value="" name="reply_content" type="text" placeholder="感谢您对本店的支持！我们会更加的努力，为您提供更优质的服务。（可在此输入回复内容，也可选择系统自动回复）">
							<input style="float:right;" type="hidden" name="comment_id" value="{$list.comment_id}" />
							<input class="btn btn-primary" style="height:36px;margin-left:2px;margin-top:9px;" type="button" data-url="{url path='comment/admin/reply'}" value="回复" />
						</div>
					</td>
				</tr>
				<!-- {foreachelse} -->
				<tr>
					<td class="no-records" colspan="5">{lang key='system::system.no_records'}</td>
				</tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
		<!-- {$comment_list.page} --> 
	</div>
</div>
<!-- {/block} -->