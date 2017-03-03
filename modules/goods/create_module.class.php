<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 某商品的所有评论
 * @author royalwang
 *
 */
class create_module extends api_front implements api_interface
{
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request)
    {	
		$this->authSession();
		if ($_SESSION['user_id'] <= 0) {
			return new ecjia_error(100,'Invalid session');
		}
		$email			= $_SESSION['email'];
		$id				= $this->requestData('object_id');
		$user_name		= $this->requestData('user_name');
		$user_name		= empty($user_name) ? $_SESSION['user_name'] : $user_name;
		$comment_type	= $this->requestData('object_type', 'goods');
		$type			= $comment_type == 'goods' ? 0 : 1;
		$order_id		= $this->requestData('order_id');
		$content		= $this->requestData('content');
		$rank			= $this->requestData('rank', 5);
		
		$result = true;
		if (!isset($id)) {
			$result = new ecjia_error('invalid_comments', __('无效的评论内容！'));
		} 
		
		if (empty($email)) {
			$code = '';
			$charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$charset_len = strlen($charset) - 1;
			for ($i = 0; $i < 6; $i++) {
				$code .= $charset[rand(1, $charset_len)];
			}
			$email = $code.'@'.$_SESSION['user_id'].'.com';
		}
		
		
		/* 没有验证码时，用时间来限制机器人发帖或恶意发评论 */
		if (!isset($_SESSION['send_time'])) {
			$_SESSION['send_time'] = 0;
		}

		$cur_time = RC_Time::gmtime();
		// 小于30秒禁止发评论
		if (($cur_time - $_SESSION['send_time']) < 30 && isset($_SESSION['send_time']))  {
			$result = new ecjia_error('cmt_spam_warning', __('您至少在30秒后才可以继续发表评论！'));
		} else {
// 			$factor = intval(ecjia::config('comment_factor'));
			$factor = 4;
			if ($type == 0 && $factor > 0) {
				/* 只有商品才检查评论条件 */
				switch ($factor) {
					case COMMENT_LOGIN :
						if ($_SESSION['user_id'] == 0) {
							$result = new ecjia_error('comment_login', __('只有注册会员才能发表评论，请您登录后再发表评论！'));
						}
						break;
					case COMMENT_CUSTOM :
						if ($_SESSION['user_id'] > 0) {
							$db = RC_Loader::load_app_model('order_info_model', 'orders');
							$where = array(
								'user_id' => $_SESSION['user_id'],
								"(order_status ='".OS_CONFIRMED."' OR order_status = '".OS_SPLITED."')",
								"(pay_status ='".PS_PAYED."' OR pay_status ='".PS_PAYING."')",
								"(shipping_status ='".SS_SHIPPED."' OR shipping_status = '".SS_RECEIVED."')"
							);
							$tmp = $db->where($where)->get_field('order_id');
							if (empty($tmp)) {
								$result = new ecjia_error('comment_custom', __('评论失败。只有在本店购买过商品的注册会员才能发表评论。'));
							}
						} else {
							$result = new ecjia_error('comment_custom', __('评论失败。只有在本店购买过商品的注册会员才能发表评论。'));
						}
						break;

					case COMMENT_BOUGHT :
						if ($_SESSION['user_id'] > 0) {
							$db = RC_Loader::load_app_model('order_order_infogoods_viewmodel', 'orders');
							$where = array(
								'oi.user_id' => $_SESSION['user_id'],
								'og.goods_id' => $id,
								"(oi.order_status = '".OS_CONFIRMED."' OR oi.order_status = '".OS_SPLITED."')",
								"(oi.pay_status = '".PS_PAYED."' OR oi.pay_status = '".PS_PAYING."')",
								"(oi.shipping_status = '".SS_SHIPPED."' OR oi.shipping_status ='".SS_RECEIVED."')"
							);
							$tmp = $db->where($where)->get_field('order_id');
							if (empty($tmp)) {
								$result = new ecjia_error('comment_brought', __('评论失败。只有购买过此商品的注册用户才能评论该商品。'));
							}
						} else {
							$result = new ecjia_error('comment_brought', __('评论失败。只有购买过此商品的注册用户才能评论该商品。'));
						}
						break;
					case 4:
						if ($_SESSION['user_id'] > 0) {
							//判断是否已评论
							$dbview = RC_Loader::load_app_model('order_goods_comment_viewmodel', 'orders');
							$dbview->view = array(
								'term_relationship' => array(
									'type' 		=> Component_Model_View::TYPE_LEFT_JOIN,
									'alias' 	=> 'tr',
									'on'		=> 'tr.object_id = og.rec_id and object_type = "ecjia.comment"'
								),
							);
							$res = $dbview->where(array('og.order_id' => $order_id, 'og.goods_id' => $id))->find();
							
							if (!empty($res['relation_id'])) {
								$result = new ecjia_error('comment_already', __('您已评论过该商品！'));
							}
							if (empty($res)) {
								$result = new ecjia_error('comment_not_brought', __('您还没有购买过此商品！'));
							}
						} else {
							$result = new ecjia_error('comment_brought', __('评论失败。只有购买过此商品的注册用户才能评论该商品。'));
						}
						break;
				}
			}
			
			/* 无错误就保存留言 */
			if (is_ecjia_error($result)) {
				return $result;
			} else {
				
				$comment_info = array(
					'email'		=> $email,
					'user_name' => $user_name,
					'type'		=> $type,
					'id'		=> $id,
					'content'	=> $content,
					'rank'		=> $rank
				);
				$comment_id = add_comment($comment_info);
				
				$db_term_relation = RC_Loader::load_model('term_relationship_model');
				$term_relation = array(
					'object_type'	=> 'ecjia.comment',
					'object_group'	=> 'comment',
					'object_id'		=> $res['rec_id'],
					'item_key1'		=> 'goods_id',
					'item_value1'	=> $id,
					'item_key2'		=> 'comment_id',
					'item_value2'	=> $comment_id
				);
				$db_term_relation->insert($term_relation);
				$message = '';
				$comment_award = 0;
				if (ecjia::config('comment_award_open')) {
					$comment_award_rules = ecjia::config('comment_award_rules');
					$comment_award_rules = unserialize($comment_award_rules);
					$comment_award = isset($comment_award_rules[$_SESSION['user_rank']]) ? $comment_award_rules[$_SESSION['user_rank']] : ecjia::config('comment_award');
					
					RC_Api::api('user', 'account_change_log', array('user_id' => $_SESSION['user_id'], 'pay_points' => $comment_award, 'change_desc' => '评论送积分'));
					$message = '评论成功！并获得'.$comment_award.ecjia::config('integral_name').'！';
				}

				return array('data' => array('comment_award' => $comment_award, 'label_comment_award' => $message, 'label_award' => ecjia::config('integral_name')));

			}
		}
	}
}

/**
 * 添加评论内容
 *
 * @access  public
 * @param   object  $cmt
 * @return  void
 */
function add_comment($comment_info) {
	/* 评论是否需要审核 */
	$status = 1 - ecjia::config('comment_check');

	$user_id = empty($_SESSION['user_id']) ? 0 : $_SESSION['user_id'];
	$email = $comment_info['email'];
	$user_name = $comment_info['user_name'];
	$email = htmlspecialchars($email);
// 	$user_name = htmlspecialchars($user_name);
	
	/* 保存评论内容 */
	$db = RC_Loader::load_app_model('comment_model', 'comment');
	$data = array(
		'comment_type' => $comment_info['type'],
		'id_value' => $comment_info['id'],
		'email' => $email,
		'user_name' => $user_name,
		'content' => $comment_info['content'],
		'comment_rank' => $comment_info['rank'],
		'add_time' => RC_Time::gmtime(),
		'ip_address' => RC_Ip::client_ip(),
		'status' => $status,
		'parent_id' => 0,
		'user_id' => $user_id
	);
	$result = $db->insert($data);

	return $result;
}

// end