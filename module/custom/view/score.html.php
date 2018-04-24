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
  <div class='center-block'>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-100px text-top'><?php echo $lang->custom->score;?></th>
          <td><?php echo html::radio('score', $lang->custom->scoreStatus, isset($config->global->scoreStatus) ? $config->global->scoreStatus : 0, '', 'block');?></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <?php echo html::submitButton();?>
            <?php common::printLink('score', 'reset', '', "<i class='icon-refresh'></i> " . $lang->custom->scoreReset, '', ' id="scoreRefresh" class="btn iframe" data-width="480"', true, true);?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
$(function()
{
    $('#mainMenu #scoreTab').addClass('btn-active-text');
})
</script>
<?php include '../../common/view/footer.html.php';?>
