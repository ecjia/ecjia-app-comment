<?php
use Royalcms\Component\FormBuilder\Fields\RepeatedType;
/**
 * ECJIA 用户评论申诉管理程序
*/

defined('IN_ECJIA') or exit('No permission resources.');

class appeal extends ecjia_admin {
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
	 * 申诉列表
	 */
	public function init() {
		$this->admin_priv('appeal_manage');
		
		$this->display('appeal_list.dwt');		
	}
}

// end