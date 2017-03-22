<?php
use Royalcms\Component\FormBuilder\Fields\RepeatedType;
/**
 * ECJIA 用户评论申诉管理程序
*/

defined('IN_ECJIA') or exit('No permission resources.');

class appeal extends ecjia_admin {
	
	public function __construct() {
		parent::__construct();
		
		RC_Loader::load_app_func('global');
		assign_adminlog_content();
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Style::enqueue_style('chosen');
		

		RC_Script::enqueue_script('jquery.toggle.buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/jquery.toggle.buttons.js'));
		RC_Style::enqueue_style('bootstrap-toggle-buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/bootstrap-toggle-buttons.css'));
		
		RC_Script::enqueue_script('appeal', RC_App::apps_url('statics/js/appeal.js', __FILE__), array(), false, false);
		RC_Script::enqueue_script('comment_manage', RC_App::apps_url('statics/js/comment_manage.js', __FILE__), array(), false, false);
		RC_Style::enqueue_style('appeal', RC_App::apps_url('statics/css/appeal.css', __FILE__));
		RC_Style::enqueue_style('comment', RC_App::apps_url('statics/css/comment.css', __FILE__));
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'));
		RC_Script::enqueue_script('bootstrap-datepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datepicker.min.js'));
		//RC_Style::enqueue_style('start', RC_App::apps_url('statics/css/start.css', __FILE__));
		RC_Script::localize_script('comment_manage', 'js_lang', RC_Lang::get('comment::comment_manage.js_lang'));
	}
	
	/**
	 * 申诉列表
	 */
	public function init() {
		$this->admin_priv('appeal_manage');
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('申诉列表'));
		$this->assign('ur_here', '申诉列表');
		
		$appeal_list = $this->get_appeal_list();
		$this->assign('appeal_list', $appeal_list);
		$this->assign('form_search', RC_Uri::url('comment/appeal/init'));
		
		$this->display('appeal_list.dwt');		
	}
	
	/**
	 *申诉-查看详情
	 */
	public function detail() {
		$this->admin_priv('appeal_manage');
		
		$id =  $_GET['id'];
		$appeal_info = RC_DB::table('comment_appeal')->where('id', $id)->first();
		if (empty($appeal_info)) {
			return $this->showmessage('不存在的信息', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR, array('pjaxurl' => RC_Uri::url('comment/appeal/init')));
		}

		$appeal_info['appeal_time'] = RC_Time::local_date(ecjia::config('time_format'), $appeal_info['appeal_time']);
		$appeal_info['process_time'] = RC_Time::local_date(ecjia::config('time_format'), $appeal_info['process_time']);
		
		$comment_info = RC_DB::table('comment')->where('comment_id', $appeal_info['comment_id'])->first();
		$comment_info['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $comment_info['add_time']);
		if ($comment_info['has_image'] == '1') {
			$comment_imgs_list = RC_DB::table('term_attachment')
								->where(RC_DB::raw('object_id'),  $comment_info['comment_id'])
								->where(RC_DB::raw('object_group'), '=', 'comment')
								->where(RC_DB::raw('object_app'), '=', 'ecjia.comment')
								->select('file_path')->get();
			if (!empty($comment_imgs_list)) {
				foreach ($comment_imgs_list as $key => $val) {
					$comment_imgs_list[$key]['file_path'] = RC_Upload::upload_url().'/'.$val['file_path'];
				}
			}
		}
		
		$avatar_img = RC_DB::table('users')->where('user_id', $comment_info['user_id'])->pluck('avatar_img');
		if (!empty($avatar_img)) {
			$avatar_img = RC_Upload::upload_url().'/'.$avatar_img;
		}
		
		$appeal_imgs_list = RC_DB::table('term_attachment')
							->where('object_id', $appeal_info['id'])
							->where(RC_DB::raw('object_group'), '=', 'appeal')
							->where(RC_DB::raw('object_app'), '=', 'ecjia.comment')
							->select('file_path')->get();
		if (!empty($appeal_imgs_list)) {
			foreach ($appeal_imgs_list as $key => $val) {
				$appeal_imgs_list[$key]['file_path'] = RC_Upload::upload_url().'/'.$val['file_path'];
			}
		}
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('申诉详情'));
		if ($appeal_info['check_status'] <= '1') {
			$this->assign('ur_here', '申诉详情（审核中）');
		} elseif ($appeal_info['check_status'] == '2') {
			$this->assign('ur_here', '申诉详情（申诉成功）');
		} elseif ($appeal_info['check_status'] == '3') {
			$this->assign('ur_here', '申诉详情（申诉失败）');
		}
		
		$this->assign('appeal_info', $appeal_info);
		$this->assign('comment_info', $comment_info);
		$this->assign('avatar_img', $avatar_img);
		$this->assign('comment_imgs_list', $comment_imgs_list);
		$this->assign('appeal_imgs_list', $appeal_imgs_list);
		$this->assign('action_link', array('text' => '申诉列表', 'href'=> RC_Uri::url('comment/appeal/init')));
		$this->assign('check_comment', $this->admin_priv('appeal_update', '', false));
		$this->display('appeal_detail.dwt');
	}
	
	/**
	 * 管理员审核评论申诉
	 */
	public function check_appeal() {
		$this->admin_priv('appeal_update');
		
		$appeal_id		  = $_GET['appeal_id'];
		$comment_id 	  = $_GET['comment_id'];
		$ckeck_remark     = $_GET['check_remark'];
		$ckeck_stauts	  = $_GET['check_status'];
		
		$db_comment_appeal = RC_DB::table('comment_appeal');
		
		if(empty($ckeck_remark)){
			return $this->showmessage('审核申诉备注不可为空', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		$data = array(
				'id' 	=> $appeal_id,
				'check_remark' 	=> $ckeck_remark,
				'check_status'	=> $ckeck_stauts,
				'process_time'	=> RC_Time::gmtime(),
		);
		$update = $db_comment_appeal->where(RC_DB::raw('id'), $appeal_id)->update($data);
		ecjia_admin::admin_log('申诉ID：'.$appeal_id, 'appeal_status', 'merchant_appeal');
		
		$pjaxurl = RC_Uri::url('comment/appeal/detail', array('id' => $appeal_id, 'comment_id' => $comment_id));
		if ($update) {
			return $this->showmessage('审核申诉成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => $pjaxurl));
		}
	}

	/**
	 * 申诉-列表信息
	 */
	private function get_appeal_list() {
		$db_comment_appeal = RC_DB::table('comment_appeal as ca')
							->leftJoin('store_franchisee as sf', RC_DB::raw('ca.store_id'), '=', RC_DB::raw('sf.store_id'))
							->leftJoin('comment as c', RC_DB::raw('c.comment_id'), '=', RC_DB::raw('ca.comment_id'));
		
		$filter['type'] = empty($_GET['type']) ? 0 : $_GET['type'];
		$filter['keywords'] = empty($_GET['keywords']) ? '' : trim($_GET['keywords']);
		
		$start_date = empty($_GET['start_date']) ? '' : RC_Time::local_strtotime($_GET['start_date']);
		$end_date   = empty($_GET['end_date']) ? '' : RC_Time::local_strtotime($_GET['end_date']);
		
		if ($filter['keywords']) {
			$db_comment_appeal->where(RC_DB::Raw('ca.appeal_sn'), 'like', '%' . mysql_like_quote($filter['keywords']) . '%')
			->orWhere(RC_DB::Raw('sf.merchants_name'), 'like', '%' . mysql_like_quote($filter['keywords']) . '%');
		}
		
		if ($start_date) {
			$db_comment_appeal->where(RC_DB::Raw('ca.appeal_time'), '>=', $start_date);
			$filter['start_date'] = $_GET['start_date'];
		}
		
		if ($end_date) {
			$db_comment_appeal->where(RC_DB::Raw('ca.appeal_time'), '<=', $end_date);
			$filter['end_date'] = $_GET['end_date'];
		}
		
		$current_count = $db_comment_appeal->select(RC_DB::raw('count(*) as count'),
				RC_DB::raw('SUM(IF(check_status = 1,1,0)) as waithandle'),
				RC_DB::raw('SUM(IF(check_status != 1,1,0)) as handled'),
				RC_DB::raw('ca.appeal_sn'), RC_DB::raw('sf.merchants_name'))->first();
	
		if ($filter['type'] == '1') {
			$db_comment_appeal->where('check_status', '=', 1);
		}
	
		if ($filter['type'] == '2') {
			$db_comment_appeal->where('check_status', '<>', 1);
		}
	
		$count = $db_comment_appeal->count();
		$page = new ecjia_page($count, 10, 5);
		$data = $db_comment_appeal
		->selectRaw('ca.id, ca.appeal_sn, ca.comment_id, ca.appeal_content, ca.check_status, ca.appeal_time, ca.process_time, sf.merchants_name, c.has_image')
		->orderby(RC_DB::Raw('ca.appeal_time'), 'asc')
		->take(10)
		->skip($page->start_id-1)
		->get();
	
		$list = array();
		if (!empty($data)) {
			foreach ($data as $row) {
				$row['appeal_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['appeal_time']);
				if($row['check_status'] == 1){
					$row['label_check_status'] = '待处理';
				}elseif ($row['check_status'] == 2){
					$row['label_check_status'] = '通过';
				}elseif ($row['check_status'] == 3){
					$row['label_check_status'] = '驳回';
				}elseif($row['check_status'] == 4){
					$row['label_check_status'] = '撤销';
				}
				$row['imgs'] = RC_DB::table('term_attachment')
				->where(RC_DB::raw('object_id'), '=', $row['id'])
				->where(RC_DB::raw('object_group'), '=', 'appeal')
				->where(RC_DB::raw('object_app'), '=', 'ecjia.comment')
				->select('file_path')->orderby(RC_DB::raw('add_time'), 'asc')->get();
				if (!empty($row['imgs'])) {
					foreach ($row['imgs'] as $key => $val) {
						$row['imgs'][$key]['file_path'] =  RC_Upload::upload_url().'/'.$val['file_path'];
					}
				}
				$list[] = $row;
			}
		}

		return array('item' => $list, 'filter' => $filter, 'page' => $page->show(2), 'desc' => $page->page_desc(), 'current_count' => $current_count);
	}
	
	
}

// end