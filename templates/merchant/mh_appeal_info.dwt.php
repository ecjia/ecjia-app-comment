<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.appeal_info.init();
	
	/*
	* 服务器地址,成功返回,失败返回参数格式依照jquery.ajax习惯;
	* 其他参数同WebUploader
	*/
	$('#test').diyUpload({
		url:'server/fileupload.php',
		success:function(data) {
			console.info(data);
		},
		error:function(err) {
			console.info(err);	
		}
	});
	
</script>
<style>
</style>
<!-- {/block} -->
<!-- {block name="home-content"} -->

<!-- #BeginLibraryItem "/library/appeal_step.lbi" --><!-- #EndLibraryItem -->

<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
	</div>
  	<div class="pull-right">
		<!-- {if $action_link} -->
			<a class="btn btn-primary data-pjax" id="sticky_a" href="{$action_link.href}"><i class="fa fa-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</div>
  	<div class="clearfix"></div>
</div>

<div class="row-fluid edit-page">
   <div class="panel">
        <div class="panel-body">
			<div class="span12">
				<div class="appeal_top">
					<div class="panel-body">
						<a class="appeal-thumb">
							{if $avatar_img}
			                	<img src="{RC_Upload::upload_url()}/{$avatar_img}" >
			                {else}
			                	<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
			                {/if}
						</a>
						<div class="appeal-thumb-details">
							<h1>{$comment_info.user_name}</h1>
							<p>{$comment_info.add_time}<span>IP：{$comment_info.ip_address}</span></p><br>
						</div>
						<div class="appeal-goods">
						  	<p>商品评分：{section name=loop loop=$comment_info.comment_rank}<i class="fa fa-star"></i>{/section}</p>
			                <p>{$comment_info.content}</p>
			                <!-- {foreach from=$comment_pic_list item=list} -->
			                	<img src="{RC_Upload::upload_url()}/{$list.file_path}">
			                <!-- {/foreach} -->
						</div>
		            </div>    
				</div> 
				<div class="appeal_bottom"> 
					<h4>申诉内容</h4>               
					<form class="form-horizontal" action='{$form_action}' method="post" name="theForm">
						<textarea class="form-control" id="appeal_content" name="appeal_content" placeholder="请输入申诉理由" ></textarea>
						<br>
						
				         <div id="box">
							<div id="test" ></div>
						</div>
                        
						<input type="hidden" name="comment_id" value="{$comment_info.comment_id}" />
						<button class="btn btn-info" type="submit">提交申诉</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- {/block} -->