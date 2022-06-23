<?php
/**
 * The create file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: create.html.php 935 2021-12-09 13:48:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('spaceType', $type);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->kanban->create;?></h2>
  </div>
  <form class='form-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->kanbanspace->type;?></th>
        <td><?php echo html::radio('type', $typeList, $type, "onchange='changeValue({$spaceID}, this.value)'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->WIPCount;?></th>
        <td><?php echo html::radio('showWIP', $lang->kanban->showWIPList, 1);?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->space;?></th>
        <td><?php echo html::select('space', $spacePairs, $spaceID, "onchange='changeValue(this.value)' class='form-control chosen'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->name;?></th>
        <td><?php echo html::input('name', '', "class='form-control'");?></td>
      </tr>
      <?php if($type != 'private'):?>
      <tr>
        <th><?php echo $lang->kanban->owner;?></th>
        <td><?php echo html::select('owner', $users, '', "class='form-control chosen' data-drop_direction='down'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->team;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('team[]', $users, '', "class='form-control picker-select' multiple data-dropDirection='bottom'");?>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->kanban->desc;?></th>
        <td colspan='2'>
          <?php echo html::textarea('desc', '', "rows='10' class='form-control'");?>
        </td>
      </tr>
      <?php if($type == 'private'):?>
      <tr id="whitelistBox">
        <th><?php echo $lang->whitelist;?></th>
        <td><?php echo html::select('whitelist[]', $whitelist, '', 'class="form-control picker-select" multiple');?></td>
      </tr>
      <?php endif;?>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
