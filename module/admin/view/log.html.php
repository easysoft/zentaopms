<?php
/**
 * The log view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     admin 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->webhook->setting;?></h2>
  </div>
  <div class='center-block mw-700px'>
    <form id='logForm' method='post' class='form-ajax'>
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->admin->days;?></th>
          <td><?php echo html::input('days', $config->admin->log->saveDays, "class='form-control'");?></td>
        </tr>
        <tr>
          <th></th>
          <td>
            <?php echo html::submitButton();?>
            <?php echo $lang->admin->info->log;?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
