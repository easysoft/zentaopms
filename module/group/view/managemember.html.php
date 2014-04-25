<?php
/**
 * The manage member view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managemember.html.php 4627 2013-04-10 05:42:20Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='GROUP'><?php echo html::icon($lang->icons['group']);?> <strong><?php echo $group->id;?></strong></span>
    <strong><?php echo $group->name;?></strong>
    <small class='text-muted'> <?php echo $lang->group->manageMember;?> <?php echo html::icon($lang->icons['manage']);?></small>
  </div>
</div>

<form class='form-condensed pdb-20' method='post' target='hiddenwin'>
  <table align='center' class='table table-form'> 
    <?php if($groupUsers):?>
    <tr>
      <th class='w-100px'><?php echo $lang->group->inside;?><?php echo html::selectAll('group', 'checkbox', true);?> </th>
      <td id='group' class='f-14px pv-10px'><?php $i = 1;?>
        <?php foreach($groupUsers as $account => $realname):?>
        <div class='group-item'><?php echo html::checkbox('members', array($account => $realname), $account);?></div>
        <?php endforeach;?>
      </td>
    </tr>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->group->outside;?><?php echo html::selectAll('other','checkbox');?> </th>
      <td id='other' class='f-14px pv-10px'><?php $i = 1;?>
        <?php foreach($otherUsers as $account => $realname):?>
        <div class='group-item'><?php echo html::checkbox('members', array($account => $realname), '');?></div>
        <?php endforeach;?>
      </td>
    </tr>
    <tr>
      <th></th>
      <td class='text-center'>
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
