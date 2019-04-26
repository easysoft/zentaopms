<?php
/**
 * The common modal footer view file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     RanZhi
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php if(helper::isAjaxRequest()):?>
    </div>
  </div>
</div>
<?php if(isset($pageJS)) js::execute($pageJS);?>
<script>
$(function()
{
    var options = 
    {
        language: '<?php echo $this->app->getClientLang(); ?>',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'yyyy-mm-dd hh:ii'
    }

    $('.form-datetime').datetimepicker(options);
    $('.form-date').datetimepicker($.extend(options, {minView: 2, format: 'yyyy-mm-dd'}));
    $('.form-time').datetimepicker($.extend(options, {eleClass: 'only-pick-time', startView: 1, minView: 0, maxView: 1, format: 'hh:ii'}));
    $('.form-month').datetimepicker($.extend(options, {startView: 3, minView: 3, format: 'yyyy-mm'}));
    $('.chosen').chosen();
});
</script>
<?php else:?>
<?php include  $this->app->getAppRoot() . 'module/common/view/footer.html.php'; ?>
<?php endif;?>
