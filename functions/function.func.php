<?php
defined('IN_ECJIA') or exit('No permission resources.');
// /**
//  * 获取评论列表
//  * @access  public
//  * @return  array
//  */
// function get_comment_list() {
// 	$db = RC_Loader::load_app_model('comment_model', 'comment');
	
// 	/* 查询条件 */
// 	$filter['keywords'] = empty($_REQUEST['keywords']) ? 0 : trim($_REQUEST['keywords']);
// 	$filter['keywords'] = stripslashes($filter['keywords']);
// 	$filter['type'] = in_array($_GET['type'], array('1','2')) ? $_GET['type'] : '1';
// 	$filter['status'] = '';
// 	$where = array();
// 	if (isset($_GET['status']) && (!empty($_GET['status']) || $_GET['status']=='0')) {
// 		$where['status'] = $_GET['status'];
// 		$filter['status'] = $_GET['status'];
// 	} else {
// 		$where['status'] = array('lt'=>'2');
// 	}

// 	$db_view = RC_Loader::load_app_model('comment_viewmodel', 'comment');

// 	if ($_GET['type'] == '2') {
		
// 		$where['comment_type'] = 1;
// 		$com_where['comment_type'] = 1;
		
// 		$field = 'c.*,a.title as comment_name';
// 		$view = 'article';
// 	} else {
		
// 		$where['comment_type'] = 0;
// 		$com_where['comment_type'] = 0;
		
// 		$field = 'c.*,g.goods_name as comment_name';
// 		$view = 'goods';
// 	}

// 	$where['parent_id'] = $com_where['parent_id'] = 0;
// 	if (!empty($filter['keywords'])) {
// 		$where[] = "c.content LIKE '%" . mysql_like_quote($filter['keywords']) . "%' ";
// 	}
	
// 	$field = "SUM(IF(status<2,1,0)) AS count, SUM(IF(status=0,1,0)) AS waitcheck, SUM(IF(status=1,1,0)) AS checked, SUM(IF(status=2,1,0)) AS trash_msg, SUM(IF(status=3,1,0)) as trashed_msg";
// 	$com_count = $db->field($field)->where($com_where)->find();
// 	//未记录时，设置默认总数0
// 	$com_count = array(
// 		'count'		=> empty($com_count['count']) ? 0 : $com_count['count'],
// 		'waitcheck'	=> empty($com_count['waitcheck']) ? 0 : $com_count['waitcheck'],
// 		'checked'	=> empty($com_count['checked']) ? 0 : $com_count['checked'],
// 		'trash_msg'	=> empty($com_count['trash_msg']) ? 0 : $com_count['trash_msg'],
// 		'trashed_msg' => empty($com_count['trashed_msg']) ? 0 : $com_count['trashed_msg'],
// 	);
// 	$count = $db_view->join($view)->where($where)->count();
// 	$page = new ecjia_page($count, 15, 5);
// 	/* 获取评论数据 */
// 	$arr = array();
	
// 	$data = $db_view->join($view)->where($where)->order(array('comment_id'=>'desc'))->limit($page->limit())->select();
// 	if (!empty($data)) {
// 		foreach ($data as $key => $row) {
// 			/* 标记是否回复过 */
// 			$row['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['add_time']);
// 			$row['ip_area'] = RC_Ip::area($row['ip_address']);
// 			if ($_GET['type'] == '2') {
// 				$row['url'] = RC_Uri::url('article/admin/preview','id='.$row['id_value']);
// 			} else {
// 				$row['url'] = RC_Uri::url('goods/admin/preview','id='.$row['id_value']);
// 			}
// 			$arr[] = $row;
// 		}
// 	}
	
// 	$arr = array('item' => $arr, 'page' => $page->show(5), 'desc' => $page->page_desc(), 'filter' => $filter, 'com_count' => $com_count);
// 	return $arr;
// }

// end