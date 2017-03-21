<?php
use Royalcms\Component\FormBuilder\Fields\RepeatedType;
/**
 * ECJIA 用户评论管理程序
*/

defined('IN_ECJIA') or exit('No permission resources.');

class admin extends ecjia_admin {
	private $db_comment;
	private $db_goods;
	
	public function __construct() {
		parent::__construct();
		
		$this->db_comment = RC_Loader::load_app_model('comment_model');
		$this->db_goods	  =	RC_Loader::load_app_model('comment_goods_model');
		
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Style::enqueue_style('chosen');
		
		RC_Script::enqueue_script('jquery.toggle.buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/jquery.toggle.buttons.js'));
		RC_Style::enqueue_style('bootstrap-toggle-buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/bootstrap-toggle-buttons.css'));
		
		RC_Script::enqueue_script('comment_manage', RC_App::apps_url('statics/js/comment_manage.js', __FILE__), array(), false, false);
		RC_Style::enqueue_style('comment', RC_App::apps_url('statics/css/comment.css', __FILE__));
		//RC_Style::enqueue_style('start', RC_App::apps_url('statics/css/start.css', __FILE__));
		
		RC_Script::localize_script('comment_manage', 'js_lang', RC_Lang::get('comment::comment_manage.js_lang'));
	}
	
	/**
	 * 获取商品评论列表
	 */
	public function init() {
		$this->admin_priv('comment_manage');
		
		$_GET['list'] = !empty($_GET['list']) ?  $_GET['list'] : 1;
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('comment::comment_manage.goods_comment_list')));
		$this->assign('ur_here', RC_Lang::get('comment::comment_manage.goods_comment'));
		
		$this->assign('action_link', array('text' => RC_Lang::get('comment::comment_manage.check_trash_comment'), 'href'=> RC_Uri::url('comment/admin/trash', array('list' => 2))));
		
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('comment::comment_manage.overview'),
			'content'	=> '<p>' . RC_Lang::get('comment::comment_manage.goods_comment_list_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('comment::comment_manage.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:商品评论" target="_blank">'.RC_Lang::get('comment::comment_manage.about_goods_comment_list').'</a>') . '</p>'
		);
		
		$list = $this->get_comment_list();
		$this->assign('comment_list', $list);
		
		$this->assign('select_status', $_GET['select_status']);
		$this->assign('select_rank', $_GET['select_rank']);
		$this->assign('select_img', $_GET['select_img']);
		
		$this->assign('form_action', RC_Uri::url('comment/admin/batch'));
		$this->assign('form_search', RC_Uri::url('comment/admin/init'));
		//$this->assign('dropback_comment', $this->admin_priv('dropback_comment', '', false));
		$this->display('comment_list.dwt');		
	}
	
	/**
	 * 管理员快捷回复评论处理
	 */
	public function quick_reply() {
		$this->admin_priv('comment_manage');
		
		$list 			  = !empty($_GET['list']) ? $_GET['list'] : 1;
		$comment_id 	  = $_GET['comment_id'];
		$reply_content    = $_GET['reply_content'];
		$status			  = $_GET['status'];
		$db_comment_reply = RC_DB::table('comment_reply');
		if(empty($reply_content)){
			$reply_content='感谢您对本店的支持！我们会更加的努力，为您提供更优质的服务。';
		}
		$data = array(
				'comment_id' 	=> $comment_id,
				'content' 		=> $reply_content,
				'user_type'		=> 'admin',
				'user_id'		=> $_SESSION['admin_id'],
				'add_time'		=> RC_Time::gmtime(),
		);
		$db_comment_reply->insertGetId($data);
		if (isset($_GET['status']) && (!empty($_GET['status']) || $_GET['status'] == '0')) {
			if ($list == 3) {
				$pjaxurl = RC_Uri::url('comment/admin/store_goods_comment_list', array('status' => $_GET['status']));
			} else {
				$pjaxurl = RC_Uri::url('comment/admin/init', array('status' => $_GET['status']));
			}
		} else {
			if ($list == 3) {
				$pjaxurl = RC_Uri::url('comment/admin/store_goods_comment_list');
			} else {
				$pjaxurl = RC_Uri::url('comment/admin/init');
			}
		}
		return $this->showmessage('回复成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, $pjaxurl);
	}
	
	/**
	 * 获取没有回复的评论列表
	 */
	public function init_bak() {
		$this->admin_priv('comment_manage');
	
		if(isset($_GET['status']) && $_GET['status'] > 1) {
			$this->admin_priv('comment_delete');
		}
	
		$here = $_GET['type'] == '1' ? RC_Lang::get('comment::comment_manage.goods_comment') : RC_Lang::get('comment::comment_manage.article_comment');
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($here));
		$this->assign('ur_here', $here);
	
		if ($_GET['type'] == 1) {
			$this->assign('action_link', array('text' => RC_Lang::get('comment::comment_manage.add_goods_comment'), 'href'=> RC_Uri::url('comment/admin/add', array('type' => 1))));
		} else {
			$this->assign('action_link', array('text' => RC_Lang::get('comment::comment_manage.add_article_comment'), 'href'=> RC_Uri::url('comment/admin/add', array('type' => 2))));
		}
	
		if ($_GET['type'] == 1) {
			ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('comment::comment_manage.overview'),
			'content'	=> '<p>' . RC_Lang::get('comment::comment_manage.goods_comment_list_help') . '</p>'
					));
				
			ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('comment::comment_manage.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:商品评论" target="_blank">'.RC_Lang::get('comment::comment_manage.about_goods_comment_list').'</a>') . '</p>'
					);
		} else {
			ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('comment::comment_manage.overview'),
			'content'	=> '<p>' . RC_Lang::get('comment::comment_manage.article_comment_list_help') . '</p>'
					));
				
			ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('comment::comment_manage.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章评论" target="_blank">'.RC_Lang::get('comment::comment_manage.about_article_comment_list').'</a>') . '</p>'
					);
		}
	
		$list = $this->get_comment_list();
		$this->assign('comment_list', $list);
	
		$this->assign('form_action', RC_Uri::url('comment/admin/batch'));
		$this->assign('form_search', RC_Uri::url('comment/admin/init'));
		$this->assign('dropback_comment', $this->admin_priv('dropback_comment', '', false));
	
		$this->display('CopyOfcomment_list.dwt');
	}
	
	
	
	/**
	 * 添加评论
	 */
	public function add() {
		$this->admin_priv('comment_add', ecjia::MSGTYPE_JSON);
		
		$type = !empty($_GET['type']) ? intval($_GET['type']) : 1;
		if ($type == 1) {//商品评论
			$here = RC_Lang::get('comment::comment_manage.goods_comment');
			$url = RC_Uri::url('comment/admin/init', 'type=1');
			
			ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($here, $url));
			ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('comment::comment_manage.add_goods_comment')));
			
			$this->assign('ur_here', RC_Lang::get('comment::comment_manage.add_goods_comment'));
			$this->assign('action_link', array('text' => RC_Lang::get('comment::comment_manage.goods_comment_list'), 'href'=> RC_Uri::url('comment/admin/init', array('type' => 1))));
			
			$this->display('comment_add_goods.dwt');
		} else {//文章评论
			$here = RC_Lang::get('comment::comment_manage.article_comment');
			$url = RC_Uri::url('comment/admin/init', 'type=2');
			
			ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($here, $url));
			ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('comment::comment_manage.add_article_comment')));
			
			$this->assign('ur_here', RC_Lang::get('comment::comment_manage.add_article_comment'));
			$this->assign('action_link', array('text' => RC_Lang::get('comment::comment_manage.article_comment_list'), 'href'=> RC_Uri::url('comment/admin/init', array('type' => 2))));
			
			$this->display('comment_add_article.dwt');
		}
	}
		
	/**
	 * 添加评论处理
	 */
	public function insert() {
		$this->admin_priv('comment_add', ecjia::MSGTYPE_JSON);
		
		$user_id   		= !empty($_POST['user_id']) 	 ? intval($_POST['user_id']) 	  : 0;
		$user_name 		= !empty($_POST['user_name']) 	 ? $_POST['user_name'] 			  : '';
		$content 		= !empty($_POST['content']) 	 ? trim($_POST['content']) 		  : '';
		$comment_rank 	= !empty($_POST['comment_rank']) ? intval($_POST['comment_rank']) : 0;
		$goods_id 		= !empty($_POST['goods_id']) 	 ? intval($_POST['goods_id']) 	  : 0;
		$article_id 	= !empty($_POST['article_id']) 	 ? intval($_POST['article_id'])   : 0;
		$article_name 	= !empty($_POST['article_name']) ? $_POST['article_name'] 		  : '';
		
		if ($user_name) {
			$user_info = RC_Api::api('user', 'user_info', $user_id);
			$email = !empty($user_info['email']) ? $user_info['email'] : '';
		} else {
			$email = !empty($_POST['email']) ? $_POST['email'] : '';
		}
		
		if ($_GET['type'] == 1) {
			$allid   = $goods_id;
			$comment_type = 0;
		} else {
			$allid = $article_id;
			$comment_type = 1;
		}
		$add_time = RC_Time::gmtime();
		
		$data = array(
			'id_value'		=>	$allid,
			'email'			=>	$email,
			'user_name'		=>	$user_name,
			'content'		=>	$content,
			'comment_rank'	=>  $comment_rank,
			'user_id'		=>	$user_id,
			'add_time'		=>  $add_time,
			'status'		=>  1,
			'comment_type'	=>  $comment_type,
		);

		$this->db_comment->comment_manage($data);
		return $this->showmessage(RC_Lang::get('comment::comment_manage.add_comment_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('comment/admin/init', array('type' => $_GET['type']))));
	}
	
	//获取会员名称列表
	public function get_user_name() {
		$keyword = !empty($_POST['keyword']) ? trim($_POST['keyword']) : '';
		$data = RC_Api::api('user', 'get_user_list', array('keywords' => $keyword));
		
		$result = array();
		if (!empty($data)) {
			foreach ($data as $key => $row) {
				array_push($result, array('value' => $row['user_id'], 'user_name' => $row['user_name']));
			}
		}
		return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('user' => $result));
	}
	
	
	//获取商品列表
	public function get_goods_name() {
		$keywords = !empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
		$data = RC_Api::api('goods', 'get_goods_list', array('keyword' => $keywords));

		$result = array();
		if (!empty($data)) {
			foreach ($data as $key => $row) {
				array_push($result, array('value' => $row['goods_id'], 'goods_name'=> $row['goods_name']));
			}
		}
		return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('goods' => $result));
	}
	
	//获取文章列表
	public function get_article_name() {
		$keywords = !empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
		$data = RC_Api::api('article', 'article_list', array('keywords' => $keywords, 'is_page' => false));
		
		$result = array();
		if (!empty($data['arr'])) {
			foreach ($data['arr'] as $key => $row) {
				array_push($result, array('value' => $row['article_id'], 'title'=> $row['title']));
			}
		}
		return $this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('article' => $result));
	}
	
	/**
	 * 回复用户评论(同时查看评论详情)
	 */
	public function reply() {		
		$this->admin_priv('comment_update');
		
		$admin_ip     	= RC_Ip::client_ip();
		$comment_info 	= array();
		$reply_info   	= array();
		$id_value     	= array();
		$where 			= array();
		
		$where['comment_id'] = !empty($_GET['comment_id']) ? $_GET['comment_id'] : 0;
		$comment_info = RC_DB::TABLE('comment')->where('comment_id', $where['comment_id'])->first();

		if (empty($comment_info)) {
			return $this->showmessage(RC_Lang::get('comment::comment_manage.no_comment_info'), ecjia::MSGTYPE_HTML | ecjia::MSGSTAT_ERROR );
		}
		/* 获取评论详细信息并进行字符处理 */
		$comment_info['content']  = str_replace('\r\n', '<br />', htmlspecialchars($comment_info['content']));
		$comment_info['content']  = nl2br(str_replace('\n', '<br />', $comment_info['content']));
		$comment_info['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $comment_info['add_time']);

		/* 获得评论回复内容 */
		$reply_info = RC_DB::TABLE('comment_reply')->where('comment_id', $where['comment_id'])
            		->select('content', 'add_time', 'user_id')
            		->get();

		if (!empty($reply_info)) {
			$reply_info['content']  = nl2br(htmlspecialchars($reply_info['content']));
			$reply_info['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $reply_info['add_time']);
		}
		/* 取得评论的对象(文章或者商品) */
		if ($comment_info['comment_type'] == '0') {
			ecjia_screen::get_current_screen()->add_help_tab(array(
				'id'		=> 'overview',
				'title'		=> RC_Lang::get('comment::comment_manage.overview'),
				'content'	=> '<p>' . RC_Lang::get('comment::comment_manage.goods_comment_detail_help') . '</p>'
			));
			ecjia_screen::get_current_screen()->set_help_sidebar(
				'<p><strong>' . RC_Lang::get('comment::comment_manage.more_info') . '</strong></p>' .
				'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:商品评论#.E6.9F.A5.E7.9C.8B.E5.8F.8A.E5.9B.9E.E5.A4.8D" target="_blank">'.RC_Lang::get('comment::comment_manage.about_goods_comment_detail').'</a>') . '</p>'
			);
			//TODO
			$id_value = $this->db_goods->goods_field(array('goods_id' => $comment_info['id_value']), 'goods_name');
			$comment_info['url'] = RC_Uri::url('goods/admin/preview', array('id' => $comment_info['id_value']));
				
			$here = RC_Lang::get('comment::comment_manage.comment_list');
			$url = RC_Uri::url('comment/admin/init', array('type' => 1));
			$comment_info['type'] = '1';
			$this->assign('id_value', $id_value); 		//评论的对象
		} else {
			ecjia_screen::get_current_screen()->add_help_tab(array(
				'id'		=> 'overview',
				'title'		=> RC_Lang::get('comment::comment_manage.overview'),
				'content'	=> '<p>' . RC_Lang::get('comment::comment_manage.article_comment_detail_help') . '</p>'
			));
			ecjia_screen::get_current_screen()->set_help_sidebar(
				'<p><strong>' . RC_Lang::get('comment::comment_manage.more_info') . '</strong></p>' .
				'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章评论#.E6.9F.A5.E7.9C.8B.E5.8F.8A.E5.9B.9E.E5.A4.8D" target="_blank">'.RC_Lang::get('comment::comment_manage.about_article_comment_detail').'</a>') . '</p>'
			);
			$id_value = RC_Api::api('article', 'article_info', array('id' => $comment_info['id_value']));
			$comment_info['url'] = RC_Uri::url('article/admin/preview', array('id' => $comment_info['id_value']));
				
			$here = RC_Lang::get('comment::comment_manage.article_comment');
			$url = RC_Uri::url('comment/admin/init', array('type' => 2));
			$comment_info['type'] = '2';
// 			$this->assign('id_value', $id_value['title']); //评论的对象
		}

		/*获取用户头像*/
		$avatar_img = RC_Upload::upload_url().'/'.RC_DB::TABLE('users')->where('user_id', $comment_info['user_id'])->pluck('avatar_img');

		/* 管理员回复内容*/
		$replay_admin_list = RC_DB::TABLE('comment_reply')
                		->where('comment_id', $comment_info['comment_id'])
                		->select('content', 'add_time', 'user_id')
                		->get();
		foreach ($replay_admin_list as $key => $val) {
		    $replay_admin_list[$key]['add_time_new'] = RC_Time::local_date(ecjia::config('time_format'), $val['add_time']);   
		    $staff_info = RC_DB::TABLE('admin_user')->where('user_id', $val['user_id'])->first(); //管理员信息
		    $replay_admin_list[$key]['user_name'] = $staff_info['user_name'];       
		    $replay_admin_list[$key]['staff_img']  =  RC_App::apps_url('statics/images/ecjia_avatar.jpg', __FILE__);
		};
		//获取评论图片
		$comment_pic_list = RC_DB::TABLE('term_attachment')->where('object_id', $comment_info['comment_id'])->select('file_path')->get();

		$shop_info['logo'] = RC_DB::TABLE('merchants_config')
                		->where('store_id', $comment_info['store_id'])
                		->where('code', 'shop_logo')
                		->pluck('value');

		$shop_info['name'] = RC_DB::TABLE('store_franchisee')
                		->where('store_id', $comment_info['store_id'])
                		->pluck('merchants_name');

		$shop_info['amount'] = RC_DB::TABLE('comment')->where('store_id', $comment_info['store_id'])->count();

		$shop_info['logo_img']  = RC_Upload::upload_url().'/'.$shop_info['logo'];
		//统计该用户其他待审核评论
		$nochecked = RC_DB::TABLE('comment')
		              ->where('store_id', $comment_info['store_id'])
		              ->where('comment_id', '!=', $comment_info['comment_id'])
		              ->where('status', 0)
		              ->count();

		$other_comment = RC_DB::TABLE('comment')
            		->where('store_id', $comment_info['store_id'])
            		->where('comment_id', '!=', $comment_info['comment_id'])
            		->where('status', 0)
            		->take(4)
            		->get();

		//计算好评率、综合评分
		$shop_info['comment_number'] = RC_DB::table('comment')
                		      ->select(RC_DB::raw('count(*) as "all"'), RC_DB::raw('SUM(IF(comment_rank > 3, 1, 0)) as "good"'))
                		      ->where('status', '<>', 3)
                		      ->where('parent_id', 0)
                		      ->where('comment_type', 0)
                		      ->where('store_id', $comment_info['store_id'])
                		      ->first();
		
		if ($shop_info['comment_number']['all'] != 0) {
		    if ($shop_info['comment_number']['good'] == 0) {
		        $shop_info['comment_percent'] = 100;
		    } else {
		        $shop_info['comment_percent'] = round(($shop_info['comment_number']['good'] / $shop_info['comment_number']['all']) * 100);
		    }
		} else {
		    $shop_info['comment_percent'] = 100;
		}
		
		if ($shop_info['comment_percent'] == '100') {
		    $shop_info['composite'] = 5;
		} elseif (($shop_info['comment_percent'] >= 95) && ($shop_info['comment_percent'] < 100)) {
		    $shop_info['composite'] = 4;
		} else {
		    $shop_info['composite'] = 3;
		}

		/* 模板赋值 */
		$this->assign('comment_info', $comment_info); 		//评论信息
		$this->assign('replay_admin_list', $replay_admin_list); 		//管理员回复信息
		$this->assign('avatar_img', $avatar_img);     //用户头像
		$this->assign('admin_info', $staff_info);   //管理员信息
		$this->assign('reply_info', $reply_info);   //回复的内容
		$this->assign('admin_ip', $admin_ip);		//当前管理员IP获取
		$this->assign('comment_pic_list', $comment_pic_list);     //评论图片
		$this->assign('shop_info', $shop_info);     //店铺信息
		$this->assign('nochecked', $nochecked);     
		$this->assign('other_comment', $other_comment);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($here, $url));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('comment::comment_manage.comment_info')));
		
		$this->assign('ur_here', '评论详情');
		$this->assign('action_link', array('text' => $here, 'href' => $url));
		$this->assign('form_action', RC_Uri::url('comment/admin/action'));
		$this->assign('store_url', RC_Uri::url('comment/admin/store_goods_comment_list', array('store_id' => $comment_info['store_id'], 'list' => 3)));
		
		$this->display('comment_info.dwt');
	}
	
	/**
	 * 处理 回复用户评论
	 */
	public function action() {		
		$this->admin_priv('comment_update', ecjia::MSGTYPE_JSON);

		$ip = RC_Ip::client_ip();
		$comment_id 	= $_POST['comment_id'];
		$reply_content  = $_POST['reply_content'];

		if(empty($reply_content)){
			 return $this->showmessage('请输入回复内容', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		$data = array(
			'comment_id' 	=> $comment_id,
			'content' 		=> $reply_content,
			'user_type'		=> 'admin',
			'user_id'		=> $_SESSION['admin_id'],
			'add_time'		=> RC_Time::gmtime(),
		);
		$email_status = $_POST['is_ok'];
		if($email_status){
			$reply_email = $_POST['reply_email'];
			$comment_info = RC_DB::TABLE('comment')->where('comment_id', $comment_id)->select('user_name', 'content')->first();
			$user_name 			= $comment_info['user_name'];
			$message_content 	= $comment_info['content'];
			$message_note 		= $reply_content;
			
			if(!empty($reply_email)){
				RC_DB::table('comment_reply')->insertGetId($data);
				
				$tpl_name = 'user_message';
				$template   = RC_Api::api('mail', 'mail_template', $tpl_name);
				if (!empty($template)) {
					$this->assign('user_name', 	$user_name);
					$this->assign('message_content', $message_content);
					$this->assign('message_note',   $message_note);
					$this->assign('shop_name',   ecjia::config('shop_name'));
					$this->assign('send_date',   RC_Time::local_date(ecjia::config('date_format')));
					RC_Mail::send_mail('', $reply_email, $template['template_subject'], $this->fetch_string($template['template_content']), $template['is_html']);
				}
			}else{
				return $this->showmessage('请输入邮件地址', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}else{
			RC_DB::table('comment_reply')->insertGetId($data);
		}
	    return $this->showmessage('回复成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('comment/admin/reply', array('comment_id' => $comment_id))));
	}
	
	/**
	 * 列表更新评论的状态为批准或者驳回
	 */
	public function check() {
		$this->admin_priv('comment_update', ecjia::MSGTYPE_JSON);
		
		$list		= !empty($_GET['list']) ? $_GET['list'] : 3;
		$id		 	= !empty($_GET['comment_id']) 		? intval($_GET['comment_id'])		: 0;
		$page		= !empty($_GET['page']) 		? intval($_GET['page'])		: 1;
		$allow 		= !empty($_POST['check']) 	? $_POST['check']			: '';
		$status		= $_POST['status'];
		$last       = !empty($_GET['last']) ? $_GET['last'] : '';
		
		if ($status === '') {
			if ($list == 3) {
			    if ($last == 'reply') {
				    $pjaxurl = RC_Uri::url('comment/admin/reply', array('comment_id' => $id));
			    } else {
				    $pjaxurl = RC_Uri::url('comment/admin/init', array('list' => 1));
			    }
			} else {
				$pjaxurl = RC_Uri::url('comment/admin/init', array('page' => $page));
			}
		} else {
			if ($list == 3) {
			    if ($last == 'reply') {
				    $pjaxurl = RC_Uri::url('comment/admin/reply', array('comment_id' => $id));
			    } else {
				    $pjaxurl = RC_Uri::url('comment/admin/init', array('list' => 1));
			    }
			} else {
				$pjaxurl = RC_Uri::url('comment/admin/init', array('status' => intval($status), 'page' => $page));
			}
		}
		
		$db_comment = RC_DB::table('comment');
		
		if ($allow == 'allow') {
			/*允许评论显示 */
			$data = array(
				'status'     => '1'
			);
			$db_comment->where('comment_id', $id)->update($data);
			$message = RC_Lang::get('comment::comment_manage.show_success');
		} elseif ($allow == 'forbid') {
			/*禁止评论显示 */
			$data = array(
				'status'     => '0'
			);
			$db_comment->where('comment_id', $id)->update($data);
		}
		 elseif ($allow == "trashed_comment") {
			/* 移到回收站 */
			$data = array(
				'status'     => '3'
			);
			$db_comment->where('comment_id', $id)->update($data);
			$message = RC_Lang::get('comment::comment_manage.trash_success');
		} 
		return $this->showmessage($message, ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => $pjaxurl));
	}
	
	/**
	 * 删除某一条评论
	 */
	public function remove() {
		$this->admin_priv('comment_delete', ecjia::MSGTYPE_JSON);
		$id = intval($_GET['id']);
		$res = $this->db_comment->comment_delete("comment_id=".$id." or parent_id=".$id);
		
		ecjia_admin::admin_log('', 'remove', 'users_comment');
		return $this->showmessage(RC_Lang::get('comment::comment_manage.drop_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
	
	/**
	 * 批量删除用户评论
	 */
	public function batch() {
		$this->admin_priv('comment_delete', ecjia::MSGTYPE_JSON);
		$action = isset($_GET['sel_action']) ? trim($_GET['sel_action']) : 'deny';
		
		$list = !empty($_GET['list']) ? $_GET['list'] : 1;
		$comment_ids = explode(',', $_POST['checkboxes']);
		$db_comment = RC_DB::table('comment'); 
		
		if (!empty($comment_ids)) {
			switch ($action) {
				case 'allow' :
					$data = array(
						'status' => '1'
					);
				break;

				case 'deny' :
					$data = array(
						'status' => '0'
					);
				break;
				
				case 'trashed_comment' :
					$data = array(
						'status' => '3'
					);
				break;
				
				default :
				break;
			}
			$db_comment->whereIn('comment_id', $comment_ids)->update($data);
			
			$page = empty($_GET['page']) ? '' : $_GET['page'];
			$status = $_GET['status'];
			
			if ($status === 'null') {
				if ($list == 3) {
					$pjaxurl = RC_Uri::url('comment/admin/store_goods_comment_list', array('page' => $page));
				} else {
					$pjaxurl = RC_Uri::url('comment/admin/init', array('page' => $page, 'list' => 1));
				}
			} else {
				if ($list == 3) {
					$pjaxurl = RC_Uri::url('comment/admin/store_goods_comment_list', array('status' => $status, 'page' => $page));
				} else {
					$pjaxurl = RC_Uri::url('comment/admin/init', array('status' => $status, 'page' => $page, 'list' => 1));
				}
			}
			//ecjia_admin::admin_log('', $action, 'users_comment');
			return $this->showmessage(sprintf(RC_Lang::get('comment::comment_manage.operation_success'), count($comment_ids)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => $pjaxurl));
		} else {
			/* 错误信息  */
			return $this->showmessage(RC_Lang::get('system::system.no_select_message'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}

	/**
	 * 商品评论回收站
	 */
	public function trash() {
	    $this->admin_priv('comment_manage');
	    $_GET['list'] = !empty($_GET['list']) ?  $_GET['list'] : 2;
	    
	    $this->assign('action_link', array('href'=> RC_Uri::url('comment/admin/init',  array('list' => 1))));
	    ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('评论回收站'));
	    $this->assign('ur_here', '评论回收站');
	     
	    $list = $this->get_comment_list();
	    
	    $this->assign('comment_list', $list);
	    $this->display('comment_trash.dwt');
	}
	
	/**
	 * 获取某一店铺所有商品评论列表
	 */
	public function store_goods_comment_list() {
		$this->admin_priv('comment_manage');
	
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('comment::comment_manage.store_goods_comment_list')));
		$this->assign('ur_here', RC_Lang::get('comment::comment_manage.store_goods_comment'));
	
		$this->assign('action_link', array('text' => RC_Lang::get('comment::comment_manage.comment_list'), 'href'=> RC_Uri::url('comment/admin/init', array('list' => 1))));
	
		ecjia_screen::get_current_screen()->add_help_tab(array(
		'id'		=> 'overview',
		'title'		=> RC_Lang::get('comment::comment_manage.overview'),
		'content'	=> '<p>' . RC_Lang::get('comment::comment_manage.goods_comment_list_help') . '</p>'
				));
		ecjia_screen::get_current_screen()->set_help_sidebar(
		'<p><strong>' . RC_Lang::get('comment::comment_manage.more_info') . '</strong></p>' .
		'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:商品评论" target="_blank">'.RC_Lang::get('comment::comment_manage.about_goods_comment_list').'</a>') . '</p>'
				);
		$_GET['list'] = !empty($_GET['list']) ? $_GET['list'] : 3;		
		$store_id = isset($_GET['store_id']) ? $_GET['store_id'] : 0;	

		$shop_logo = RC_DB::table('merchants_config')->where(RC_DB::raw('store_id'), $store_id)->where(RC_DB::raw('code'), 'shop_logo')->pluck('value');
		if (!empty($shop_logo)) {
			$shop_logo = RC_Upload::upload_url().'/'.$shop_logo;
		} else {
			$shop_logo = RC_Uri::admin_url('statics/images/nopic.png');
		}
		$list_storeinfo = array();
		$list_storeinfo['comment_number'] = RC_DB::table('comment')
		->select(RC_DB::raw('count(*) as "all"'),
				RC_DB::raw('SUM(IF(comment_rank > 3, 1, 0)) as "good"'))
				->where('status', '<>', 3)
				->where('parent_id', 0)
				->where('comment_type', 0)
				->where('store_id', $store_id)
				->first();
				
		if ($list_storeinfo['comment_number']['all'] != 0) {
			if ($list_storeinfo['comment_number']['good'] == 0) {
				$list_storeinfo['comment_percent'] = 100;
			} else {
				$list_storeinfo['comment_percent'] = round(($list_storeinfo['comment_number']['good'] / $list_storeinfo['comment_number']['all']) * 100);
			}
		} else {
			$list_storeinfo['comment_percent'] = 100;
		}
		
		if ($list_storeinfo['comment_percent'] === '100') {
			$comment = array('comment_rank' => 5);
		} elseif (($list_storeinfo['comment_percent'] >= 95) && ($list_storeinfo['comment_percent'] < 100)) {
			$comment = array('comment_rank' => 4);
		} else {
			$comment = array('comment_rank' => 3);
		}
		
		$merchants_name = RC_DB::table('store_franchisee')->where(RC_DB::raw('store_id'), '=', $store_id)->pluck('merchants_name');
		$total_count = $list_storeinfo['comment_number']['all'];
		$comment_percent = $list_storeinfo['comment_percent'];
		
		$list = $this->get_comment_list($store_id);
		$this->assign('comment_list', $list);
		
		$this->assign('comment_percent', $comment_percent);
		$this->assign('comment', $comment);
		$this->assign('total_count', $total_count);
		$this->assign('merchants_name', $merchants_name);
		$this->assign('shop_logo', $shop_logo);
		
		$this->assign('select_status', $_GET['select_status']);
		$this->assign('select_rank', $_GET['select_rank']);
		$this->assign('select_img', $_GET['select_img']);
		$this->assign('store_id', $store_id);
	
		$this->assign('form_action', RC_Uri::url('comment/admin/batch', array('list' => 3)));
		$this->assign('form_search', RC_Uri::url('comment/admin/store_goods_comment_list', array('list' => 3)));
		//$this->assign('dropback_comment', $this->admin_priv('dropback_comment', '', false));
		$this->display('store_goods_comment_list.dwt');
	}
	
	/**
	 * 获取商品评论列表
	 * @access  public
	 * @return  array
	 */
	private function get_comment_list($store_id) {
		/* 查询条件 */
		$filter['keywords'] = empty($_GET['keywords']) ? '' : stripslashes(trim($_GET['keywords']));
	
		$db_comment = RC_DB::table('comment as c');
		
		if (!empty($store_id)) {
			$db_comment->where(RC_DB::raw('c.store_id'), '=', $store_id);
		}
		
		if ($_GET['list'] == '1' || $_GET['list'] == '3') {
			$db_comment->where(RC_DB::raw('c.status'), '<>','3');
		} elseif ($_GET['list'] == 2) {
			$db_comment->where(RC_DB::raw('c.status'), '=','3');
		}
	
		if (!empty($filter['keywords'])) {
			$db_comment->where(RC_DB::raw('c.content'), 'like', '%'.mysql_like_quote($filter['keywords']).'%');
		}
	
		if (isset($_GET['status']) && (!empty($_GET['status']) || $_GET['status'] == '0')) {
			$db_comment->where(RC_DB::raw('c.status'), '=', $_GET['status']);
			$filter['status'] = $_GET['status'];
		}
	
		if (isset($_GET['rank'])) {
			if ($_GET['rank'] == '1') {
				$db_comment->where(RC_DB::raw('c.comment_rank'), '=', '5');
			} elseif ($_GET['rank'] == '2') {
				$db_comment->whereIn(RC_DB::raw('c.comment_rank'), array('3','4'));
			} elseif ($_GET['rank'] == '3') {
				$db_comment->where(RC_DB::raw('c.comment_rank'), '<=', '2');
			}
		}
		if (isset($_GET['has_img']) && (!empty($_GET['has_img']) || $_GET['has_img'] == '0')) {
			$db_comment->where(RC_DB::raw('c.has_image'), '=', $_GET['has_img']);
			$filter['has_img'] = $_GET['has_img'];
		}
		
		$count = $db_comment->count();
		$filter['current_count'] = $count;
				
		$page = new ecjia_page($count, 10, 5);
		$data = $db_comment
		->leftJoin('store_franchisee as sf', RC_DB::raw('c.store_id'), '=', RC_DB::raw('sf.store_id'))
		->leftJoin('goods as g', RC_DB::raw('c.id_value'), '=', RC_DB::raw('g.goods_id'))
		->selectRaw('c.comment_id,c.user_name,c.content,c.add_time,c.id_value,c.comment_rank,c.status,c.has_image,sf.merchants_name,g.goods_name')
		->orderby(RC_DB::raw('c.add_time'), 'asc')
		->take(10)
		->skip($page->start_id-1)
		->get();
		
		$list = array();
		if (!empty($data)) {
			foreach ($data as $row) {
				$row['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['add_time']);
				if ($row['has_image'] == '1') {
					$row['imgs'] = RC_DB::table('term_attachment')
					->where(RC_DB::raw('object_id'), '=', $row['comment_id'])
					->where(RC_DB::raw('object_group'), '=', 'comment')
					->where(RC_DB::raw('object_app'), '=', 'ecjia.comment')
					->select('file_path')->orderby(RC_DB::raw('add_time'), 'asc')->limit(5)->get();
					if (!empty($row['imgs'])) {
						foreach ($row['imgs'] as $key => $val) {
							$row['imgs'][$key]['file_path'] =  RC_Upload::upload_url().'/'.$val['file_path'];
						}
					}
				}
				$list[] = $row;
			}
		}
		return array('item' => $list, 'filter' => $filter, 'page' => $page->show(2), 'desc' => $page->page_desc());
	}

	/**
	 * 获取评论列表
	 * @access  public
	 * @return  array
	 */
	private function get_comment_list_bak() {
		$db_comment 		= RC_Loader::load_app_model('comment_model');
		$db_comment_view 	= RC_Loader::load_app_model('comment_viewmodel');
	
		/* 查询条件 */
		$filter['keywords'] = empty($_GET['keywords']) ? 0 : stripslashes(trim($_GET['keywords']));
		$filter['type'] 	= in_array($_GET['type'], array('1', '2')) ? $_GET['type'] : '1';
	
		$where = array();
		if (isset($_GET['status'])) {
			$filter['status'] = $where['status'] = $_GET['status'];
		} else {
			$where['status'] = array('lt' => '2');
		}
	
		if ($_GET['type'] == '2') {
			$where['comment_type'] = 1;
			$com_where['comment_type'] = 1;
	
			$fields = 'c.*, a.title as comment_name';
			$view = 'article';
		} else {
			$where['comment_type'] = 0;
			$com_where['comment_type'] = 0;
	
			$fields = 'c.*, g.goods_name as comment_name';
			$view = 'goods';
		}
	
		$where['parent_id'] = $com_where['parent_id'] = 0;
		if (!empty($filter['keywords'])) {
			$where[] = "c.content LIKE '%" . mysql_like_quote($filter['keywords']) . "%' ";
		}
	
		$field = "SUM(IF(status < 2, 1, 0)) AS count, SUM(IF(status = 0, 1, 0)) AS waitcheck, SUM(IF(status = 1, 1, 0)) AS checked, SUM(IF(status = 2, 1, 0)) AS trash_msg, SUM(IF(status = 3, 1, 0)) as trashed_msg";
		$com_count = $db_comment->comment_info($com_where, $field);
		//未记录时，设置默认总数0
		$com_count = array(
				'count'			=> empty($com_count['count']) 		? 0 : $com_count['count'],
				'waitcheck'		=> empty($com_count['waitcheck']) 	? 0 : $com_count['waitcheck'],
				'checked'		=> empty($com_count['checked']) 	? 0 : $com_count['checked'],
				'trash_msg'		=> empty($com_count['trash_msg']) 	? 0 : $com_count['trash_msg'],
				'trashed_msg' 	=> empty($com_count['trashed_msg']) ? 0 : $com_count['trashed_msg'],
		);
	
		$option = array(
				'table'	=> $view,
				'where'	=> $where
		);
		$count = $db_comment_view->comment_count($option);
		$page = new ecjia_page($count, 10, 5);
		/* 获取评论数据 */
		$options = array(
				'table'	=> $view,
				'field'	=> $fields,
				'where'	=> $where,
				'order'	=> array('comment_id' => 'desc'),
				'limit'	=> $page->limit(),
		);
		$data = $db_comment_view->comment_select($options);
	
		$arr = array();
		if (!empty($data)) {
			foreach ($data as $key => $row) {
				/* 标记是否回复过 */
				$row['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['add_time']);
				$row['ip_area'] = RC_Ip::area($row['ip_address']);
				if ($_GET['type'] == '2') {
					$row['url'] = RC_Uri::url('article/admin/preview', array('id' => $row['id_value']));
				} else {
					$row['url'] = RC_Uri::url('goods/admin/preview', array('id' => $row['id_value']));
				}
				$arr[] = $row;
			}
		}
		return array('item' => $arr, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc(), 'com_count' => $com_count);
	}
}

// end