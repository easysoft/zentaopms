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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div class='yui-d0'>
  <table class='table-1 tablesorter fixed'>
    <caption class='caption-tl'>
      <div class='f-left'><?php echo $lang->project->build;?></div>
      <div class='f-right'><?php common::printLink('build', 'create', "project=$project->id", $lang->build->create);?></div>
    </caption>
    <thead>
    <tr class='colhead'>
      <th class='w-120px'><?php echo $lang->build->product;?></th>
      <th><?php echo $lang->build->name;?></th>
      <th><?php echo $lang->build->scmPath;?></th>
      <th><?php echo $lang->build->filePath;?></th>
      <th class='w-date'><?php echo $lang->build->date;?></th>
      <th class='w-user'><?php echo $lang->build->builder;?></th>
      <th class='w-60px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($builds as $build):?>
    <tr class='a-center'>
      <td><?php echo $build->productName;?></td>
      <td class='a-left'><?php echo html::a($this->createLink('build', 'view', "build=$build->id"), $build->name);?></td>
      <td class='a-left nobr'><?php strpos($build->scmPath,  'http') === 0 ? printf(html::a($build->scmPath))  : printf($build->scmPath);?></td>
      <td class='a-left nobr'><?php strpos($build->filePath, 'http') === 0 ? printf(html::a($build->filePath)) : printf($build->filePath);?></td>
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
<?php include '../../common/view/footer.html.php';?>
