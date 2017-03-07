<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
ecjia.merchant.merchant_comment.editlist();
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

<div class="row">
	<div class="col-lg-12">
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
	<section>
		<table class="table table-striped table-hover table-hide-edit ecjiaf-tlf">
			<tr>
				<th class="width-20-p">用户名</th>
				<th class="width-60-p">评论详情</th>
				<th class="width-20-p">星级</th>
			</tr>
		</table>
	</section>
		<ul>
		    <li class="edit-hidden">			
				<span class="width-20-p">
					test
				</span>
				<div class="width-60-p hide-edit-area {if $goods.is_promote}ecjiafc-red{/if}">
				    <p>
				       <a href='{url path="goods/merchant/edit" args="goods_id=617"}'>精选菜薹300g</a>
				    </p>
				    <p>评论于 2017-03-03 16:16:00</p>
					<span data-title="请输入商品名称">收到手机已经过去3天了，虽然还是有一些问题但是总体感觉是不错的。</span>
					<br/>
					<div class="edit-list">
						<a class="data-pjax" href='{url path="goods/merchant/edit" args="goods_id={$goods.goods_id}"}'>查看详情&nbsp;</a>|
						<a class="data-pjax" href='{url path="goods/merchant/edit_goods_desc" args="goods_id={$goods.goods_id}"}'>回复</a>
					</div>
				</div>	
				<span class="width-20-p">
					*****
				</span>
				<div class="comment-list-hr"></div>
                <div class="form-group col-lg-11">
                	<input type="text" class="form-control" name="keywords" value="{$smarty.get.keywords}" placeholder="感谢您对本店的支持！我们会更加的努力，为您提供更优质的服务。（可在此输入回复内容，也可选择系统自动回复）">
                </div>
                <input class="comment-list-reply btn btn-primary screen-btn" type="button" value="回复" />
		    </li>
		</ul>
</div>
<!-- {/block} -->