<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<script language="Javascript">
function checkall(checker)
{
    $('input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}
</script>
<?php include '../../common/view/header.html.php';?>
<style>#users span{display:block; width:100px; float:left}</style>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $group->name . $lang->colon . $lang->group->manageMember;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->group->checkall;?><input type='checkbox' onclick='checkall(this);'></th>
        <td id='users'><?php foreach($allUsers as $account => $realname) echo '<span>' . html::checkbox('members', array($account => $realname), $groupUsers) . '</span>';?></td>
      </tr>
      <tr>
        <th class='rowhead'></th>
        <td class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->createLink('group', 'browse'));?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
