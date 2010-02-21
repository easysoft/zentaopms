<?php
/**
 * The create view of build module of ZenTaoMS.
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
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->build->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->build->product;?></th>
        <td><?php echo html::select('product', $products);?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->name;?></th>
        <td><input type='text' name='name' class='text-3' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->builder;?></th>
        <td><?php echo html::select('builder', $users, $app->user->account, 'class="select-3"');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->date;?></th>
        <td><input type='text' name='date' class='text-3' value='<?php echo date('Y-m-d');?>' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->scmPath;?></th>
        <td><input type='text' name='scmPath' class='text-1' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->filePath;?></th>
        <td><input type='text' name='filePath' class='text-1' /></td>
      </tr>  
 
      <tr>
        <th class='rowhead'><?php echo $lang->build->desc;?></th>
        <td><textarea name='desc' rows='5' class='area-1'></textarea></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
