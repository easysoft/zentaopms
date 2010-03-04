<link rel='stylesheet' href='<?php echo $clientTheme;?>datepicker.css' type='text/css' />
<script src='<?php echo $jsRoot;?>jquery/datepicker/min.js'  type='text/javascript'></script>
<script src='<?php echo $jsRoot;?>jquery/datepicker/date.js' type='text/javascript'></script>
<script language='javascript'>
Date.firstDayOfWeek = 1;
Date.format = 'yyyy-mm-dd';
$.dpText = {
    TEXT_PREV_YEAR      :   '去年',
    TEXT_PREV_MONTH     :   '上个月',
    TEXT_NEXT_YEAR      :   '明年',
    TEXT_NEXT_MONTH     :   '下个月',
    TEXT_CLOSE          :   '关闭',
    TEXT_CHOOSE_DATE    :   '...'
}
Date.dayNames       = ['日', '一', '二', '三', '四', '五', '六'];
Date.abbrDayNames   = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
Date.monthNames     = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
 
$(function() {$(".date")
    .datePicker({createButton:false, startDate:'2009-05-03'})
    .bind('click', function() {
        $(this).dpDisplay();
        this.blur();
        return false;
    }
    );
});
</script>
