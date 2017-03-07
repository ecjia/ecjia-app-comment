// JavaScript Document
;(function (app, $) {
    app.appeal_list = {
        init: function () {
            //搜索功能
            $("form[name='searchForm'] .btn-primary").on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action');
                var keywords = $("input[name='keywords']").val();
                if (keywords != '') {
                    url += '&keywords=' + keywords;
                }
                ecjia.pjax(url);
            });
        }
    };
 
    app.appeal_edit = {
        init: function () {        	
    		var $form = $("form[name='staffForm']");
			var option = {
		            rules: {
		            	name: "required"
		            },
		            messages: {
		            	name: "请输入申诉内容"
		            },

					submitHandler : function() {
						$form.ajaxSubmit({
							dataType : "json",
							success : function(data) {
								top.location.reload();
								ecjia.merchant.showmessage(data);
							}
						});
					}
				}
			 var options = $.extend(ecjia.merchant.defaultOptions.validate, option);
			$form.validate(options);
        }
    };
})(ecjia.merchant, jQuery);
 
// end