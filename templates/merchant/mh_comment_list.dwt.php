<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
ecjia.merchant.mh_comment.editlist();
</script>
<!-- {/block} -->

<!-- {block name="home-content"} -->
<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
  	</div>
  	<div class="pull-right">
  		{if $action_link}
		<a href="{$action_link.href}" class="btn btn-primary data-pjax">
			<i class="fa fa-plus"></i> {$action_link.text}
		</a>
		{/if}
  	</div>
  	<div class="clearfix"></div>
</div>

<div class="comment-list row">
	<div class="col-lg-12">
	   <div class="filter">
           <a href="javascript:;" class="fl-l">全部</a>
           <span class="fl-l">&nbsp;>&nbsp;</span>
           <div class="text-center filter-box fl-l">好评 <i class="cursor_pointer fa fa-times"></i></div>
           <div class="text-center filter-box fl-l">有图 <i class="cursor_pointer fa fa-times"></i></div>
	   </div>
	   <table class="table table-bordered table-striped table-hover table-hide-edit ecjiaf-tlf">
    	   <tr>
    	       <th class="text-right" style="width: 20%;padding-right: 1em;">评分级别:</th>
    	       <td>好评    中评    差评</td>
    	   </tr>
    	   <tr>
    	       <th class="text-right" style="width: 80%;padding-right: 1em;">有无晒图:</th>
    	       <td>有  无</td>
    	   </tr>
	   </table>
		<form class="form-inline f_r" action="{RC_Uri::url('goods/merchant/init')}{$get_url}" method="post" name="search_form">
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
					<input type="text" class="form-control" name="keywords" value="{$smarty.get.keywords}" placeholder="输入商品名称或用户名进行搜索">
				</div>
				<button class="btn btn-primary screen-btn" type="button"><i class="fa fa-search"></i> 搜索 </button>
			</div>
		</form>
	</div>
</div>
<div class="panel-body panel-body-small comment-list">
	<section class="panel">
		<table class="table table-striped table-hover table-hide-edit ecjiaf-tlf">
			<tr>
				<th class="width-20-p">用户名</th>
				<th class="width-60-p">评论详情</th>
				<th class="width-20-p">星级</th>
			</tr>
		</table>
	</section>
	<ul>
		<!-- {foreach from=$data.comment_list item=list} -->
		    <li class="edit-hidden">			
				<span class="width-20-p">
					{$list.user_name}
				</span>
				<div class="width-60-p hide-edit-area {if $goods.is_promote}ecjiafc-red{/if}">
				    <p>
				       <a href='{url path="goods/merchant/edit" args="goods_id=617"}'>{$list.goods_name}</a>
				    </p>
				    <p>{$list.add_time}</p>
					<p style="overflow: hidden;">{$list.content}</p>
					<div class="edit-list">
						<a class="data-pjax" href='{url path="comment/mh_comment/comment_detail"}'>查看详情&nbsp;</a>|
						<a class="data-pjax" href='{url path="comment/mh_comment/comment_reply"}'>回复</a>
					</div>
				</div>	
				<div class="width-20-p">
				{section name=loop loop=$list.comment_rank}   
					<i class="fa fa-star"></i>
				{/section}   
				</div>
				<div class="comment-list-hr"></div>
	            <div class="form-group col-lg-11">
	            	<input type="text" class="form-control" name="keywords" value="{$smarty.get.keywords}" placeholder="感谢您对本店的支持！我们会更加的努力，为您提供更优质的服务。（可在此输入回复内容，也可选择系统自动回复）">
	            </div>
	            <input class="comment-list-reply btn btn-primary screen-btn" type="button" value="回复" />
		    </li>
		<!-- {foreachelse} -->
		<!-- {/foreach} -->
	</ul>
</div>
<!-- {/block} -->