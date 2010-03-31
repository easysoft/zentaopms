<?php
/**
 * The edit view of user module of ZenTaoMS.
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
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-5'> 
      <caption><?php echo $lang->user->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->user->dept;?></th>
        <td><?php echo html::select('dept', $depts, $user->dept, "class='select-3'");?>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->account;?></th>
        <td><?php echo html::input('account', $user->account, "class='text-3'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->realname;?></th>
        <td><?php echo html::input('realname', $user->realname, "class='text-3'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->email;?></th>
        <td><?php echo html::input('email', $user->email, "class='text-3'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->join;?></th>
        <td><?php echo html::input('join', '', "class='text-3 date'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->gendar;?></th>
        <td><?php echo html::radio('gendar', (array)$lang->user->gendarList, $user->gendar);?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->password;?></th>
        <td><?php echo html::password('password1', '', "class='text-3'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->password2;?></th>
        <td><?php echo html::password('password2', '', "class='text-3'");?></td>
      </tr>  
      <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
