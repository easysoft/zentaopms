<?php
/**
 * The edit view of product module of ZenTaoMS.
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
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='yui-d0' style='margin-top:20px'>
  <form method='post'>
    <table class='table-1'> 
      <caption><?php echo $lang->tree->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->tree->parent;?></th>
        <td><?php echo html::select('parent', $optionMenu, $module->parent, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->tree->name;?></th>
        <td><?php echo html::input('name', $module->name, "class='text-1'");?></td>
      </tr>  
      <tr>
        <td colspan='2' class='a-center'>
        <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>  
