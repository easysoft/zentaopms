<?php
/**
 * The score view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form form-ajax" method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->custom->score?></strong>
      </div>
    </div>
    <table class='table table-form'>
      <tr>
        <th class='w-100px text-top'><?php echo $lang->custom->score;?></th>
        <td>
          <?php $checkedKey = isset($config->global->scoreStatus) ? $config->global->scoreStatus : 0;?>
          <?php foreach($lang->custom->scoreStatus as $key => $value):?>
          <p><label class="radio-inline"><input type="radio" name="score" value="<?php echo $key?>"<?php echo $key == $checkedKey ? " checked='checked'" : ''?> id="score<?php echo $key;?>"><?php echo $value;?></label></p>
          <?php endforeach;?>
        </td>
      </tr>
      <tr>
        <th></th>
        <td class='form-actions'>
          <?php echo html::submitButton('', '', 'btn btn-primary btn-wide');?>
          <?php common::printLink('score', 'reset', '', "<i class='icon-refresh'></i> " . $lang->custom->scoreReset, '', ' id="scoreRefresh" class="btn btn-wide iframe" data-width="480"', true, true);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function()
{
    $('#mainMenu #scoreTab').addClass('btn-active-text');
})
</script>
<?php include '../../common/view/footer.html.php';?>
