<?php
/**
 * The manage member view of group module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>#users span{display:block; width:100px; float:left}</style>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $group->name . $lang->colon . $lang->group->manageMember;?></caption>
      <tr>
        <td id='users'><?php foreach($allUsers as $account => $realname) echo '<span>' . html::checkbox('members', array($account => $realname), $groupUsers) . '</span>';?></td>
      </tr>
      <tr><td class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->createLink('group', 'browse'));?></td></tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
