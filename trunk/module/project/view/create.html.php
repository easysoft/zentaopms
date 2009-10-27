<?php
/**
 * The create view of project module of ZenTaoMS.
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
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-1 a-left'> 
      <caption><?php echo $lang->project->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->project->name;?></th>
        <td><input type='text' name='name' class='text-3' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->project->code;?></th>
        <td><input type='text' name='code' class='text-3' /></td>
      </tr>  
      <!--
      <tr>
        <th class='rowhead'><?php echo $lang->project->parent;?></th>
        <td><?php echo html::select('parent', $projects, '', 'class=select-3');?></td>
      </tr>  
      -->
      <tr>
        <th class='rowhead'><?php echo $lang->project->begin;?></th>
        <td><input type='text' name='begin' class='text-3' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->project->end;?></th>
        <td><input type='text' name='end' class='text-3' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->project->team;?></th>
        <td><input type='text' name='team' class='text-3' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->project->goal;?></th>
        <td><textarea name='goal' rows='5' class='area-1'></textarea></td>
      </tr>  

      <tr>
        <th class='rowhead'><?php echo $lang->project->desc;?></th>
        <td><textarea name='desc' rows='5' class='area-1'></textarea></td>
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
