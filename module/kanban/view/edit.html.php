<?php
/**
 * The edit file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     kanban
 * @version     $Id: edit.html.php 935 2021-12-09 16:15:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('vision', $this->config->vision);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->kanban->edit;?></h2>
  </div>
  <form class='form-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->kanban->space;?></th>
        <td><?php echo html::select('space', $spacePairs, $kanban->space, "class='form-control chosen' onchange='loadOwners(this.value)'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->name;?></th>
        <td><?php echo html::input('name', $kanban->name, "class='form-control'");?></td>
      </tr>
      <?php if($type != 'private'):?>
      <tr>
        <th><?php echo $lang->kanban->owner;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('owner', $ownerPairs, $kanban->owner, "class='form-control chosen' data-drop_direction='down'");?>
            <span class='input-group-btn'><?php echo html::commonButton($lang->kanban->allUsers, "class='btn btn-default' onclick='loadAllUsers()' data-toggle='tooltip'");?></span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->team;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('team[]', $users, $kanban->team, "class='form-control picker-select' multiple data-dropDirection='bottom'");?>
            <?php echo $this->fetch('my', 'buildContactLists', 'dropdownName=team');?>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->kanban->desc;?></th>
        <td colspan='2'>
          <?php echo html::textarea('desc', $kanban->desc, "rows='10' class='form-control'");?>
        </td>
      </tr>
      <?php if($type == 'private'):?>
      <tr id="whitelistBox">
        <th><?php echo $lang->whitelist;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('whitelist[]', $users, $kanban->whitelist, 'class="form-control picker-select" multiple');?>
            <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist&attr=data-drop_direction='up'");?>
          </div>
        </td>
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
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
