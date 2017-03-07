// JavaScript Document
;(function (app, $) {
	app.mh_comment = {
        editlist: function () {
	    	$(".edit-hidden").mouseover(function(){
	    		$(this).children().find(".edit-list").css('visibility', 'visible');
	   		});
	   		$(".edit-hidden").mouseleave(function(){
	   			$(this).children().find(".edit-list").css('visibility', 'hidden');
	   		});
	   		$(".cursor_pointer").click(function(){
	   			$(this).parent().remove();
	   		})
        },
	}
})(ecjia.merchant, jQuery);