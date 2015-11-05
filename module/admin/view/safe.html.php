<?php
/**
 * The safe view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
    <li class='active'><?php common::printLink('admin', 'safe', '', $lang->admin->safe->set);?></li>
    <li><?php common::printLink('admin', 'checkWeak', '', $lang->admin->safe->checkWeak);?></li>
  </ul>
</div>
<div class='container mw-800px'>
  <form method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->admin->safe->password?></th>
        <td><?php echo html::radio('mode', $lang->admin->safe->modeList, isset($config->safe->mode) ? $config->safe->mode : 0)?></td>
        <td><?php echo $lang->admin->safe->noticeMode?></td>
      </tr>
      <tr>
        <th></th>
        <td colspan='2'><span style='color:#03b8cf;font-weight:bold;'><?php echo $lang->admin->safe->noticeStrong;?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->admin->safe->weak?></th>
        <td colspan='2'><?php echo html::textarea('weak', $config->safe->weak, "class='form-control' rows='4'")?></td>
      </tr>
      <tr>
        <th></th>
        <td colspan='2'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
