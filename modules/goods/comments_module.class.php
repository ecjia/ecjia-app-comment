<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 某商品的所有评论
 * @author royalwang
 *
 */
class comments_module extends api_front implements api_interface
{
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request)
    {	
    	$this->authSession();
		$goods_id = $this->requestData('goods_id');
		$comment_type = $this->requestData('comment_type');
		
		if (!$goods_id || !$comment_type) {
			return new ecjia_error('invalid_parameter', RC_Lang::get('system::system.invalid_parameter'));
		}
		
		$page_size	= $this->requestData('pagination.count', 15);
		$page		= $this->requestData('pagination.page', 1);
		
		//0评论的是商品,1评论的是文章
		$comment_list = EM_assign_comment($goods_id, $comment_type, $page, $page_size);
		return array('data' => $comment_list['data'], 'pager' => $comment_list['pager']);
	}
}



/**
 * 查询评论内容
 *
 * @access  public
 * @params  integer     $id
 * @params  integer     $type
 * @params  integer     $page
 * @return  array
 */
function EM_assign_comment($id, $type, $page = 1, $page_size = 15) {
	$list['comment_number'] = RC_DB::table('comment')
		->select(RC_DB::raw('count(*) as "all"'),
			RC_DB::raw('SUM(IF(comment_rank > 3, 1, 0)) as "good"'),
			RC_DB::raw('SUM(IF(comment_rank > 1 && comment_rank < 4, 1, 0)) as "general"'),
			RC_DB::raw('SUM(IF(comment_rank = 1, 1, 0)) as "low"'),
			RC_DB::raw('SUM(IF(has_image = 1, 1, 0)) as "picture"'))
		->where('status', 1)
		->where('parent_id', 0)
		->where('comment_type', 0)
		->where('id_value', $id)
		->first();
	$list['comment_number']['good'] = empty($list['comment_number']['good']) ? 0 : intval($list['comment_number']['good']);
	$list['comment_number']['general'] = empty($list['comment_number']['good']) ? 0 : intval($list['comment_number']['general']);
	$list['comment_number']['low'] = empty($list['comment_number']['good']) ? 0 : intval($list['comment_number']['low']);
	$list['comment_number']['picture'] = empty($list['comment_number']['good']) ? 0 : intval($list['comment_number']['picture']);
	
	if ($list['comment_number']['all'] != 0) {
		$list['comment_percent'] = round(($list['comment_number']['good'] / $list['comment_number']['all']) * 100) .'%';
	} else {
		$list['comment_percent'] = '100%';
	}

	$db_comment = RC_DB::table('comment')
		->select('*')
		->where('id_value', $id)
		->where('status', 1)
		->where('parent_id', 0)
		->where('comment_type', 0);
	
	if ($type == 'all') {
		$db_comment->where('comment_rank', '>', 0);
	} elseif ($type == 'good') {
		$db_comment->where('comment_rank', '>', 3);
	} elseif ($type == 'general') {
		$db_comment->where('comment_rank', '>', 1)->where('comment_rank', '<', 4);
	} elseif ($type == 'low') {
		$db_comment->where('comment_rank', '=', 1);
	} elseif ($type == 'picture') {
		$db_comment->where('has_image', '=', 1);
	}
	
	/* 取得评论列表 */
	$count = $db_comment->count();
	
	/* 查询总数为0时直接返回  */
	if ($count == 0) {
		$pager = array(
			'total' => 0,
			'count' => 0,
			'more'	=> 0,
		);
		return array('data' => $list, 'pager' => $pager);
	}
		
	$page_row = new ecjia_page($count, $page_size, 6, '', $page);
	$data = $db_comment->select('*')->orderBy('comment_id', 'desc')->take($page_size)->skip($page_row->start_id-1)->get();
	
	$arr = $ids = array();
	if (!empty($data)) {
		foreach ($data as $row) {
			$arr['id'] = $row['comment_id'];
			if ($row['is_anonymous'] == 1) {
				$len = mb_strlen($row['user_name']);
				if ($len > 2) {
					$user_name = mb_substr($row['user_name'], 0, 1) . '***' . mb_substr($row['user_name'], $len-1, $len);
				} else  {
					$user_name = mb_substr($row['user_name'], 0, 1) . '*';
				}
				$arr['author'] = $user_name;
			} else {
				$arr['author'] = $row['user_name'];
			}
	
			$arr['content']  	= str_replace('\r\n', '<br />', htmlspecialchars($row['content']));
			$arr['content']  	= nl2br(str_replace('\n', '<br />', $arr['content']));
			$arr['rank']     	= $row['comment_rank'];
			$arr['goods_attr']	= $row['goods_attr'];
			$arr['add_time'] 	= RC_Time::local_date(ecjia::config('time_format'), $row['add_time']);
	
			if ($row['has_image'] == 1) {
				$picture_list = RC_DB::table('term_attachment')
					->where('object_group', 'comment')
					->where('object_id', $row['comment_id'])
					->where('object_app', 'ecjia.comment')
					->where('object_group', 'comment')
					->lists('file_path');
				if (!empty($picture_list)) {
					foreach ($picture_list as $k => $v) {
						if (!empty($v) && file_exists(RC_Upload::upload_path($v))) {
							$arr['picture'][] = RC_Upload::upload_url($v);
						}
					}
				}
			}
			$reply = RC_DB::table('comment')->where('parent_id', $row['comment_id'])->first();
			if (!empty($reply)) {
				$arr['reply_content']  = nl2br(str_replace('\n', '<br />', htmlspecialchars($reply['content'])));
				$arr['reply_username'] = $row['user_name'];
				$arr['reply_add_time'] = RC_Time::local_date(ecjia::config('time_format'), $reply['add_time']);
			}
			$list['list'][] = $arr;
		}
	}

	$pager = array(
		"total"  => $page_row->total_records,
		"count"  => $page_row->total_records,
		"more"   => $page_row->total_pages <= $page ? 0 : 1,
	);

   	return array('data' => $list, 'pager' => $pager);
}

// end