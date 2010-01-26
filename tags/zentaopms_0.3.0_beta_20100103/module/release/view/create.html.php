<?php
/**
 * The create view of release module of ZenTaoMS.
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
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div id='doc3'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-4'> 
      <caption><?php echo $lang->release->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->release->product;?></th>
        <td class='a-left'><input type='text' name='product' value='<?php echo $product;?>' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->release->name;?></th>
        <td class='a-left'><input type='text' name='name' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->release->planDate;?></th>
        <td class='a-left'><input type='text' name='planDate' /></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->release->desc;?></th>
        <td class='a-left'><textarea name='desc' style='width:100%' rows='5'></textarea></td>
      </tr>  
      <tr>
        <td colspan='2'>
          <input type='submit' />
        </td>
      </tr>
    </table>
  </form>
</div>  
<?php include '../../common/footer.html.php';?>
