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
				</div><br>
				
		        <div class="comment_all" id="accordionOne">
		            <div class="panel panel-info">
		                <div class="panel-heading">
		                    <a data-toggle="collapse" data-parent="#accordionOne" href="#collapseOne" class="accordion-toggle">
		                        <span class="glyphicon"></span>
		                        <h4 class="panel-title">评论内容</h4>
		                    </a>
		                </div>
		                <div id="collapseOne" class="panel-collapse collapse in">
	                         <div class="comment-substance panel-body">
	                              <div class="text-right">
		                              <p>admin</p>
		                              <p>2017-03-08 11:40:00</p>
		                              <img src="https://cityo2o.ecjia.com/content/uploads/data/avatar_img/201701101718165302.png" />
		                              <p>感谢您对本店的支持！我们会更加的努力，为您提供做优质的服务。</p>
	                              </div>
	                          </div>
		                </div>
		              </div>
		        </div>
		        
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
        <div class="panel panel-body">
            <div class="appeal-goods">
			  	<h4>商品信息</h4>
			  	<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p>
                <p>价格：</p>
                <p>购买于：</p>
			</div>
        </div>
        <section class="panel panel-body">
          <div class="appeal-goods">
			  	<h4>其他评价</h4>
			  	<p>用户名<span>查看</span></p><br>
                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p><br>
                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p>
		  </div>
        </section>
    </div>
</div>
<!-- {/block} -->