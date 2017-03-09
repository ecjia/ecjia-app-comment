<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 发表评价
 * @author royalwang
 *
 */
class create_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
    	
    	//如果用户登录获取其session
    	$this->authSession();
		$user_id = $_SESSION['user_id'];
		if ($user_id < 1) {
			return new ecjia_error(100, 'Invalid session');
		}
		
		$rec_id			= $this->requestData('rec_id');
		$object_id 		= $this->requestData('object_id');
		$object_type 	= $this->requestData('object_type', 'goods');
		$user_name 		= $_SESSION['user_name'];
		$order_id 		= $this->requestData('order_id', 0);
		$content 		= $this->requestData('content');
		$rank 			= $this->requestData('rank');
		$is_anonymous 	= $this->requestData('is_anonymous', 1);
		
		$store_id = $this->requestData('store_id');
		$goods_attr = $this->requestData('goods_attr');
		
// 		if ($is_anonymous) {
// 			$len = mb_strlen($user_name);
// 			if ($len > 2) {
// 				$user_name = mb_substr($user_name, 0, 1) . '***' . mb_substr($user_name, $len-1, $len);
// 			} else  {
// 				$user_name = mb_substr($user_name, 0, 1) . '*';
// 			}
// 		}
		if (empty($rec_id)) {
			return new ecjia_error('invalid_parameter', '参数错误！');
		}
		
		$save_path = 'data/comment/'.$object_id;
		$upload = RC_Upload::uploader('image', array('save_path' => $save_path, 'auto_sub_dirs' => true));
		
// 		$order_db = RC_Model::model('orders/order_order_infogoods_viewmodel');
// 		$order_db->view = array(
// 			'order_goods' => array(
// 				'type'	=> Component_Model_View::TYPE_LEFT_JOIN,
// 				'alias'	=> 'og',
// 				'on'	=> 'oi.order_id = og.order_id'
// 			),
// 		);
// 		$order_info = $order_db->where(array('oi.user_id' => $user_id, 'og.rec_id' => $rec_id))->find();
		
		$order_info = RC_DB::table('order_info as oi')
			->leftJoin('order_goods as og', RC_DB::raw('oi.order_od'), '=', RC_DB::raw('og.order_id'))
			->where(RC_DB::raw('oi.user_id'), $user_id)
			->where(RC_DB::raw('og.rec_id'), $rec_id)
			->pluck();
	
		if (empty($order_info)) {
			return new ecjia_error('order_error', '订单信息不存在！');
		}
		
// 		$comment_info = RC_Model::model('comment/comment_model')->where(array('rec_id' => $rec_id, 'user_id' => $_SESSION['user_id'], 'comment_type' => 0))->find();

// 		$comment_info = RC_DB::table('comment')->where('rec_id', $rec_id)->where('user_id', $user_id)->where('comment_type', $object_type)->pluck();
// 		if (empty($comment_info) && empty($content) && empty($rank)) {

		if (empty($content) && empty($rank)) {
			return new ecjia_error('invalid_parameter', '参数错误！');
		}
		$save_path = 'data/comment/'.RC_Time::local_date('Ym');
		$upload = RC_Upload::uploader('image', array('save_path' => $save_path, 'auto_sub_dirs' => true));
		
		if (!empty($_FILES['picture'])) {
			$count = count($_FILES['picture']['name']);
			$type_array = array('image/jpeg', 'image/png', 'image/x-png', 'image/pjpeg');
			for ($i=0; $i<$count; $i++) {
				if (!in_array($_FILES['picture']['type'][$i], $type_array)) {
					return new ecjia_error('picture_type_error', '图片类型错误');
				}
				//限制大小2M
				if ($_FILES['picture']['size'][$i] > 2097152) {
					return new ecjia_error('picture_size_error', '超出限制文件大小');
				}
			}
		}
		$image_info	= $upload->batch_upload($_FILES);
		
// 		if (empty($comment_info)) {
			/* 评论是否需要审核 */
			$status = 1 - ecjia::config('comment_check');
// 			$goods_id = RC_Model::model('orders/order_goods_model')->where(array('rec_id' => $rec_id))->get_field('goods_id');
// 			$goods_id = RC_DB::table('order_goods')->where('rec_id', $rec_id)->lists('goods_id');
			
			$data = array(
				'comment_type'	=> $object_type,
				'id_value'		=> $object_id,
				'user_name'		=> $user_name,
				'is_anonymous'  => $is_anonymous,
				'content'		=> $content,
				'comment_rank'	=> $rank,
				'add_time'		=> RC_Time::gmtime(),
				'ip_address'	=> RC_Ip::client_ip(),
				'status'		=> $status,
				'parent_id'		=> 0,
				'user_id'		=> $user_id,
				'store_id'		=> $store_id,
				'order_id'   	=> $rec_id,
				'goods_attr'	=> $goods_attr
			);
			$comment_id = RC_Model::model('comment/comment_model')->insert($data);
// 		} else {
// 			$comment_id = $comment_info['comment_id'];
// 		}

		foreach ($image_info as $key => $val) {
			if (!empty($val)) {
				$image_url	= $upload->get_position($image_info[$key]);
				$data = array(
					'object_app'	=> 'ecjia.comment',
					'object_group'	=> 'comment',
					'object_id'		=> $comment_id,
					'file_path'		=> $image_url,
					'is_image'		=> 1,
					'user_id'		=> $user_id,
					'add_time'		=> RC_Time::gmtime(),
					'add_ip'		=> RC_Ip::client_ip(),
				);
				RC_DB::table('term_attachment')->insert($data);
			}
		}
		return array();
	}
}

// end