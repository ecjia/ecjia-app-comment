<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->

<script type="text/javascript">
	function getObjectURL(file) {
		var url = null;
		if (window.createObjectURL != undefined) {
			url = window.createObjectURL(file)
		} else if (window.URL != undefined) {
			url = window.URL.createObjectURL(file)
		} else if (window.webkitURL != undefined) {
			url = window.webkitURL.createObjectURL(file)
		}
		return url
	}
	
    $(function() {
		$(".filepath").live("change",function() {
			var srcs = getObjectURL(this.files[0]);//获取路径
			var htmlImg='<div class="imgbox1">'+
					  '<div class="imgnum1">'+
					  '<input type="file" name="file[]" class="filepath" />'+
					  '<span class="close">2</span>'+
					  '<img src="{$ecjia_main_static_url}img/appeal_pic.png" class="img11" width="50px" height="50px"/>'+
					  '<img src="" class="img22" />'+
					  '</div>'+
					  '</div>';
			  
			$(this).parent().children(".img22").attr('src', srcs);
			$(this).parent().children(".img11").hide();
			$(this).parent().parent().after(htmlImg);
			$(".close").on("click",function() {
				 $(this).hide();
				 $(this).nextAll(".img22").hide();
				 $(this).nextAll(".img11").show(); 
				 if($('.imgbox1').length>1){
					$(this).parent().parent().remove();
				 }
			})
		});
	});
</script>

<style type="text/css">
.imgbox1{
	float: left;
	margin-right: 5px;	
	position: relative;
	width: 50px;
	height: 50px;
	overflow: hidden;
}
.imgnum{
	left: 0px;
	top: 0px;
	margin: 0px;
	padding: 0px;
}
.imgnum input,.imgnum1 input {
	position: absolute;
	width: 182px;
	height: 142px;
	opacity: 0;
}
.imgnum img,.imgnum1 img {
	width: 100%;
	height: 100%;
}
.close{
	color: red;
	position: absolute;
	left: 170px;
	top: 0px;
	display: none;
}
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
					<form class="form-horizontal" action='{$form_action}' method="post" name="theForm"  enctype="multipart/form-data">
						<textarea class="form-control" id="appeal_content" name="appeal_content" placeholder="请输入申诉理由" ></textarea>
						<br>
						<div class="imgbox1">
							<div class="imgnum">
								<input type="file" name="file[]" class="filepath" />
								<span class="close">1</span>
								<img src="{$ecjia_main_static_url}img/appeal_pic.png" width="50px" height="50px" class="img11" />
								<img src="" class="img22" />
							</div>
						</div>
						<input type="hidden" name="comment_id" value="{$comment_info.comment_id}" />
						<br>
						<button class="btn btn-info" type="submit">提交申诉</button>
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- {/block} -->