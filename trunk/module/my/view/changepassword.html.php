<?php
/**
 * The change password  view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-4 a-left'> 
    <caption><?php echo $lang->my->changePassword;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->user->account;?></th>
      <td><?php echo $user->account . html::hidden('account',$user->account);?></td>
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
<?php include '../../common/view/footer.html.php';?>
