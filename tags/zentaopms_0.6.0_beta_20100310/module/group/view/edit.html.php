<?php
/**
 * The edit view of group module of ZenTaoMS.
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
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-4'> 
      <caption><?php echo $lang->group->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->group->name;?></th>
        <td><input type='text' name='name' value="<?php echo $group->name;?>"  class='text-1' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->group->desc;?></th>
        <td><textarea name='desc' rows='5' class='area-1'><?php echo $group->desc;?></textarea></td>
      </tr>  
      <tr><td colspan='2' class='a-center'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
