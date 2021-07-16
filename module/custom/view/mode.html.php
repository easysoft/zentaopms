<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php js::set('newTips', $lang->upgrade->selectedModeTips['new'])?>
<?php js::set('classicTips', $lang->custom->changeClassicTip)?>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form" method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->custom->mode?></strong>
      </div>
    </div>
    <table class='table table-form'>
      <tr>
        <th class='text-top'><?php echo $lang->custom->mode;?></th>
        <td>
          <p>
            <?php $isDisabled = $changedMode == 'yes' ? ' disabled' : '';?>
            <label class="radio-inline"><input type="radio" name="mode" value="classic" <?php echo $mode == 'classic'? "checked='checked'" : ''; echo $isDisabled;?> id="modeclassic"><?php echo $lang->upgrade->to15Mode['classic'];?></label>
            <label class="radio-inline"><input type="radio" name="mode" value="new" <?php echo $mode == 'new'? "checked='checked'" : ''; echo $isDisabled;?> id="modenew"><?php echo $lang->upgrade->to15Mode['new'];?></label>
          </p>
          <p class='text-info' id='modeTips'><?php echo $mode == 'classic' ? $lang->custom->changeClassicTip : $lang->upgrade->selectedModeTips['new'];?></p>
        </td>
      </tr>
      <tr>
        <td></td>
        <td><?php if($changedMode != 'yes') echo html::submitButton($lang->custom->switch);?></td>
      </tr>
    </table>
  </form>
</div>
<script>
$('#modeTab').addClass('btn-active-text');
</script>
<?php include '../../common/view/footer.html.php';?>
