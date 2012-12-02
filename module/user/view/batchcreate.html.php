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
<script> var batchCreateNum = '<?php echo $config->user->batchCreate;?>'; </script>
<form method='post' target='hiddenwin' id='dataform'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->user->batchCreate;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th class='w-150px'><?php echo $lang->user->dept;?></th>
      <th class='w-150px'><?php echo $lang->user->account;?></th>
      <th class='w-150px'><?php echo $lang->user->realname;?></th>
      <th><?php echo $lang->user->email;?></th>
      <th class='w-80px'><?php echo $lang->user->gender;?></th>
      <th><?php echo $lang->user->password;?></th>
    </tr>
    <?php for($i = 0; $i < $config->user->batchCreate; $i++):?>
    <?php $pri = 3;?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("dept[$i]", $depts, $deptID, "class='select-2'");?>
      <td><?php echo html::input("account[$i]", '', "class='text-2' autocomplete='off'");?></td>
      <td><?php echo html::input("realname[$i]", '', "class='text-2'");?></td>
      <td><?php echo html::input("email[$i]", '', "class='text-3'");?></td>
      <td><?php echo html::radio("gender[$i]", (array)$lang->user->genderList, 'm');?></td>
      <td><?php echo html::input("password[$i]", '', "class='text-3' autocomplete='off'");?></td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='7' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
