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
	
	<div class="trash-btn">
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a">
			<i class="fontello-icon-reply"></i> {'商品评价'}
		</a> 
	</div>
	</h3>
</div>
<div class="row-fluid batch">
	<div class="choose_list f_r" >
		<form class="f_r form-inline" action='{$form_search}'  method="post" name="searchForm">
			<input type="text" name="keyword" value="{$smarty.get.keywords}" placeholder="{'输入评价关键字进行搜索'}" size="15" />
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
						{if $comment.has_image eq 1}
						    <!-- {foreach from=$comment.imgs item=imgs} -->
							     <img class="thumbnail" style="float:left;width:78px;height:78px;margin-right:8px;" alt="" src="">
						    <!-- {/foreach} -->
						{/if}
						<div class="edit-list">
								<a class="data-pjax" href='{url path="comment/admin/reply" args="comment_id={$comment.comment_id}"}'>
									{t}查看{/t}
								</a>&nbsp;|&nbsp;
								<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{t}您确定要删除该用户[{$comment.user_name}]的评论吗？{/t}" href='{url path="comment/admin/remove" args="id={$comment.comment_id}"}'>
									{t}删除{/t}
								</a>
						</div>
					</td>
					<td>
						{section name=loop loop=$comment.comment_rank}   
							<i class="fontello-icon-star" style="color:#FF9933;"></i>
						{/section}
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