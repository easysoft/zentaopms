<?php
/**
 * The edit view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-4 a-left'> 
    <caption><?php echo $lang->my->editProfile;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->user->account;?></th>
      <td><?php echo $user->account . html::hidden('account',$user->account);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->realname;?></th>
      <td><?php echo html::input('realname', $user->realname);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->commiter;?></th>
      <td><?php echo html::input('commiter', $user->commiter);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->email;?></th>
      <td><?php echo html::input('email', $user->email);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->gender;?></th>
      <td><?php echo html::radio('gender', $lang->user->genderList, $user->gender);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->password;?></th>
      <td><?php echo html::password('password1');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->password2;?></th>
      <td><?php echo html::password('password2');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->user->birthyear;?></th>
      <td><?php echo html::input('birthday', $user->birthday,"class='date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->join;?></th>
      <td><?php //echo html::input('account', $user->account, "readonly");
                echo $user->join;
                echo html::hidden('join',$user->join);
          ?>
      </td>
    </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->user->msn;?></th>
      <td><?php echo html::input('msn', $user->msn);?></td>
    </tr>  
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->qq;?></th>
      <td><?php echo html::input('qq', $user->qq);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->user->yahoo;?></th>
      <td><?php echo html::input('yahoo', $user->yahoo);?></td>
    </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->user->gtalk;?></th>
      <td><?php echo html::input('gtalk', $user->gtalk);?></td>
    </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->user->wangwang;?></th>
      <td><?php echo html::input('wangwang', $user->wangwang);?></td>
    </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->user->mobile;?></th>
      <td><?php echo html::input('mobile', $user->mobile);?></td>
    </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->user->phone;?></th>
      <td><?php echo html::input('phone', $user->phone);?></td>
    </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->user->address;?></th>
      <td><?php echo html::input('address', $user->address);?></td>
    </tr>  
     <tr>
      <th class='rowhead'><?php echo $lang->user->zipcode;?></th>
      <td><?php echo html::input('zipcode', $user->zipcode);?></td>
    </tr>  
     <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
