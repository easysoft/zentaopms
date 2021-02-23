<?php
/**
 * The to20 view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='panel' style='padding:20px;'>
    <form method='post'>
      <div class='panel-title text-center'><?php echo $lang->upgrade->to15Guide;?></div>
      <div class='panel-body'>
        <div style='width:600px; margin: auto;'>
          <?php echo $lang->upgrade->to15Desc;?>
          <?php echo html::radio('mode', $lang->upgrade->to15Mode, 'old');?>
          <div id='selectedModeTips' class='text-info'><?php echo $lang->upgrade->selectedModeTips['old'];?></div>
        </div>
      </div>
      <div class='panel-footer text-center'><?php echo html::submitButton();?></div>
    </form>
  </div>
</div>
<?php js::set('selectedModeTips', $lang->upgrade->selectedModeTips);?>
<script>
$(function()
{
    $('[name=mode]').change(function()
    {
        $('#selectedModeTips').html(selectedModeTips[$(this).val()]);
    })
})
</script>
<?php include '../../common/view/footer.lite.html.php';?>
