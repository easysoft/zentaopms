<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$clientLang = $this->app->getClientLang();
if($config->debug)
{
    css::import($jsRoot . 'jquery/datetimepicker/min.css');
    js::import($jsRoot . 'jquery/datetimepicker/min.js'); 
}
?>
<script language='javascript'>
$(function()
{
    $.fn.fixedDate = function()
    {
        return $(this).each(function()
        {
            var $this = $(this);
            if($this.offset().top + 200 > $(document.body).height())
            {
                $this.attr('data-picker-position', 'top-right');
            }

            if($this.val() == '0000-00-00')
            {
                $this.focus(function(){if($this.val() == '0000-00-00') $this.val('').datetimepicker('update');}).blur(function(){if($this.val() == '') $this.val('0000-00-00')});
            }
        });
    };

    var options = 
    {
        language: '<?php echo $clientLang; ?>',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'yyyy-mm-dd hh:ii',
        startDate: '1970-1-1'
    }

    $('.form-datetime').fixedDate().datetimepicker(options);
    $('.form-date').fixedDate().datetimepicker($.extend(options, {minView: 2, format: 'yyyy-mm-dd'}));
    $('.form-time').fixedDate().datetimepicker($.extend(options, {startView: 1, minView: 0, maxView: 1, format: 'hh:ii'}));

    $('.datepicker-wrapper').click(function()
    {
        $(this).find('.form-date, .form-datetime, .form-time').datetimepicker('show').focus();
    });

    window.datepickerOptions = options;
});
</script>
