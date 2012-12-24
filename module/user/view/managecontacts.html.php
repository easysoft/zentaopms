<?php
/**
 * The contacts manage page of user module.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<table class='cont-lt1'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->user->contacts->contactsList;?></div>
      <div class='box-content'>
        <?php 
        foreach($lists as $listID => $listName) echo html::a(inlink('managecontacts', "listID=$listID"), $listName) . '<br />';
        ?>
      </div>
    </td>
    <td class=='divider'></td>
    <td>
      <form method='post' target='hiddenwin' id='dataform' style='margin:0 0 0 10px; padding:0'>
      <div class='box-title' style='height:15px'>
        <div class='f-left'><?php echo $lang->user->contacts->manage;?></div>
        <div class='f-right'><?php if($mode == 'edit') echo html::a(inlink('deleteContacts', "listID=$listID"), $lang->delete, 'hiddenwin');?></div>
      </div>
      <table class='table-1 fixed'> 
        <tr>
          <th class='rowhead'><?php echo $lang->user->contacts->selectedUsers;?></th>
          <td>
            <?php
            foreach($this->view->users as $account => $realname)
            {
                echo "<span class='userSpan'><input type='checkbox' name='users[]' value='$account' checked='checked'>$realname</span>";
            }
            ?>
          </td>
        </tr>
        <tr>
          <th class='rowhead'><?php $mode == 'new' ? print($lang->user->contacts->selectList) : print($lang->user->contacts->listName);?></th>
          <td>
            <?php 
            if($mode == 'new')
            {
                if($lists)
                {
                    echo $lang->user->contacts->appendToList;
                    echo html::select('list2Append', array('' => '') + $lists, '', "class='select-2'") . $lang->user->contacts->or;
                }
                echo $lang->user->contacts->createList;
                echo html::input('newList', '', "class='text-2'");
            }
            else
            {
                echo html::input('listName', $list->listName, "class='text-2'");
                echo html::hidden('listID',  $list->id);
            }
            ?>
          </td>
        </tr>
        <tr><td></td><td><?php echo html::submitButton() . html::hidden('mode', $mode);?></td></tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
