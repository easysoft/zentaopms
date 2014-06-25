<?php
/**
 * The contacts manage page of user module.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='COMPANY'><?php echo html::icon($lang->icons['company']);?></span>
    <strong><small class='text-muted'><?php echo html::icon('cogs');?></small> <?php echo $lang->user->contacts->manage;?></strong>
  </div>
</div>
<div class='row'>
  <div class='col-md-3 col-lg-2'>
    <div class='panel panel-sm with-list'>
      <div class='panel-heading'>
        <i class='icon-list-ul'></i> <strong><?php echo $lang->user->contacts->contactsList;?></strong>
      </div>
      <ul class='list-group'>
        <?php 
        foreach($lists as $listID => $listName) echo html::a(inlink('managecontacts', "listID=$listID"), $listName, '', "class='list-group-item'");
        ?>
      </ul>
    </div>
  </div>
  <div class='col-md-9 col-lg-10'>
    <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
      <div class='panel panel-sm'>
        <div class='panel-heading'>
          <i class='icon-cogs'></i> <strong><?php echo $lang->user->contacts->manage;?></strong>
          <div class='panel-actions pull-right'>
            <?php if($mode == 'edit') echo html::a(inlink('deleteContacts', "listID=$listID"), $lang->delete, 'hiddenwin', "class='btn btn-danger'");?>
          </div>
        </div>
        <div class='panel-body'>
          <table class='table table-form table-fixed'> 
            <tr>
              <th class='w-80px'><?php echo $lang->user->contacts->selectedUsers;?></th>
              <td>
                <?php
                foreach($this->view->users as $account => $realname)
                {
                    echo "<div class='userSpan group-item'><input type='checkbox' name='users[]' value='$account' checked='checked'> $realname</div>";
                }
                ?>
              </td>
            </tr>
            <tr>
              <th><?php $mode == 'new' ? print($lang->user->contacts->selectList) : print($lang->user->contacts->listName);?></th>
              <td>
                <?php 
                if($mode == 'new')
                {
                    echo "<div class='input-group clearfix mw-700px'>";
                    if($lists)
                    {
                        echo "<span class='input-group-addon'>" . $lang->user->contacts->appendToList . '</span>';
                        echo html::select('list2Append', array('' => '') + $lists, '', "class='form-control'");
                    }
                    echo "<span class='input-group-addon'>";
                    if($lists) echo $lang->user->contacts->or;
                    echo $lang->user->contacts->createList;
                    echo '</span>';
                    echo html::input('newList', '', "class='form-control'");
                    echo '</div>';
                }
                else
                {
                    echo html::input('listName', $list->listName, "class='form-control'");
                    echo html::hidden('listID',  $list->id);
                }
                ?>
              </td>
            </tr>
            <tr><td></td><td><?php echo html::submitButton() . html::hidden('mode', $mode);?></td></tr>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
