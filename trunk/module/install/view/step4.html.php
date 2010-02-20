<?php
/**
 * The html template file of step4 method of install module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author	  Chunsheng Wang <wwccss@263.net>
 * @package	 ZenTaoMS
 * @version	 $Id$
 */
?>
<?php include './header.html.php';?>
<div class='yui-d0'>
  <?php if(isset($error)):?>
  <table class='table-6' align='center'>
	<caption><?php echo $lang->install->error;?></caption>
    <tr><td><?php echo $error;?></td></tr>
    <tr><td><?php echo html::commonButton($lang->install->pre, "onclick='javascript:history.back(-1)'");?></td></tr>
  </table>
  <?php elseif(isset($success)):?>
  <table class='table-6' align='center'>
	<caption><?php echo $lang->install->success;?></caption>
    <tr><td><?php echo $lang->install->afterSuccess;?></td></tr>
    <tr><td><?php echo html::commonButton($lang->install->pre, "onclick='javascript:history.back(-1)'");?></td></tr>
  </table>
  <?php else:?>
  <form method='post' target='hiddenwin'>
  <table class='table-6' align='center'>
	<caption><?php echo $lang->install->getPriv;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->install->company;?></th>
      <td><?php echo html::input('company');?></td>
	</tr>
    <tr>
      <th class='rowhead'><?php echo $lang->install->pms;?></th>
      <td><?php echo html::input('pms', $pmsDomain) . "<span class='f-12px'>{$lang->install->pmsNote}</span>";?></td>
	</tr>
    <tr>
      <th class='rowhead'><?php echo $lang->install->account;?></th>
      <td><?php echo html::input('account');?></td>
	</tr>
    <tr>
      <th class='rowhead'><?php echo $lang->install->password;?></th>
      <td><?php echo html::input('password');?></td>
	</tr>
    <tr class='a-center'>
      <td colspan='2'><?php echo html::submitButton();?></td>
	</tr>
  </table>
  </form>
  <?php endif;?>
</div>
<?php include './footer.html.php';?>
