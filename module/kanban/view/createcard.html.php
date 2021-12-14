<?php
/**
 * The create card view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian<tianshujie@cnezsoft.com>
 * @package     kanban 
 * @version     $Id: createcard.html.php 5090 2021-12-13 13:49:24Z tainshujie@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanbancard->create;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->kanbancard->estimate;?></th>
          <td>
            <div class="input-group">
              <input type="text" name="estimate" id="estimate" value="" class="form-control" autocomplete="off">
              <span class="input-group-addon"><?php echo $lang->kanbancard->lblHour;?></span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->assignedTo;?></th>
          <td>
            <?php echo html::select('assignedTo', $users, $app->user->account, "class='form-control chosen'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->beginAndEnd;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::input('begin', '', "class='form-control form-date form-date' placeholder='{$lang->kanbancard->begin}'");?>
              <span class='input-group-addon fix-border'>~</span>
              <?php echo html::input('end', '', "class='form-control form-date form-date' placeholder='{$lang->kanbancard->end}'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->name;?></th>
          <td colspan='2'>
            <div class='required required-wrapper'></div>
            <?php echo html::input('name', '', "class='form-control'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->desc;?></th>
          <td colspan='3'><?php echo html::textarea('desc', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
