<?php
/**
 * The edit file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     kanban
 * @version     $Id: edit.html.php 935 2021-12-09 16:15:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('acl', $kanban->acl);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->kanban->edit;?></h2>
  </div>
  <form class='form-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->kanban->space;?></th>
        <td><?php echo html::select('space', $spacePairs, $kanban->space, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->name;?></th>
        <td><?php echo html::input('name', $kanban->name, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->owner;?></th>
        <td><?php echo html::select('owner', $users, $kanban->owner, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->team;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('team[]', $users, $kanban->team, "class='form-control chosen' multiple data-drop_direction='down'");?>
            <?php echo $this->fetch('my', 'buildContactLists');?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->desc;?></th>
        <td colspan='2'>
          <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=kanban&link=desc');?>
          <?php echo html::textarea('desc', $kanban->desc, "rows='10' class='form-control'");?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->acl;?></th>
        <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->kanban->aclList, $kanban->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
      </tr>
      <tr id="whitelistBox">
        <th><?php echo $lang->whitelist;?></th>
        <td><?php echo html::select('whitelist[]', $users, $kanban->whitelist, 'class="form-control chosen" multiple');?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
