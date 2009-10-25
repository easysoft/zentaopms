<?php
/**
 * The browse view file of release module of ZenTaoMS.
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
  <table align='center' class='table-1'>
    <caption><?php echo $lang->page->browse;?></caption>
    <tr>
      <th><?php echo $lang->release->id;?></th>
      <th><?php echo $lang->release->name;?></th>
      <th><?php echo $lang->release->desc;?></th>
      <th>
        <?php echo $lang->release->desc;?>
      </th>
    </tr>
    <?php foreach($releases as $release):?>
    <tr>
      <td><?php echo $release->id;?></td>
      <td><?php echo $release->name;?></td>
      <td><?php echo $release->desc;?></td>
      <td>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
  <?php 
  $vars['product'] = $product;
  $addLink = $this->createLink($this->moduleName, 'create', $vars);
  echo "<a href='$addLink'>{$lang->page->create}</a>";
  ?>
</div>  
<?php include '../../common/footer.html.php';?>
