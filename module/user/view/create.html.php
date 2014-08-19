<?php
/**
 * The create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('holders', $lang->user->placeholder);?>
<?php js::set('roleGroup', $roleGroup);?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->user->create;?></strong>
    </div>
  </div>
  <form class='form-condensed mw-700px' method='post' target='hiddenwin' id='dataform'>
    <table align='center' class='table table-form'> 
      <tr>
        <th class='w-100px'><?php echo $lang->user->dept;?></th>
        <td class='w-p50'><?php echo html::select('dept', $depts, $deptID, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->account;?></th>
        <td><?php echo html::input('account', '', "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->realname;?></th>
        <td><?php echo html::input('realname', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->password;?></th>
        <td><?php echo html::password('password1', '', "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->password2;?></th>
        <td><?php echo html::password('password2', '', "class='form-control' autocomplete='off'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->role;?></th>
        <td><?php echo html::select('role', $lang->user->roleList, '', "class='form-control' onchange='changeGroup(this.value)'");?></td>
        <td><?php echo $lang->user->placeholder->role?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->group;?></th>
        <td><?php echo html::select('group', $groupList, '', "class='form-control chosen'");?></td>
        <td><?php echo $lang->user->placeholder->group?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->email;?></th>
        <td><?php echo html::input('email', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->commiter;?></th>
        <td><?php echo html::input('commiter', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->join;?></th>
        <td><?php echo html::input('join', '', "class='form-control form-date'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->gender;?></th>
        <td><?php echo html::radio('gender', (array)$lang->user->genderList, 'm');?></td>
      </tr>

      <tr><th></th><td><?php echo html::submitButton() . html::backButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
