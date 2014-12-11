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
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->user->batchEdit;?></strong>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-150px'><?php echo $lang->user->dept;?></th>
        <th class='w-150px'><?php echo $lang->user->account;?></th>
        <th class='w-150px'><?php echo $lang->user->realname;?></th>
        <th class='w-120px'><?php echo $lang->user->role;?></th>
        <th><?php echo $lang->user->commiter;?></th>
        <th><?php echo $lang->user->email;?></th>
        <th class='w-120px'><?php echo $lang->user->join;?></th>
      </tr>
    </thead>
    <?php $depts = $depts + array('ditto' => $lang->user->ditto)?>
    <?php $lang->user->roleList = $lang->user->roleList + array('ditto' => $lang->user->ditto)?>
    <?php $first = true;?>
    <?php foreach($users as $user):?>
    <?php
    $dept  = ($first and empty($user->dept)) ? 0 : (empty($user->dept) ? 'ditto' : $user->dept); 
    $role  = ($first and empty($user->role)) ? 0 : (empty($user->role) ? 'ditto' : $user->role); 
    $first = false;
    ?>
    <tr class='text-center'>
      <td><?php echo $user->id;?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("dept[$user->id]", $depts, $dept, "class='form-control chosen'");?></td>
      <td><?php echo html::input("account[$user->id]", $user->account, "class='form-control' autocomplete='off'");?></td>
      <td><?php echo html::input("realname[$user->id]", $user->realname, "class='form-control'");?></td>
      <td><?php echo html::select("role[$user->id]", $lang->user->roleList, $role, "class='form-control'");?></td>
      <td><?php echo html::input("commiter[$user->id]", $user->commiter, "class='form-control'");?></td>
      <td><?php echo html::input("email[$user->id]", $user->email, "class='form-control'");?></td>
      <td><?php echo html::input("join[$user->id]", $user->join, "class='form-control form-date'");?></td>
    </tr>
    <?php endforeach;?>
    <tr><td colspan='8' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
