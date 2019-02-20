<?php
/**
 * The timezone view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<?php include $this->app->getConfigRoot() . 'timezones.php';?>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form form-ajax" method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->custom->timezone?></strong>
      </div>
    </div>
    <table class='table table-form'>
      <tr>
        <th class='w-200px'><?php echo $lang->custom->timezone;?></th>
        <td><?php echo html::select('timezone', $timezoneList, $config->timezone, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th></th>
        <td class='form-actions'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function()
{
    $('#mainMenu #timezoneTab').addClass('btn-active-text');
})
</script>
<?php include '../../common/view/footer.html.php';?>

