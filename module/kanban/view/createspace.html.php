<?php
/**
 * The createspace file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
  <form class='form-indicator main-form form-ajax no-stash' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->kanbanspace->type;?></th>
        <td><?php echo html::radio('type', $typeList, $type, "onchange='changeType(this.value)'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanbanspace->name;?></th>
        <td><?php echo html::input('name', '', "class='form-control'");?></td>
      </tr>
      <tr id='ownerBox' class="<?php echo $type == 'private' ? 'hidden' : '';?>">
        <th><?php echo $lang->kanbanspace->owner;?></th>
        <td><?php echo html::select('owner', $users, '', "class='form-control chosen'");?></td>
      </tr>
      <tr id='teamBox' class="<?php echo $type == 'private' ? 'hidden' : '';?>">
        <th><?php echo $lang->kanbanspace->team;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('team[]', $users, '', "class='form-control picker-select' multiple data-drop-direction='bottom'");?>
            <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=team");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kanbanspace->desc;?></th>
        <td colspan='2'>
          <?php echo html::textarea('desc', '', "rows='10' class='form-control'");?>
        </td>
      </tr>
      <tr id="whitelistBox" class="<?php echo $type == 'private' ? '' : 'hidden';?>">
        <th><?php echo $lang->whitelist;?></th>
        <td colspan='2'><?php echo html::select('whitelist[]', $users, '', 'class="form-control picker-select" multiple data-drop-direction="top"');?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
