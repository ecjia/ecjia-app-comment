<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.mh_comment.editlist();
</script>
<!-- {/block} -->

<!-- {block name="home-content"} -->
<div class="row">
	<div class="col-lg-12">
		<h2 class="page-header">
			<!-- {if $ur_here}{$ur_here}{/if} -->
			<div class="pull-right">
				{if $action_link}
					<a href="{$action_link.href}" class="btn btn-primary data-pjax">
						<i class="fa fa-plus"></i> {$action_link.text}
					</a>
				{/if}
			</div>
		</h2>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel">
			<div class="panel-body">
				<div class="filter">
	           		<a href="javascript:;" class="fl-l">全部</a>
	           		<span class="fl-l">&nbsp;>&nbsp;</span>
	           		<div class="text-center filter-box fl-l">好评 <i class="cursor_pointer fa fa-times"></i></div>
	           		<div class="text-center filter-box fl-l">有图 <i class="cursor_pointer fa fa-times"></i></div>
				</div>
                <table class="table table-th-block">
                    <tbody>
                        <tr>
                            <td class="active w150">评分级别：</td>
                            <td>
								好评 中评 差评
							</td>
                        </tr>
                        <tr>
                            <td class="active">有无晒图：</td>
                            <td>
								有 无
							</td>
                        </tr>
                    </tbody>
                </table>
            </div>
			<div class="panel-body panel-body-small">
				<form class="form-inline pull-right" name="searchForm" method="post" action="{$search_action}">
					<div class="screen f_r">
						<div class="form-group">
							<select class="w130" name="intro_type">
								<option value="0">{lang key='goods::goods.intro_type'}</option>
								<!-- {foreach from=$intro_list item=list key=key} -->
								<option value="{$key}" {if $key == $smarty.get.intro_type}selected{/if}>{$list}</option>
								<!-- {/foreach} -->
							</select>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="keywords" value="{$smarty.get.keywords}" placeholder="输入用户名称">
						</div>
						<button class="btn btn-primary screen-btn" type="button"><i class="fa fa-search"></i> 搜索 </button>
					</div>
				</form>
			</div>
			<div class="panel-body panel-body-small">
				<section class="panel">
					<table class="table table-striped table-hover table-hide-edit">
						<thead>
							<tr>
								<th class="w100">用户名</th>
								<th class="w450">评论详情</th>
								<th class="w100">星级</th>
							</tr>
						</thead>
						<tbody>
							<!-- {foreach from=$data.comment_list item=list} -->
							<tr>
								<td>{$list.user_name}</td>
								<td class="hide-edit-area">
									<span>
										<a href='{url path="goods/merchant/edit" args="goods_id={$list.id_value}"}'>{$list.goods_name}</a><br>
										评论于 {$list.add_time}<br>
										{$list.content}
									</span>
									<br>
									<div class="edit-list">
										<a class="data-pjax" href='{url path="comment/mh_appeal/comment" args="comment_id={$list.comment_id}"}' title="查看详情">查看详情</a>&nbsp;|&nbsp;
										<a class="data-pjax" href='{url path="comment/mh_appeal/comment" args="comment_id={$list.comment_id}"}' title="回复">回复</a>
								    </div>
								</td>
								<td>
									{section name=loop loop=$list.comment_rank}   
										<i class="fa fa-star"></i>
									{/section}
								</td>
							</tr>
							<tr>
								<td colspan="2"><input class="form-control small" value="" type="text" placeholder="感谢您对本店的支持！我们会更加的努力，为您提供更优质的服务。（可在此输入回复内容，也可选择系统自动回复）"></td>
								<td > <input class="btn btn-primary " type="button" value="回复" /></td>
							</tr>
							<!-- {foreachelse} -->
							   <tr><td class="no-records" colspan="6">{lang key='system::system.no_records'}</td></tr>
							<!-- {/foreach} -->
							
						</tbody>
					</table>
				</section>
				<!-- {$data.page} -->
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->