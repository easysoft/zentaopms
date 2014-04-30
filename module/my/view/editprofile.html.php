<?php
/**
 * The edit view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div class='container mw-800px'>
  <div id='titlebar'>
    <div class='heading'><i class='icon-pencil'></i> <?php echo $lang->my->editProfile;?></div>
  </div>
  <form method='post' target='hiddenwin' class='form-condensed'>
    <fieldset>
      <legend><?php echo $lang->my->form->lblBasic;?></legend>
      <table class='table table-form'> 
        <tr>
          <th class='w-90px'><?php echo $lang->user->realname;?></th>
          <td><?php echo html::input('realname', $user->realname, "class='form-control'");?></td>
          <th class='w-90px'><?php echo $lang->user->email;?></th>
          <td><?php echo html::input('email', $user->email, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->gender;?></th>
          <td><?php echo html::radio('gender', $lang->user->genderList, $user->gender);?></td>
          <th><?php echo $lang->user->birthyear;?></th>
          <td><?php echo html::input('birthday', $user->birthday,"class='form-date form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->join;?></th>
          <td><?php
              echo $user->join;
              echo html::hidden('join',$user->join);
              ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->my->form->lblAccount;?></legend>
      <table class='table table-form'>
        <tr>
          <th class='w-90px'><?php echo $lang->user->account;?></th>
          <td><?php echo html::input('account', $user->account, "class='form-control' readonly='readonly'");?></td>
          <th class='w-90px'><?php echo $lang->user->commiter;?></th>
          <td><?php echo html::input('commiter', $user->commiter, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password;?></th>
          <td><?php echo html::password('password1', '', "class='form-control'");?></td>
          <th><?php echo $lang->user->password2;?></th>
          <td><?php echo html::password('password2', '', "class='form-control'");?></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->my->form->lblContact;?></legend>
        <table class='table table-form'>
         <tr>
          <th class='w-90px'><?php echo $lang->user->skype;?></th>
          <td><?php echo html::input('skype', $user->skype, "class='form-control'");?></td>
          <th class='w-90px'><?php echo $lang->user->qq;?></th>
          <td><?php echo html::input('qq', $user->qq, "class='form-control'");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->user->yahoo;?></th>
          <td><?php echo html::input('yahoo', $user->yahoo, "class='form-control'");?></td>
          <th><?php echo $lang->user->gtalk;?></th>
          <td><?php echo html::input('gtalk', $user->gtalk, "class='form-control'");?></td>
        </tr>  
         <tr>
          <th><?php echo $lang->user->wangwang;?></th>
          <td><?php echo html::input('wangwang', $user->wangwang, "class='form-control'");?></td>
          <th><?php echo $lang->user->mobile;?></th>
          <td><?php echo html::input('mobile', $user->mobile, "class='form-control'");?></td>
        </tr>  
         <tr>
          <th><?php echo $lang->user->phone;?></th>
          <td><?php echo html::input('phone', $user->phone, "class='form-control'");?></td>
          <th><?php echo $lang->user->address;?></th>
          <td><?php echo html::input('address', $user->address, "class='form-control'");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->user->zipcode;?></th>
          <td><?php echo html::input('zipcode', $user->zipcode, "class='form-control'");?></td>
          <td></td>
        </tr>
      </table>
    </fieldset>
    <div class='text-center'><?php echo html::submitButton('', '', 'btn-primary') . ' &nbsp; ' . html::backButton();?></div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
