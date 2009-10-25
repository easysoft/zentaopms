<?php
/**
 * The link user view of project module of ZenTaoMS.
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
 * @package     project
 * @version     $Id: managemembers.html.php 1353 2009-09-24 09:28:35Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div id='doc3'>
  <form method='post'>
    <table align='center' class='table-3'> 
      <caption><?php echo $lang->project->manageMembers;?></caption>
      <tr>
        <th><?php echo $lang->team->account;?></th>
        <th><?php echo $lang->team->role;?></th>
        <th><?php echo $lang->team->workingHour;?></th>
      </tr>
      <?php foreach($members as $member):?>
      <?php unset($users[$member->account]);?>
      <tr>
        <td><input type='text' name='accounts[]' value='<?php echo $member->account;?>' readonly class='text-2' /></td>
        <td><input type='text' name='roles[]'    value='<?php echo $member->role;?>' class='text-2' /></td>
        <td>
          <input type='text'   name='workingHours[]' value='<?php echo $member->workingHour;?>' class='text-2' />
          <input type='hidden' name='modes[]'        value='update' />
        </td>
      </tr>
      <?php endforeach;?>
      <?php
      $count = count($users) - 1;
      if($count > PROJECTMODEL::LINK_MEMBERS_ONE_TIME) $count = PROJECTMODEL::LINK_MEMBERS_ONE_TIME;
      ?>

      <?php for($i = 0; $i < $count; $i ++):?>
      <tr>
        <td><?php echo html::select('accounts[]', $users, '', 'class=select-2');?></td>
        <td><input type='text' name='roles[]' class='text-2' /></td>
        <td>
          <input type='text'   name='workingHours[]' class='text-2' />
          <input type='hidden' name='modes[]' value='create' />
        </td>
      </tr>
      <?php endfor;?>
      <tr>
        <td colspan='3'>
          <input type='submit' name='submit' value='<?php echo $lang->save;?>' class='button-s' />
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
