<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('cloud');?></span>
      <strong><?php echo $lang->admin->register->caption;?></strong>
    </div>
  </div>
  <div class='alert'>
    <div class='pull-right'><?php echo html::a(inlink('bind'), $lang->admin->bind->caption, '', "class='btn btn-success'");?></div>
    <i class='icon-info-sign'></i>
    <div class='content'>
      <?php echo sprintf($lang->admin->register->bind, html::a(inlink('bind'), $lang->admin->register->click));?>
    </div>
  </div>
  <form class='form-condensed mw-600px' method="post" target="hiddenwin">
    <table align='center' class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->user->account;?></th>
    	<td>
          <div class="required required-wrapper"></div>
          <?php echo html::input('account', '', "class='form-control'");?>
          <div class='help-block'><?php echo $lang->admin->register->lblAccount;?></div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->user->realname;?></th>
        <td><div class="required required-wrapper"></div><?php echo html::input('realname', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->company;?></th>
        <td><?php echo html::input('company', $register->company, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->user->phone;?></th>
        <td><?php echo html::input('phone', '', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->email;?></th>
        <td><div class="required required-wrapper"></div><?php echo html::input('email', $register->email, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->password;?></th>
        <td>
          <div class="required required-wrapper"></div>
          <?php echo html::password('password1', '', "class='form-control'");?>
          <div class='help-block'><?php echo $lang->admin->register->lblPasswd;?></div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->password2;?></th>
        <td><?php echo html::password('password2', '', "class='form-control'") . '<span class="star">*</span>';?></td>
      </tr> 
      <tr>
        <th></th>
        <td colspan="2">
          <?php echo html::submitButton($lang->admin->register->submit) . html::hidden('sn', $sn);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
