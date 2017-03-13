<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">

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
			<i class="fa fa-reply"></i> {$action_link.text}
		</a>
		{/if}
  	</div>
  	<div class="clearfix"></div>
</div>

<div class="row" id="home-content">
    <div class="col-lg-8">
        <section class="panel">
            <div class="panel-body">
				<div class="comment_top">
					<div class="panel-body">
						<a class="comment-thumb">
							<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg" >
						</a>
						<div class="comment-thumb-details">
							<h1>送钱</h1>
							<p>2016-11-04 17：02:33<span>IP:10.10.10.41</span></p><br>
						</div>
						<div class="comment-goods">
						  	<p>商品评分：</p>
			                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p>
			                <img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
						</div><br>
						<a href='{url path="comment/mh_appeal/add_appeal" args="comment_id={$comment_id}"}'><button class="btn btn-info" type="button">申诉</button></a>
		            </div>    
				</div> 
            </div>
        </section>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="appeal-goods">
			  	<p>商品信息</p>
			  	<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p>
                <p>价格：</p>
                <p>购买于：</p>
			</div>
        </div>
        <section class="panel">
          <div class="appeal-goods">
			  	<p>其他评价</p>
			  	<p>用户名<span>查看</span></p><br>
                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p><br>
                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p>
		  </div>
        </section>
    </div>
</div>
<!-- {/block} -->