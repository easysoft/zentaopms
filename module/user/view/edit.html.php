<?php
/**
 * The edit view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
  <table align='center' class='table-5'> 
    <caption><?php echo $lang->user->edit;?></caption>
    <tr>
      <th><?php echo $lang->user->dept;?></th>
      <td><?php echo html::select('dept', $depts, $user->dept, "class='form-control'");?>
    </tr>  
    <tr>
      <th><?php echo $lang->user->account;?></th>
      <td><?php echo html::input('account', $user->account, "class='form-control' autocomplete='off'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->realname;?></th>
      <td><?php echo html::input('realname', $user->realname, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->role;?></th>
      <td><?php echo html::select('role', $lang->user->roleList, $user->role, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->group->priv;?></th>
      <td><?php echo html::select('groups[]', $groups, $userGroups, 'size=3 multiple=multiple class=select-3');?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->commiter;?></th>
      <td><?php echo html::input('commiter', $user->commiter, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->email;?></th>
      <td><?php echo html::input('email', $user->email, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->join;?></th>
      <td><?php echo html::input('join', $user->join, "class='form-control form-date'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->gender;?></th>
      <td><?php echo html::radio('gender', (array)$lang->user->genderList, $user->gender);?></td>
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
      <th><?php echo $lang->user->skype;?></th>
      <td><?php echo html::input('skype', $user->skype, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->qq;?></th>
      <td><?php echo html::input('qq', $user->qq, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->yahoo;?></th>
      <td><?php echo html::input('yahoo', $user->yahoo, "class='form-control'");?></td>
    </tr>
     <tr>
      <th><?php echo $lang->user->gtalk;?></th>
      <td><?php echo html::input('gtalk', $user->gtalk, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->wangwang;?></th>
      <td><?php echo html::input('wangwang', $user->wangwang, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->mobile;?></th>
      <td><?php echo html::input('mobile', $user->mobile, "class='form-control'");?></td>
    </tr>
     <tr>
      <th><?php echo $lang->user->phone;?></th>
      <td><?php echo html::input('phone', $user->phone, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->address;?></th>
      <td><?php echo html::input('address', $user->address, "class='form-control'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->zipcode;?></th>
      <td><?php echo html::input('zipcode', $user->zipcode, "class='form-control'");?></td>
    </tr>
    <tr><td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
