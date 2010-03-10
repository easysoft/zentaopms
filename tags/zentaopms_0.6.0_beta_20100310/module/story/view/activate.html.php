<?php
/**
 * The activate view file of story module of ZenTaoMS.
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
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include './header.html.php';?>
<div class='yui-d0'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $lang->story->activate;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->story->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $users, $story->closedBy, 'class="select-3"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->story->comment;?></th>
      <td><?php echo html::textarea('comment', '', 'rows=5 class="area-1"');?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton() . html::linkButton($lang->goback, inlink('view', "storyID=$story->id"));?>
      </td>
    </tr>
  </table>
  <?php include '../../common/action.html.php';?>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
