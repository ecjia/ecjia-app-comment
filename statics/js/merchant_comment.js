// JavaScript Document
;(function (app, $) {
	app.merchant_comment = {
        editlist: function () {
	    	$(".edit-hidden").mouseover(function(){
	    		$(".edit-list").css('visibility', 'visible');
	   		});
	   		$(".edit-hidden").mouseleave(function(){
	   			$(".edit-list").css('visibility', 'hidden');
	   		});
        },
	}
})(ecjia.merchant, jQuery);