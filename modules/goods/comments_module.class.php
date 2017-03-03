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
		$goods_id = $this->requestData('goods_id', 0);
		if (!$goods_id) {
			return new ecjia_error('invalid_parameter', RC_Lang::get('system::system.invalid_parameter'));
		}
		$page_size	= $this->requestData('pagination.count', 15);
		$page		= $this->requestData('pagination.page', 1);
		//0评论的是商品,1评论的是文章
		$comment_list = EM_assign_comment($goods_id, 0, $page, $page_size);
		return array('data' => $comment_list['comments'], 'pager' => $comment_list['pager']);
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
function EM_assign_comment($id, $type, $page = 1, $page_size = 10) {
	$db = RC_Loader::load_app_model('comment_model','comment');
	$where = array(
		'id_value' => $id,
		'comment_type' => $type,
		'status' => 1,
		'parent_id' =>0
	);
    /* 取得评论列表 */
	$count = $db->where($where)->count();
    $page_count = ($count > 0) ? intval(ceil($count / $page_size)) : 1;
	$data = $db->where($where)->order(array('comment_id' => 'desc'))->limit(($page-1)*$page_size,$page_size)->select();
    
    $arr = array();
    $ids = '';
	if(!empty($data)) {
		foreach ($data as $row) {
	        $ids .= $ids ? ",$row[comment_id]" : $row['comment_id'];
	        $arr[$row['comment_id']]['id']       = $row['comment_id'];
	        $arr[$row['comment_id']]['email']    = $row['email'];
	        $arr[$row['comment_id']]['author'] = empty($row['user_name']) ? '匿名用户' : $row['user_name'] ;
	        $arr[$row['comment_id']]['content']  = str_replace('\r\n', '<br />', htmlspecialchars($row['content']));
	        $arr[$row['comment_id']]['content']  = nl2br(str_replace('\n', '<br />', $arr[$row['comment_id']]['content']));
	        $arr[$row['comment_id']]['rank']     = $row['comment_rank'];
	        $arr[$row['comment_id']]['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['add_time']);
	    }
	}
    
    /* 取得已有回复的评论 */
    if ($ids) {
        $data = $db->in(array('parent_id' => $ids))->select();
    	if (!empty($data)) {
	        foreach ($data as $row) {
	            $arr[$row['parent_id']]['re_content']  = nl2br(str_replace('\n', '<br />', htmlspecialchars($row['content'])));
	            $arr[$row['parent_id']]['re_add_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['add_time']);
	            $arr[$row['parent_id']]['re_email']    = $row['email'];
	            $arr[$row['parent_id']]['re_username'] = $row['user_name'];
	        }
        }
    }

	$pager = array(
		"total"  => $count,	 
		"count"  => count($arr),
		"more"   => $page < $page_count ? 1 : 0
	);

    $cmt = array('comments' => array_values($arr), 'pager' => $pager);
    return $cmt;    
}

// end