<?php
/**
 * The createspace file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: createspace.html.php 935 2021-12-08 14:04:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->kanban->createSpace;?></h2>
  </div>
  <form class='form-indicator main-form' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->kanban->spaceName;?></th>
        <td><?php echo html::input('name', '', "class='form-control'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->owner;?></th>
        <td><?php echo html::select('owner', $users, '', "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->team;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('mailto[]', $users, '', "class='form-control chosen' multiple");?>
            <?php echo $this->fetch('my', 'buildContactLists');?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->spaceDesc;?></th>
        <td colspan='2'>
          <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=space&link=desc');?>
          <?php echo html::textarea('desc', '', "rows='10' class='form-control'");?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->acl;?></th>
        <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->kanban->spaceAclList, 'private', "onclick='setWhite(this.value);'", 'block'));?></td>
      </tr>
      <tr id="whitelistBox">
        <th><?php echo $lang->whitelist;?></th>
        <td><?php echo html::select('whitelist[]', $users, '', 'class="form-control chosen" multiple');?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
