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
	</h3>
</div>
<div class="row-fluid batch">
	<ul class="nav nav-pills">
		<li class="{if $comment_list.filter.status eq ''}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}"}'>全部 <span class="badge badge-info">{$comment_list.com_count.count}</span> </a></li>
		<li class="{if $comment_list.filter.status eq '0'}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}&status=0"}'>待处理<span class="badge badge-info use-plugins-num">{$comment_list.com_count.waitcheck}</span></a></li>
		<li class="{if $comment_list.filter.status eq '1'}active{/if}"><a class="data-pjax" href='{url path="comment/admin/init" args="type={$comment_list.filter.type}&status=1"}'>已处理<span class="badge badge-info unuse-plugins-num">{$comment_list.com_count.checked}</span></a></li>
	</ul>
	<div class="btn-group f_l m_r5 row-batch">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fontello-icon-cog"></i>{lang key='comment::comment_manage.batch_operation'}
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
		<!-- {if $comment_list.filter.status neq null && $comment_list.filter.status neq 1} -->
			<li><a class="batch-sale-btn"  data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=allow&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_allow_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}" href="javascript:;"><i class="fontello-icon-eye"></i>{lang key='comment::comment_manage.allow'}</a></li>
		<!--{/if} -->
		<!-- {if $comment_list.filter.status eq 1} -->
			<li><a class="batch-notsale-btn" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=deny&status={$comment_list.filter.status}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_fobid_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}"  href="javascript:;"><i class="fontello-icon-eye-off"></i>{lang key='comment::comment_manage.forbid'}</a></li>
		<!-- {/if} -->
			<li><a data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=trashed_comment{if $comment_list.filter.status neq null}&status={$comment_list.filter.status}{/if}&page={$smarty.get.page}" data-msg="{lang key='comment::comment_manage.batch_move_confirm'}" data-noSelectMsg="{lang key='comment::comment_manage.pls_select_comment'}"  href="javascript:;"><i class="fontello-icon-box"></i>{lang key='comment::comment_manage.move_to_recycle'}</a></li>
		</ul>
	</div>
	<div class="choose_list f_r" >
		<select class="w120" name="rank" id="select-rank">
			<option value="0">本周</option>
			<!--{foreach from=$user_ranks item=item}-->
			<option value="{$item.rank_id}" {if $smarty.get.rank eq $item.rank_id} selected="selected" {/if}>{$item.rank_name}</option>
			<!-- {/foreach} -->
		</select>
		<form class="f_r form-inline" action="{RC_Uri::url('comment/admin/init')}{if $comment_list.filter.status neq null}&status={$comment_list.filter.status}{/if}{if $comment_list.filter.has_img neq null}&has_img={$comment_list.filter.has_img}{/if}{if $smarty.get.rank}&rank={$smarty.get.rank}{/if}{if $select_status}&select_status={$select_status}{/if}{if $select_rank}&select_rank={$select_rank}{/if}{if $select_img}&select_img={$select_img}{/if}"  method="post" name="searchForm">
			<input type="text" name="keyword" value="{$smarty.get.keywords}" placeholder="输入申诉编号或商家名称" size="15" />
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
					<th class="w120">申诉编号</th>
					<th class='w100'>商家名称</th>
					<th class="w550">申诉内容</th>
					<th class="w100">审核状态</th>
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
						<div>{$comment.content|truncate:100|escape:html}</div>
						{if $comment.has_image eq 1}
							{if $comment.imgs}
								<div class="img-style">
									<!-- {foreach from=$comment.imgs item=img_list} -->
											<img class="thumbnail" style="float:left;width:78px;height:78px;margin-right:8px;" alt="" src="{$img_list.file_path}">
									<!-- {/foreach} -->
								</div>
							{/if}
						{/if}
						<div class="edit-list">
						    {if $comment.status lt 2}
								<a class="toggle_view" href='{url path="comment/admin/check" args="comment_id={$comment.comment_id}&status={$comment.status}{if $smarty.get.page}&page={$smarty.get.page}{/if}"}' data-msg="您确定要更改此评论的状态吗？" data-val="{if $comment.status eq 0}allow{else}forbid{/if}" data-status="{$smarty.get.status}" >
									{if $comment.status eq 0} {t}批准{/t} {elseif $comment.status eq 1} <span class="ecjiafc-red">{t}驳回{/t}</span> {/if}
								</a>&nbsp;|&nbsp;
								<a class="data-pjax" href='{url path="comment/admin/reply" args="comment_id={$comment.comment_id}"}'>
									{t}查看及回复{/t}
								</a>&nbsp;|&nbsp;
								<a class="ecjiafc-red toggle_view" href='{url path="comment/admin/check" args="comment_id={$comment.comment_id}{if $smarty.get.page}&page={$smarty.get.page}{/if}"}' data-msg="{t}您确定要将该用户[{$comment.user_name|default:{lang key='comment::comment_manage.anonymous'}}]的评论移至回收站吗？{/t}" data-status="{$smarty.get.status}" data-val="trashed_comment" >{t}移至回收站{/t}</a>
						    {/if}
						</div>
					</td>
					<td>
						{section name=loop loop=$comment.comment_rank}   
							<i class="fontello-icon-star" style="color:#FF9933;"></i>
						{/section}
					</td>
				</tr>
				<tr style="border-top:none;">
					<td colspan="5" style="border-top:none;">
						<div style="border-top: 2px dashed #ddd;">
							<input class="form-control small span12" style="width:94%;margin-bottom:3px;margin-top:12px;" value="" name="reply_content" type="text" placeholder="感谢您对本店的支持！我们会更加的努力，为您提供更优质的服务。（可在此输入回复内容，也可选择系统自动回复）">
							<input class="btn btn-primary quick_reply" style="height:36px;margin-top:9px;" type="button" data-url="{url path='comment/admin/quick_reply'}" data-id={$comment.comment_id} data-status={$comment.status} value="回复" />
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