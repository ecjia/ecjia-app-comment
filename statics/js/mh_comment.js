// JavaScript Document
;(function (app, $) {
	app.mh_comment = {
		comment_list: function () {
	    	$(".edit-hidden").mouseover(function(){
	    		$(this).children().find(".edit-list").css('visibility', 'visible');
	   		});
	   		$(".edit-hidden").mouseleave(function(){
	   			$(this).children().find(".edit-list").css('visibility', 'hidden');
	   		});
	   		$(".cursor_pointer").click(function(){
	   			$(this).parent().remove();
	   		})
	   		
	        $("form[name='searchForm'] .btn-primary").on('click', function (e) {
	            e.preventDefault();
	            var url = $("form[name='searchForm']").attr('action');
	            var keywords = $("input[name='keywords']").val();
	            if (keywords != '') {
	                url += '&keywords=' + keywords;
	            }
	            ecjia.pjax(url);
	        });
	   		
	   		app.mh_comment.comment_reply();
        },

        comment_reply: function () {			
            $(".comment_reply").on('click', function (e) {
                e.preventDefault();
                var url = $(this).attr('data-url');
                var data = {
                	reply_content: $("input[name='reply_content']").val(),
                	comment_id: $("input[name='comment_id']").val()
                };
                $.get(url, data, function (data) {
                	ecjia.merchant.showmessage(data);
                }, 'json');
            });
		}
	}
})(ecjia.merchant, jQuery);