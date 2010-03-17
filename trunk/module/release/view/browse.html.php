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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <div id='feature-bar'>
  </div>
  <table class='table-1 fixed tablesorter'>
    <caption>
      <div class='f-left'><?php echo $lang->release->browse;?></div>
      <div class='f-right'><?php common::printLink('release', 'create', "product=$product->id", $lang->release->create);?></div>
    </caption>
    <thead>
    <tr>
      <th><?php echo $lang->release->name;?></th>
      <th><?php echo $lang->release->build;?></th>
      <th><?php echo $lang->release->date;?></th>
      <th><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($releases as $release):?>
    <tr class='a-center'>
      <td><?php echo html::a(inlink('view', "release=$release->id"), $release->name);?></td>
      <td><?php echo $release->buildName;?></td>
      <td><?php echo $release->date;?></td>
      <td>
        <?php
        common::printLink('release', 'edit',   "release=$release->id", $lang->edit);
        common::printLink('release', 'delete', "release=$release->id", $lang->delete, 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>  
<?php include '../../common/view/footer.html.php';?>
