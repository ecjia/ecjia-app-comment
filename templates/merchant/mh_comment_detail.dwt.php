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
						<div class="comment-thumb">
							<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg" >
						</div>
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
				
		        <div id="accordionOne">
		            <div class="panel panel-info">
		                <div class="panel-heading">
		                    <a data-toggle="collapse" data-parent="#accordionOne" href="#collapseOne" class="accordion-toggle">
		                        <span class="glyphicon"></span>
		                        <h4 class="panel-title">评论内容</h4>
		                    </a>
		                </div>
		                <div id="collapseOne" class="panel-collapse collapse in">
	                         <div class="panel-body">
	                              <div class="text-right">
	                                 <div class="comment-all-right-thumb">
											<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg" >
									 </div>
						  			 <div class="comment-thumb-details">
										<h1>admin</h1>
										<p>2017-03-08 11:40:00</p><br>
									 </div>
									  <p>感谢您对本店的支持！我们会更加的努力，为您提供做优质的服务感谢您对本店的支持！</p>
	                              </div>
	                              <hr>
	                             <div class="text-left">
	                                 <div class="comment-all-left-thumb">
										<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg" >
									 </div>
						  			 <div class="comment-thumb-details">
										<h1>admin</h1>
										<p>2017-03-08 11:40:00</p><br>
									 </div>
									  <p>感谢您对本店的支持！我们会更加的努力，为您提供做优质的服务感谢您对本店的支持！</p>
	                              </div>
	                          </div>
		                </div>
		              </div>
		        </div>
		        
		       	<div class="comment-reply">
					<div class="panel-body">
						<h4>回复评论</h4>
						<form class="form-horizontal" action='{$form_action}' method="post" name="theForm">
							 <div class="reply-content">
                                 <h5 class="reply-title">回复内容:</h5>
                                 <div class="col-lg-10">
                                      <textarea class="form-control" id="appeal_content" name="appeal_content" placeholder="请输入申诉理由" ></textarea>
                                      <p class="help-block">提示：此条评论已有回复，如果继续回复将更新原来回复内容</p>
                                 </div>
                             </div>
                             <div class="text-right">
								<input id="is_ok" name="is_ok" value="1" type="checkbox">
								<label for="is_ok">邮件通知</label>
								<div class="col-lg-4">
									<input class="form-control" placeholder="请输入电子邮箱" type="text" name="reply_email">
								</div>
                             </div>
							 <input type="hidden" name="goods_id" value="" />
							 <input type="hidden" name="user_id" value=""/>
							 <div class="reply-btn">
						   		<input class="btn btn-info" type="submit" value="回复"/>
						     </div>
						</form>
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