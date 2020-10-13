<?php
/**
 * The view view of stakeholder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->stakeholder->createExpect;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='expectForm'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->stakeholder->common;?></th>
            <td><?php echo html::select('userID', $users, '', "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->expect;?></th>
            <td colspan="2"><?php echo html::textarea('expect', '', "rows='6'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->stakeholder->progress;?></th>
            <td colspan="2"><?php echo html::textarea('progress', '', "rows='6'");?></td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
