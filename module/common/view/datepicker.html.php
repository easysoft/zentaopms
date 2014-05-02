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
/**
 * Format date to a string
 *
 * @param  string   format
 * @return string
 */
Date.prototype.format = function(format)
{
    var date =
    {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format))
    {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date)
    {
        if (new RegExp("(" + k + ")").test(format))
        {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
}

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
});
</script>
