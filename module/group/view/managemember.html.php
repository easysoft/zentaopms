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
function checkall(checker, id)
{
    $('#' + id + ' input').each(function() 
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
        <td class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->createLink('group', 'browse'));?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
