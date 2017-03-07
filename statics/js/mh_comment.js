// JavaScript Document
;(function (app, $) {
	app.mh_comment = {
        editlist: function () {
	    	$(".edit-hidden").mouseover(function(){
	    		$(".edit-list").css('visibility', 'visible');
	   		});
	   		$(".edit-hidden").mouseleave(function(){
	   			$(".edit-list").css('visibility', 'hidden');
	   		});
	   		$(".cursor_pointer").click(function(){
	   			$(".filter-box").css('visibility', 'hidden');
	   		})
        },
	}
})(ecjia.merchant, jQuery);