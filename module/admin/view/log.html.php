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
<?php include $this->app->getModuleRoot() . 'webhook/view/header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<div class='container mw-600px'>
  <form id='logForm' method='post' class='ajaxForm'>
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
<?php include '../../common/view/footer.html.php';?>
