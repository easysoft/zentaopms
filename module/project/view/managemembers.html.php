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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <form method='post'>
    <table align='center' class='table-4 a-center'> 
      <caption><?php echo $lang->project->manageMembers;?></caption>
      <tr>
        <th><?php echo $lang->team->account;?></th>
        <th><?php echo $lang->team->role;?></th>
        <th><?php echo $lang->team->workingHour;?></th>
      </tr>
      <?php foreach($members as $key => $member):?>
      <?php unset($users[$member->account]);?>
      <tr>
        <td><input type='text' name='accounts[]' id='account<?php echo $key;?>' value='<?php echo $member->account;?>' readonly class='text-2' /></td>
        <td><input type='text' name='roles[]'    id='role<?php echo $key;?>'    value='<?php echo $member->role;?>' class='text-2' /></td>
        <td>
          <input type='text'   name='workingHours[]' id='workingHour<?php echo $key;?>' value='<?php echo $member->workingHour;?>' class='text-2' />
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
        <td><input type='text' name='roles[]' id='role<?php echo ($key + $i);?>' class='text-2' /></td>
        <td>
          <input type='text'   name='workingHours[]' id='workingHour<?php echo ($key + $i);?>' class='text-2' />
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
<?php include '../../common/view/footer.html.php';?>
