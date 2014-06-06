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
            if($this.val() != '' && !$this.hasClass('form-time'))
            {
                var date = new Date($this.val());
                if(!date.valueOf()) date = new Date();

                if($this.hasClass('form-datetime')) $this.val(date.format('yyyy-MM-dd hh:mm'));
                else $this.val(date.format('yyyy-MM-dd'));
            }
            return $this;
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
        format: 'yyyy-mm-dd hh:ii'
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
