<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		{if $action_link}
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fontello-icon-reply"></i> {$action_link.text}</a>
		{/if}
	</h3>
</div>
<!-- start add new category form -->
<form class="form-horizontal comment_info" action="{$form_action}" method="post" name="theForm" enctype="multipart/form-data" data-edit-url="{RC_Uri::url('goods/admin_category/edit')}">
	<div class="row-fluid editpage-rightbar">
		<div class="left-bar move-mod">
		
			<div class="control-group">
                <div class="comment_top">
    				<div class="panel-body">
    					<div class="comment-thumb">
    						{if $avatar_img}
    		                	<img src="{RC_Upload::upload_url()}/{$avatar_img}" >
    		                {else}
    		                	<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
    		                {/if}
    					</div>
    					<div class="comment-thumb-details">
    						<h1>{if $comment_info.is_anonymous eq 1 }{'匿名发表'}{else}{$comment_info.user_name}{/if}</h1>
    						<p>{$comment_info.add_time}<span>IP: {$comment_info.ip_address}</span></p><br>
    					</div>
    					<div class="comment-goods">
    					  	<p>商品评分：{section name=loop loop=$comment_info.comment_rank}<i class="fontello-icon-star" style="color:#FF9933;"></i>{/section}</p>
    		                <p>{$comment_info.content}</p>
    		                 <!-- {foreach from=$comment_pic_list item=list} -->
    		                	<img src="">
    		                 <!-- {/foreach} -->
    					</div>
    					<div class="edit-list">
							<a class="data-pjax" href='{url path="comment/admin/reply" args="comment_id={$comment.comment_id}"}'>
								{t}回复{/t}
							</a>&nbsp;|&nbsp;
							<a class="data-pjax" href='{url path="comment/admin/reply" args="comment_id={$comment.comment_id}"}'>
								{t}待审核{/t}
							</a>&nbsp;|&nbsp;
							<a class="data-pjax" href='{url path="comment/admin/reply" args="comment_id={$comment.comment_id}"}'>
								{t}驳回{/t}
							</a>&nbsp;|&nbsp;
							<a class="ecjiafc-red toggle_view" href='{url path="comment/admin/check" args="comment_id={$comment.comment_id}{if $smarty.get.page}&page={$smarty.get.page}{/if}"}' data-msg="{t}您确定要将该用户[{$comment.user_name|default:{lang key='comment::comment_manage.anonymous'}}]的评论移至回收站吗？{/t}" data-status="{$smarty.get.status}" data-val="trashed_comment" >{t}移至回收站{/t}</a>
							
						</div>
    	            </div>    
    			</div><br>
			
			</div>

			<div class="foldable-list move-mod-group" id="goods_info_sort_seo">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_seo">
							<strong>{'回复评价'}</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="goods_info_area_seo">
						<div class="accordion-inner">
						      <div class="panel-body">
						          <!-- {foreach from=$replay_admin_list item=list} -->
                                    <div class="text-right">
                                        <div class="comment-all-right-thumb">
									       <img src="{$list.staff_img}" >
								        </div>
        					  			 <div class="comment-thumb-details">
        									<h1>{$list.staff_name}</h1>
        									<p>{$list.add_time_new}</p><br>
        								 </div>
        								 <p>{$list.content}</p>
                                    </div>
                                  <!-- {foreachelse} -->
                                    <div class="text-center">管理员暂时还未回复任何消息</div>
                                  <!-- {/foreach} -->
                            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="control-group">
			    <div class="reply-title">回复： </div>
    			<textarea class="span12 form-control" name="cat_desc" rows="6" cols="48" placeholder="回复内容"></textarea>
    			<div class="text-right" style="margin: 10px 0">
					<input type="checkbox" name="is_show" id="" value="1" /><span>{'邮件通知'}</span>
    			    <input type="text" style="margin-left: 20px;" name="zipcode" placeholder="电子邮箱" />
    			</div>
			</div>
			<div class="control-group control-group-small">
				<button class="btn btn-gebo" type="submit">回复</button>
			</div>
		</div>
		
		<div class="right-bar move-mod">
			<div class="foldable-list move-mod-group edit-page" id="goods_info_sort_brand">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_brand">
							<strong>{'店铺信息'}</strong>
						</a>
					</div>
					<div class="accordion-body in in_visable collapse" id="goods_info_area_brand">
						<div class="accordion-inner">
						    <div class="comment-thumb">
        						{if $avatar_img}
        		                	<img src="{RC_Upload::upload_url()}/{$avatar_img}" >
        		                {else}
        		                	<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
        		                {/if}
        					</div>
        					<div class="comment-store-info">
        						{if $comment_info.is_anonymous eq 1 }{'匿名发表'}{else}{$comment_info.user_name}{/if}
        					</div>
            					<div class="comment-goods">
            					   <div class="goods_type">
	    					  	       <label class="control-label store-attr">综合评分：</label>
	    					  	       {section name=loop loop=$comment_info.comment_rank}<i class="fontello-icon-star" style="color:#FF9933;"></i>{/section}
            					   </div>
            					   <p>全部评论： </p>
            					   <p>好评率： </p>
            					</div>
							<button class="btn btn-gebo" type="submit">{'进入店铺评价'}</button>
						</div>
					</div>
				</div>
			</div>

			<div class="foldable-list move-mod-group" id="goods_info_sort_img">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_img">
							<strong>{lang key='goods::category.cat_img'}</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="goods_info_area_img">
						<div class="accordion-inner">
							<label>{lang key='goods::category.lab_upload_picture'}</label>
							<div class="ecjiaf-db">
								<div class="fileupload {if $cat_info.category_img}fileupload-exists{else}fileupload-new{/if} m_t10" data-provides="fileupload">
									<div class="fileupload-preview fileupload-exists thumbnail"><input type="hidden" name="old_img" value="1" />{if $cat_info.category_img}<img src="{$cat_info.category_img}" >{/if}</div>
									<div>
										<span class="btn btn-file">
											<span class="fileupload-new">{lang key='goods::category.select_cat_img'}</span>
											<span class="fileupload-exists">{lang key='goods::category.edit_cat_img'}</span>
											<input type="file" name="cat_img" />
										</span>
										<a class="btn fileupload-exists" {if $cat_info.category_img eq ''} data-dismiss="fileupload" href="javascript:;"  {else} data-toggle="removefile" data-msg="{lang key='goods::category.drop_cat_img_confirm'}" data-href='{url path="goods/admin_category/remove_logo" args="cat_id={$cat_info.cat_id}"}' data-removefile="true"{/if}>{lang key='system::system.drop'}</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="foldable-list move-mod-group" id="goods_info_sort_tvimg">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_tvimg">
							<strong>{lang key='goods::category.tv_cat_img'}</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="goods_info_area_tvimg">
						<div class="accordion-inner">
							<label>{lang key='goods::category.lab_upload_picture'}</label>
							<div class="ecjiaf-db">
								<div class="fileupload {if $cat_info.tv_image}fileupload-exists{else}fileupload-new{/if} m_t10" data-provides="fileupload">
									<div class="fileupload-preview fileupload-exists thumbnail"><input type="hidden" name="old_img" value="1" />{if $cat_info.tv_image}<img src="{$cat_info.tv_image}" >{/if}</div>
									<div>
										<span class="btn btn-file">
											<span class="fileupload-new">{lang key='goods::category.select_cat_img'}</span>
											<span class="fileupload-exists">{lang key='goods::category.edit_cat_img'}</span>
											<input type="file" name="cat_tvimg" />
										</span>
										<a class="btn fileupload-exists" {if $cat_info.tv_image eq ''} data-dismiss="fileupload" href="javascript:;" {else} data-toggle="removefile" data-msg="{lang key='goods::category.drop_cat_img_confirm'}" data-href='{url path="goods/admin_category/remove_logo_tv_image" args="cat_id={$cat_info.cat_id}"}' data-removefile="true" {/if}>{lang key='system::system.drop'}</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- {/block} -->