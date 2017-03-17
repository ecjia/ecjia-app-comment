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
		RC_Style::enqueue_style('start', RC_App::apps_url('statics/css/start.css', __FILE__));
		
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
		$this->assign('ur_here', RC_Lang::get('comment::comment_manage.admin_goods_comment'));
		
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
			$pjaxurl = RC_Uri::url('comment/admin/init', array('status' => $_GET['status']));
		} else {
			$pjaxurl = RC_Uri::url('comment/admin/init');
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
		$avatar_img = RC_DB::TABLE('users')->where('user_id', $comment_info['user_id'])->pluck('avatar_img');

		/* 管理员回复内容*/
		$replay_admin_list = RC_DB::TABLE('comment_reply')
                		->where('comment_id', $comment_info['comment_id'])
                		->select('content', 'add_time', 'user_id')
                		->get();
		
		foreach ($replay_admin_list as $key => $val) {
		    $replay_admin_list[$key]['add_time_new'] = RC_Time::local_date(ecjia::config('time_format'), $val['add_time']);   
		    $staff_info = RC_DB::TABLE('staff_user')->where('user_id', $val['user_id'])->select('name', 'avatar', 'store_id')->first(); //管理员信息
		    $replay_admin_list[$key]['staff_name'] = $staff_info['name'];       
		    $replay_admin_list[$key]['staff_img']  =  RC_Upload::upload_url($staff_info['avatar']);
		};

		//获取评论图片
		$comment_pic_list = RC_DB::TABLE('term_attachment')->where('object_id', $comment_info['comment_id'])->select('file_path')->get();

		$shop_info['logo'] = RC_DB::TABLE('merchants_config')
                		->where('store_id', $staff_info['store_id'])
                		->where('code', 'shop_logo')
                		->pluck('value');

		$shop_info['name'] = RC_DB::TABLE('store_franchisee')
                		->where('store_id', $staff_info['store_id'])
                		->pluck('merchants_name');

		$shop_info['amount'] = RC_DB::TABLE('comment')->where('store_id', $comment_info['store_id'])->count();

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
		
		$this->assign('ur_here', RC_Lang::get('comment::comment_manage.comment_info'));
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
		/* 获得评论是否有回复 */
		$reply_info = $this->db_comment->comment_info(array('parent_id' => $_POST['comment_id']), 'comment_id, content, parent_id');
		
		$email 		= !empty($_POST['email']) 		? $_POST['email'] 		: '';
		$content 	= !empty($_POST['content']) 	? $_POST['content'] 	: '';
		$comment_id	= !empty($_POST['comment_id']) 	? $_POST['comment_id'] 	: 0;
		
		if (!empty($reply_info['content'])) {
			/* 更新回复的内容 */
			$data = array(
			    'comment_id'    => $reply_info['comment_id'],
				'email'         => $email,
				'user_name' 	=> !empty($_POST['user_name']) ? $_POST['user_name'] : '',
				'content' 		=> $content,
				'add_time' 		=> RC_Time::gmtime(),
				'ip_address' 	=> $ip,
				'status' 		=> 1,
			);
		} else {
			/* 插入回复的评论内容 */
			$data = array(
				'comment_type' 	=> !empty($_POST['comment_type']) ? $_POST['comment_type'] : '',
				'id_value' 		=> !empty($_POST['id_value']) ? $_POST['id_value'] : '',
				'email' 		=> $email,
				'user_name' 	=> $_SESSION['admin_name'],
				'content' 		=> $content,
				'add_time' 		=> RC_Time::gmtime(),
				'ip_address' 	=> $ip,
				'status' 		=> 1,
				'parent_id'		=> $comment_id,
			);
		}
		$this->db_comment->comment_manage($data);

		/* 邮件通知处理流程 */
		if (!empty($_POST['send_email_notice']) || isset($_POST['remail'])) {
			//获取邮件中的必要内容
			$comment_info = $this->db_comment->comment_info(array('comment_id' => $_POST['comment_id']), 'user_name, email, content');
			/* 设置留言回复模板所需要的内容信息 */
			$tpl_name = 'recomment';
			$template = RC_Api::api('mail', 'mail_template', $tpl_name);
			
			$this->assign('user_name', $comment_info['user_name']);
			$this->assign('comment', $comment_info['content']);
			$this->assign('recomment', $content);
			$this->assign('shop_name', "<a href='".SITE_URL."'>" . ecjia::config('shop_name') . '</a>');
			$this->assign('send_date', date('Y-m-d'));
			$content = $this->fetch_string($template['template_content']);
			
			/* 发送邮件 */
			if (RC_Mail::send_mail($comment_info['user_name'], $comment_info['email'], $template['template_subject'], $content, $template['is_html'])) {
			    return $this->showmessage(RC_Lang::get('comment::comment_manage.mail_notice_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('comment/admin/reply', array('id' => $comment_id))));
			} else {
				return $this->showmessage(RC_Lang::get('comment::comment_manage.mail_send_fail'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR );
			}
		}
		
		ecjia_admin::admin_log(addslashes(RC_Lang::get('comment::comment_manage.reply')), 'edit', 'users_comment');
		return $this->showmessage(RC_Lang::get('comment::comment_manage.reply_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
	
	/**
	 * 列表更新评论的状态为批准或者驳回
	 */
	public function check() {
		$this->admin_priv('comment_update', ecjia::MSGTYPE_JSON);

		$id		 	= !empty($_GET['comment_id']) 		? intval($_GET['comment_id'])		: 0;
		$page		= !empty($_GET['page']) 		? intval($_GET['page'])		: 1;
		$allow 		= !empty($_POST['check']) 	? $_POST['check']			: '';
		$status		= $_POST['status'];
		
		if ($status === '') {
			$pjaxurl = RC_Uri::url('comment/admin/init', array('page' => $page));
		} else {
			$pjaxurl = RC_Uri::url('comment/admin/init', array('status' => intval($status), 'page' => $page));
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
		 elseif ($allow == "trash_comment") {
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
				$pjaxurl = RC_Uri::url('comment/admin/init', array('page' => $page, 'list' => 1));
			} else {
				$pjaxurl = RC_Uri::url('comment/admin/init', array('status' => $status, 'page' => $page, 'list' => 1));
			}
			//ecjia_admin::admin_log('', $action, 'users_comment');
			return $this->showmessage(sprintf(RC_Lang::get('comment::comment_manage.operation_success'), count($comment_ids)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => $pjaxurl));
		} else {
			/* 错误信息  */
			return $this->showmessage(RC_Lang::get('system::system.no_select_message'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}

	/**
	 * 获取商品评论列表
	 * @access  public
	 * @return  array
	 */
	private function get_comment_list() {
		/* 查询条件 */
		$filter['keywords'] = empty($_GET['keywords']) ? '' : stripslashes(trim($_GET['keywords']));

		$db_comment = RC_DB::table('comment as c');
		if ($_GET['list'] == '1') {
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
		}
		
		$count = $db_comment->count();
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
	
	/**
	 * 评论设置
	 */
	public function config() {
	    $this->admin_priv('comment_update');
	
	    ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('评论设置'));
	    $this->assign('ur_here', '评论设置');
		
	    $this->assign('close_comment', ecjia::config('close_comment'));
	    $this->assign('comment_check', ecjia::config('comment_check'));
	    $this->assign('comment_award_open', ecjia::config('comment_award_open'));  
	    $this->assign('comment_factor', ecjia::config('comment_factor'));
	    
	    /* 评论送积分*/
	    $user_rank_list = array();
	    $db_user_rank = RC_DB::table('user_rank');
	    $data = $db_user_rank->selectRaw('rank_id, rank_name')->get();
	    if (!empty($data)) {
	    	$comment_award_rules = unserialize(ecjia::config('comment_award_rules'));
	    		
	    	foreach ($data as $row) {
	    		if (!empty($comment_award_rules[$row['rank_id']])) {
	    			$row['comment_award'] = $comment_award_rules[$row['rank_id']];
	    		}
	    		$user_rank_list[] = $row;
	    	}
	    }
	    $this->assign('user_rank_list', $user_rank_list);
	    $this->assign('comment_award_open', ecjia::config('comment_award_open'));
	    $this->assign('comment_award', ecjia::config('comment_award'));
	    $this->assign('form_action', RC_Uri::url('comment/admin/update_config'));
	    
	    $this->display('comment_config.dwt');
	}
	
	
	/**
	 * 评论设置处理
	 */
	public function update_config() {
		$this->admin_priv('comment_update', ecjia::MSGTYPE_JSON);
		/*评论送积分*/
		$comment_award_open = isset($_POST['comment_award_open']) ? intval($_POST['comment_award_open']) : 0;
		$comment_award 		= isset($_POST['comment_award']) ? intval($_POST['comment_award']) : 0;
		$close_comment		= isset($_POST['close_comment']) ? intval($_POST['close_comment']) : 0;
		$comment_check		= isset($_POST['comment_check']) ? intval($_POST['comment_check']) : 0;
		$comment_factor		= isset($_POST['comment_factor']) ? intval($_POST['comment_factor']) : 0;
				
		ecjia_config::instance()->write_config('comment_award_open', $comment_award_open);
		ecjia_config::instance()->write_config('comment_award', $comment_award);
		ecjia_config::instance()->write_config('close_comment', $close_comment);
		ecjia_config::instance()->write_config('comment_check', $comment_check);
		ecjia_config::instance()->write_config('comment_factor', $comment_factor);
		
		$comment_award_rules = '';
		if (isset($_POST['comment_award_rules'])) {
			foreach ($_POST['comment_award_rules'] as $key => $val) {
				if (empty($val)) {
					continue;
				}
				$comment_award_rules[$key] = intval($val);
			}
			if (!empty($comment_award_rules)) {
				$comment_award_rules = serialize($comment_award_rules);
			}
		}
		ecjia_config::instance()->write_config('comment_award_rules', $comment_award_rules);
		return $this->showmessage('评论设置更新成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('comment/admin/config')));
	}
	/**
	 * 商品评论回收站
	 */
	public function trash() {
	    $this->admin_priv('comment_manage');
	    $_GET['list'] = !empty($_GET['list']) ?  $_GET['list'] : 2;
	    
	    $this->assign('action_link', array('href'=> RC_Uri::url('comment/admin/init',  array('list' => 1))));
	    ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('回收站'));
	    $this->assign('ur_here', '回收站');
	    
	    $list = $this->get_comment_list();
	    $this->assign('comment_list', $list);
	    
	    $this->display('comment_trash.dwt');
	}
	
}

// end