<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
// 	ecjia.merchant.goods_info.init();
</script>
<!-- {/block} -->
<!-- {block name="home-content"} -->

<!-- #BeginLibraryItem "/library/appeal_step.lbi" --><!-- #EndLibraryItem -->

<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
	</div>
  	<div class="pull-right">
		<!-- {if $action_link} -->
			<a class="btn btn-primary data-pjax" id="sticky_a" href="{$action_link.href}"><i class="fa fa-reply"></i> {$action_link.text}</a>
		<!-- {/if} -->
	</div>
  	<div class="clearfix"></div>
</div>

<div class="row-fluid edit-page">
   <div class="panel">
        <div class="panel-body">
			<div class="span12 appeal_bottom">
				<div class="appeal_top">
					<div class="panel-body">
						<a class="appeal-thumb">
							<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg" >
						</a>
						<div class="appeal-thumb-details">
							<h1>送钱</h1>
							<p>2016-11-04 17：02:33<span>IP:10.10.10.41</span></p><br>
						</div>
						<div class="appeal-goods">
						  	<p>商品评分：</p>
			                <p>收到手机已经过去三天了，我觉得无法使用，所以给差评</p>
			                <img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
						</div>
		            </div>    
				</div> 
				<h4>申诉内容</h4>        
				<div class="appeal_top">
					<div class="panel-body">
						<div class="appeal-appeal">
			                <p>{$appeal.appeal_content}</p>
			                <img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
			                <p>{$appeal.appeal_time}</p>
						</div>
		            </div>    
				</div> 
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->