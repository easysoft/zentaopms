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
$.dpText = <?php echo json_encode($lang->datepicker->dpText)?>

Date.dayNames     = <?php echo json_encode($lang->datepicker->dayNames)?>;
Date.abbrDayNames = <?php echo json_encode($lang->datepicker->abbrDayNames)?>;
Date.monthNames   = <?php echo json_encode($lang->datepicker->monthNames)?>; 

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
