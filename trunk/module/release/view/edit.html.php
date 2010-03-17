<?php
/**
 * The edit view of release module of ZenTaoMS.
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
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->release->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->release->name;?></th>
        <td><input type='text' name='name' class='text-3' value='<?php echo $release->name;?>' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->release->build;?></th>
        <td>
          <?php
          echo html::select('build', $builds, $release->build, 'class="select-3"');
          ?>
        </td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->release->date;?></th>
        <td><input type='text' name='date' class='text-3 date' value='<?php echo $release->date;?>' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->release->desc;?></th>
        <td><textarea name='desc' rows='5' class='area-1'><?php echo $release->desc;?></textarea></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton() . html::hidden('product', $release->product);?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
