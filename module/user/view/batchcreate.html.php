<?php
/**
 * The batch create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<form method='post' target='hiddenwin' id='dataform'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->user->batchCreate;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th class='w-150px'><?php echo $lang->user->dept;?></th>
      <th class='w-130px'><?php echo $lang->user->account;?></th>
      <th class='w-130px'><?php echo $lang->user->realname;?></th>
      <th class='w-100px'><?php echo $lang->user->role;?></th>
      <th><?php echo $lang->user->email;?></th>
      <th class='w-60px'><?php echo $lang->user->gender;?></th>
      <th><?php echo $lang->user->password;?></th>
    </tr>
    <?php $depts = $depts + array('ditto' => $lang->user->ditto)?>
    <?php $lang->user->roleList = $lang->user->roleList + array('ditto' => $lang->user->ditto)?>
    <?php for($i = 0; $i < $config->user->batchCreate; $i++):?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("dept[$i]", $depts, $i > 0 ? 'ditto' : $deptID, "class='select-1'");?>
      <td><?php echo html::input("account[$i]", '', "class='text-1 account_$i' autocomplete='off' onchange='changeEmail($i)'");?></td>
      <td><?php echo html::input("realname[$i]", '', "class='text-1'");?></td>
      <td><?php echo html::select("role[$i]", $lang->user->roleList, $i > 0 ? 'ditto' : '', "class='select-1'");?></td>
      <td><?php echo html::input("email[$i]", '', "class='text-1 email_$i' onchange='setDefaultEmail($i)'");?></td>
      <td><?php echo html::radio("gender[$i]", (array)$lang->user->genderList, 'm');?></td>
      <td>
      <?php
      echo html::input("password[$i]", '', "class='w-p70' autocomplete='off'");
      echo "<input type='checkbox' name='ditto[$i]' " . ($i> 0 ? "checked" : '') . " /> {$lang->user->ditto}";
      ?>
      </td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='8' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
