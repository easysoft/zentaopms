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
<?php js::set('roleGroup', $roleGroup);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->user->batchCreate;?></strong>
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
  <table class='table table-form table-fixed'> 
    <thead>
      <tr>
        <th class='w-40px'><?php echo $lang->idAB;?></th> 
        <th class='w-150px'><?php echo $lang->user->dept;?></th>
        <th class='w-130px red'><?php echo $lang->user->account;?></th>
        <th class='w-130px red'><?php echo $lang->user->realname;?></th>
        <th class='w-120px red'><?php echo $lang->user->role;?></th>
        <th class='w-120px'><?php echo $lang->user->group;?></th>
        <th><?php echo $lang->user->email;?></th>
        <th class='w-90px'><?php echo $lang->user->gender;?></th>
        <th class="w-p20 red"><?php echo $lang->user->password;?></th>
      </tr>
    </thead>
    <?php $depts = $depts + array('ditto' => $lang->user->ditto)?>
    <?php $lang->user->roleList = $lang->user->roleList + array('ditto' => $lang->user->ditto)?>
    <?php $groupList = $groupList + array('ditto' => $lang->user->ditto)?>
    <?php for($i = 0; $i < $config->user->batchCreate; $i++):?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("dept[$i]", $depts, $i > 0 ? 'ditto' : $deptID, "class='form-control chosen'");?></td>
      <td><?php echo html::input("account[$i]", '', "class='form-control account_$i' autocomplete='off' onchange='changeEmail($i)'");?></td>
      <td><?php echo html::input("realname[$i]", '', "class='form-control'");?></td>
      <td><?php echo html::select("role[$i]", $lang->user->roleList, $i > 0 ? 'ditto' : '', "class='form-control' onchange='changeGroup(this.value, $i)'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("group[$i]", $groupList, $i > 0 ? 'ditto' : '', "class='form-control chosen'");?></td>
      <td><?php echo html::input("email[$i]", '', "class='form-control email_$i' onchange='setDefaultEmail($i)'");?></td>
      <td><?php echo html::radio("gender[$i]", (array)$lang->user->genderList, 'm');?></td>
      <td align='left'>
        <div class='input-group'>
        <?php
        echo html::input("password[$i]", '', "class='form-control' autocomplete='off' onkeyup='toggleCheck(this, $i)'");
        if($i != 0) echo "<span class='input-group-addon'><input type='checkbox' name='ditto[$i]' id='ditto$i' " . ($i> 0 ? "checked" : '') . " /> {$lang->user->ditto}</span>";
        ?>
        </div>
      </td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='9' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
