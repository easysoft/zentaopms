<?php
/**
 * The contacts manage page of my module.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content main-row'>
  <div class='side-col'>
  <div class='main-header'><h2 class='contactListTitle'><?php echo $lang->my->contactList;?></h2></div>
    <div class="list-group">
    <?php echo html::a(inlink('managecontacts', "listID=0&mode=new"), '<i class="icon icon-plus"></i> ' . $lang->user->contacts->createList, '', "class='btn btn-secondary createBtn'"); ?>
    <?php
    foreach($lists as $id => $listName)
    {
        $listClass = ($id == $listID and $mode == 'edit') ? 'btn btn-block active' : 'btn btn-block';
        echo html::a(inlink('managecontacts', "listID=$id&mode=edit"), $listName, '', "class='{$listClass}' title='$listName'");
    }
    ?>
    </div>
  </div>
  <div class='main-col'>
    <div class='main-header'>
      <h2 class='title'>
        <?php if($mode == 'new'):?>
        <strong><?php echo $lang->user->contacts->createList;?></strong>
        <?php else:?>
        <strong><?php echo $lang->user->contacts->manage;?></strong>
        <?php endif;?>
      </h2>
    </div>

    <form method='post' target='hiddenwin' id='dataform' class='main-form form-ajax' enctype='multipart/form-data'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->user->contacts->listName;?></th>
          <td class='w-300px'>
          <div class='required required-wrapper'></div>
          <?php
          if($mode == 'edit') $readonly = in_array($list->id, $disabled) ? ' readonly' : '';
          if($mode == 'new')
          {
              echo html::input('newList', '', "class='form-control'");
          }
          else
          {
              echo html::input('listName', $list->listName, "$readonly class='form-control'");
              echo html::hidden('listID',  $list->id);
          }
          ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->contacts->selectedUsers;?></th>
          <td colspan='2'>
          <?php
          if($mode == 'new')
          {
              echo html::select('users[]', $users, '', "multiple class='form-control picker' data-drop_direction='down'");
          }
          else
          {
              echo html::select('users[]', $users, $list->userList, "multiple $readonly class='form-control picker' data-drop_direction='down'");
          }
          ?>
          </td>
        </tr>
        <?php if(common::hasPriv('datatable', 'setGlobal')):?>
        <tr>
          <th></th>
          <td colspan="2">
            <label class="checkbox-primary">
              <input type="checkbox" name="share" value="1" id="shareCheckbox" <?php if($mode != 'new' && in_array($list->id, $globalContacts)) echo 'checked';?>/>
              <label for="shareCheckbox"><?php echo $lang->my->shareContacts;?></label>
            </label>
          </td>
        </tr>
        <?php endif;?>
        <?php if($mode == 'new' || !in_array($list->id, $disabled)):?>
        <tr>
          <td></td>
          <td class="form-actions">
          <?php echo html::submitButton() . html::hidden('mode', $mode);?>
          <?php if($mode == 'edit') echo html::a(inlink('deleteContacts', "listID=$listID"), $lang->delete, 'hiddenwin', "class='btn btn-danger btn-wide'");?>
          </td>
        </tr>
        <?php endif;?>
      </table>
    </form>
  <?php if($mode == 'edit'):?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
