<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
if($config->debug)
{
    css::import($defaultTheme . 'datepicker.css');
    js::import($jsRoot . 'jquery/datepicker/min.js'); 
    js::import($jsRoot . 'jquery/datepicker/date.js');
}
?>
<script language='javascript'>
Date.firstDayOfWeek = 1;
Date.format = 'yyyy-mm-dd';
$.dpText = {
    TEXT_OR             :   '或 ',
    TEXT_PREV_YEAR      :   '去年',
    TEXT_PREV_MONTH     :   '上月',
    TEXT_PREV_WEEK      :   '上周',
    TEXT_YESTERDAY      :   '昨天',
    TEXT_THIS_MONTH     :   '本月',
    TEXT_THIS_WEEK      :   '本周',
    TEXT_TODAY          :   '今天',
    TEXT_NEXT_YEAR      :   '明年',
    TEXT_NEXT_MONTH     :   '下月',
    TEXT_CLOSE          :   '关闭',
    TEXT_DATE           :   '选择时间段',
    TEXT_CHOOSE_DATE    :   '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
}
Date.dayNames       = ['日', '一', '二', '三', '四', '五', '六'];
Date.abbrDayNames   = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
Date.monthNames     = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
 
$(function() {
    $('.date').each(function(){
        time = $(this).val();
        if(!isNaN(time) && time != ''){
            var Y = time.substring(0, 4);
            var m = time.substring(4, 6);
            var d = time.substring(6, 8);
            time = Y + '-' + m + '-' + d;
            $('.date').val(time);
        }
    });

    startDate = new Date(1970, 1, 1);
    $(".date").datePicker({createButton:true, startDate:startDate})
        .dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_RIGHT)
});
</script>
