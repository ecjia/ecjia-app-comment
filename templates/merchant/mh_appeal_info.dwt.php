<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.appeal_info.init();

    function imgChange(obj1, obj2) {
        //获取点击的文本框
        var file = document.getElementById("file");
        //存放图片的父级元素
        var imgContainer = document.getElementsByClassName(obj1)[0];
        //获取的图片文件
        var fileList = file.files;
        //文本框的父级元素
        var input = document.getElementsByClassName(obj2)[0];
        var imgArr = [];
        //遍历获取到得图片文件
        for (var i = 0; i < fileList.length; i++) {
            var imgUrl = window.URL.createObjectURL(file.files[i]);
            imgArr.push(imgUrl);
            var img = document.createElement("img");
            img.setAttribute("src", imgArr[i]);
            var imgAdd = document.createElement("div");
            imgAdd.setAttribute("class", "z_addImg");
            imgAdd.appendChild(img);
            imgContainer.appendChild(imgAdd);
        };
        imgRemove();
    };

    function imgRemove() {
        var imgList = document.getElementsByClassName("z_addImg");
        var mask = document.getElementsByClassName("z_mask")[0];
        var cancel = document.getElementsByClassName("z_cancel")[0];
        var sure = document.getElementsByClassName("z_sure")[0];
        for (var j = 0; j < imgList.length; j++) {
            imgList[j].index = j;
            imgList[j].onclick = function() {
                var t = this;
                mask.style.display = "block";
                cancel.onclick = function() {
                    mask.style.display = "none";
                };
                sure.onclick = function() {
                    mask.style.display = "none";
                    t.style.display = "none";
                };
            }
        };
    };
	
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
						
				        <div class="z_photo">
				            <div class="z_file">
				                <input type="file" name="picture[]" id="file" value="" multiple="true" onchange="imgChange('z_photo','z_file');" />
				            </div>
				        </div>
				        
				        <div class="z_mask">
				            <div class="z_alert">
				                <p>确定要删除这张图片吗？</p>
				                <p>
				                    <span class="z_cancel">取消</span>
				                    <span class="z_sure">确定</span>
				                </p>
				            </div>
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