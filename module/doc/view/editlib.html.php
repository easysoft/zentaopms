<?php
/**
 * The editlib file of doc module of ZenTaoMS.
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
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: editlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>body{background:white; margin-top:20px; padding-bottom:0}</style>
<div id='yui-d0'>
  <?php if($libID == 'product' or $libID == 'project'):?>
    <h3><?php echo $lang->doc->errorEditSystemDoc;?></h3>
  <?php else:?>
  <form method='post'>
    <table class='table-1'> 
      <caption><?php echo $lang->doc->editLib;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->doc->libName;?></th>
        <td><?php echo html::input('name', $libName, "class='text-1'");?></td>
      </tr>  
      <tr><td colspan='2' class='a-center'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
  <?php endif;?>
</div>  
