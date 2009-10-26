<?php
/**
 * The create view of user module of ZenTaoMS.
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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-4 a-left'> 
      <caption><?php echo $lang->user->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->user->dept;?></th>
        <td><?php echo html::select('dept', $depts);?>
      </tr>  

      <tr>
        <th class='rowhead'><?php echo $lang->user->account;?></th>
        <td><input type='text' name='account' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->realname;?></th>
        <td><input type='text' name='realname' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->email;?></th>
        <td><input type='text' name='email' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->join;?></th>
        <td><input type='text' name='join' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->gendar;?></th>
        <td><?php echo html::radio('gendar', (array)$lang->user->gendarList, 'm');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->user->password;?></th>
        <td><input type='password' name='password' /></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'>
          <?php echo html::submitButton() . html::resetButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
