<?php
/**
 * The contacts manage page of my module.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='row'>
  <?php if($mode == 'edit'):?>
  <div class='col-sm-3 col-lg-2'>
    <ul class='list-group'>
    <?php
    foreach($lists as $id => $listName)
    {
        $listClass = ($id == $listID) ? 'list-group-item active' : 'list-group-item';
        echo html::a(inlink('managecontacts', "listID=$id&mode=edit"), $listName, '', "class='{$listClass}'");
    }
    ?>
    </ul>
    <?php echo html::a(inlink('managecontacts', "listID=0&mode=new"), '<i class="icon icon-plus"></i> ' . $lang->user->contacts->createList, '', "class='btn btn-block'"); ?>
  </div>
  <?php endif;?>
  <?php $class = $mode == 'edit' ? 'col-sm-9 col-lg-10' : 'col-sm-12 col-lg-12';?>
    <div class='<?php echo $class?>'>
    <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
      <div class='panel panel-sm'>
        <div class='panel-heading'>
          <?php if($mode == 'new'):?>
          <i class='icon-plus'></i> <strong><?php echo $lang->user->contacts->createList;?></strong>
          <?php else:?>
          <i class='icon-cogs'></i> <strong><?php echo $lang->user->contacts->manage;?></strong>
          <?php endif;?>
        </div>
        <div class='panel-body'>
          <table class='table table-form'> 
            <tr>
              <th class='w-80px'><?php echo $lang->user->contacts->listName;?></th>
              <td>
              <?php
              if($mode == 'new')
              {
                  echo html::input('newList', '', "class='form-control w-300px'");
              }
              else
              {
                  echo html::input('listName', $list->listName, "class='form-control w-300px'");
                  echo html::hidden('listID',  $list->id);
              }
              ?>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->user->contacts->selectedUsers;?></th>
              <td>
              <?php
              if($mode == 'new')
              {
                  echo html::select('users[]', $users, '', "multiple class='form-control chosen'");
              }
              else
              {
                  echo html::select('users[]', $users, $list->userList, "multiple class='form-control chosen'");
              }
              ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
              <?php echo html::submitButton() . html::hidden('mode', $mode);?>
              <?php if($mode == 'edit') echo html::a(inlink('deleteContacts', "listID=$listID"), $lang->delete, 'hiddenwin', "class='btn btn-danger'");?>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
