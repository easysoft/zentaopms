<?php
/**
 * The build view file of project module of ZenTaoMS.
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
<?php include '../../common/tablesorter.html.php';?>
<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-right'><?php common::printLink('build', 'create', "project=$project->id", $lang->build->create);?></div>
  </div>
</div>
<div class='yui-d0'>
  <table class='table-1 fixed tablesorter'>
    <thead>
    <tr class='colhead'>
      <th><?php echo $lang->build->product;?></th>
      <th><?php echo $lang->build->name;?></th>
      <th class='w-p30'><?php echo $lang->build->scmPath;?></th>
      <th class='w-p30'><?php echo $lang->build->filePath;?></th>
      <th><?php echo $lang->build->date;?></th>
      <th><?php echo $lang->build->builder;?></th>
      <th><?php echo $lang->action;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($builds as $build):?>
    <tr class='a-center'>
      <td><?php echo $build->productName;?></td>
      <td><?php echo html::a($this->createLink('build', 'view', "build=$build->id"), $build->name);?></td>
      <td class='a-left nobr'><?php echo $build->scmPath?></td>
      <td class='a-left nobr'><?php echo $build->filePath?></td>
      <td><?php echo $build->date?></td>
      <td><?php echo $build->builder?></td>
      <td>
        <?php 
        common::printLink('build', 'edit',   "buildID=$build->id", $lang->edit);
        common::printLink('build', 'delete', "buildID=$build->id", $lang->delete, 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>  
<?php include '../../common/footer.html.php';?>
