<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-1 a-left'> 
    <caption><?php echo $group->name . $lang->colon . $lang->group->manageMember;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->group->inside;?><input type='checkbox' onclick='checkall(this, "group");'></th>
      <td id='group' class='f-14px pv-10px'><?php $i = 1;?>
        <?php foreach($groupUsers as $account => $realname):?>
        <div class='w-p10 f-left'><?php echo '<span>' . html::checkbox('members', array($account => $realname), $account) . '</span>';?></div>
        <?php if(($i %  8) == 0) echo "<div class='c-both'></div>"; $i ++;?>
        <?php endforeach;?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->group->outside;?><input type='checkbox' onclick='checkall(this, "other");'></th>
      <td id='other' class='f-14px pv-10px'><?php $i = 1;?>
        <?php foreach($otherUsers as $account => $realname):?>
        <div class='w-p10 f-left'><?php echo '<span>' . html::checkbox('members', array($account => $realname), '') . '</span>';?></div>
        <?php if(($i %  8) == 0) echo "<div class='c-both'></div>"; $i ++;?>
        <?php endforeach;?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'></th>
      <td class='a-center'>
        <?php 
        echo html::submitButton();
        echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
        echo html::hidden('foo'); // Just a var, to make sure $_POST is not empty.
        ?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
