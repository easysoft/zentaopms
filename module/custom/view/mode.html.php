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
            <label class="radio-inline"><input type="radio" name="mode" value="classic" checked='checked' id="modeclassic"><?php echo $lang->upgrade->to15Mode['classic'];?></label>
            <label class="radio-inline"><input type="radio" name="mode" value="new" id="modenew"><?php echo $lang->upgrade->to15Mode['new'];?></label>
          </p>
          <p class='text-info'><?php echo $lang->upgrade->selectedModeTips['new'];?></p>
        </td>
      </tr>
      <tr><td></td><td><?php echo html::submitButton($lang->upgrade->common);?></td></tr>
    </table>
  </form>
</div>
<script>
$('#modeTab').addClass('btn-active-text');
</script>
<?php include '../../common/view/footer.html.php';?>
