<?php
/**
 * The batch create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->user->batchEdit;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th class='w-150px'><?php echo $lang->user->dept;?></th>
      <th class='w-120px'><?php echo $lang->user->account;?></th>
      <th class='w-120px'><?php echo $lang->user->realname;?></th>
      <th class='w-100px'><?php echo $lang->user->role;?></th>
      <th><?php echo $lang->user->commiter;?></th>
      <th><?php echo $lang->user->email;?></th>
      <th class='w-120px'><?php echo $lang->user->join;?></th>
    </tr>
    <?php $depts = $depts + array('ditto' => $lang->user->ditto)?>
    <?php $lang->user->roleList = $lang->user->roleList + array('ditto' => $lang->user->ditto)?>
    <?php foreach($users as $user):?>
    <tr class='text-center'>
      <td><?php echo $user->id;?></td>
      <td><?php echo html::select("dept[$user->id]", $depts, empty($user->dept) ? 'ditto' : $user->dept, "class='form-control'");?>
      <td><?php echo html::input("account[$user->id]", $user->account, "class='form-control' autocomplete='off'");?></td>
      <td><?php echo html::input("realname[$user->id]", $user->realname, "class='form-control'");?></td>
      <td><?php echo html::select("role[$user->id]", $lang->user->roleList, empty($user->role) ? 'ditto' : $user->role, "class='form-control'");?>
      <td><?php echo html::input("commiter[$user->id]", $user->commiter, "class='form-control'");?></td>
      <td><?php echo html::input("email[$user->id]", $user->email, "class='form-control'");?></td>
      <td><?php echo html::input("join[$user->id]", $user->join, "class='w-p70 date'");?></td>
    </tr>
    <?php endforeach;?>
    <tr><td colspan='7' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
