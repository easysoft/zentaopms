<?php
/**
 * The edit view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'><div class='u-1'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-4 a-left'> 
      <caption><?php echo $lang->my->editProfile;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->user->account;?></th>
        <td><?php echo html::input('account', $user->account, "readonly");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->realname;?></th>
        <td><?php echo html::input('realname', $user->realname);?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->email;?></th>
        <td><?php echo html::input('email', $user->email);?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->gendar;?></th>
        <td><?php echo html::radio('gendar', $lang->user->gendarList, $user->gendar);?></td>
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
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
