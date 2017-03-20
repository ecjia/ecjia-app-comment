// JavaScript Document
;(function (app, $) {
    app.appeal = {
        init: function () {
            app.appeal.list_search();
            $(".date").datepicker({
    			format: "yyyy-mm-dd"
    		});
        },
 
        list_search: function (e) {
            $(".search_appeal").on('click', function (e) {
            	e.preventDefault();
            	var start_date = $("input[name='start_date']").val();
    			var end_date = $("input[name='end_date']").val();
    			if (start_date > end_date && (start_date != '' && end_date !='')) {
    				var data = {
    						message : "开始时间不得大于结束时间！",
    						state : "error",
    				};
    				ecjia.admin.showmessage(data);
    				return false;
    			}
                var url = $("form[name='searchForm']").attr('action');
                var keywords = $("input[name='keyword']").val();
                if (keywords != '') {
                    url += '&keywords=' + keywords;
                }
                if (start_date != '')		url+= '&start_date=' + start_date;
    			if (end_date != '')			url+= '&end_date=' + end_date;
                ecjia.pjax(url);
            });
        } 
    }  
})(ecjia.admin, jQuery);
 
// end