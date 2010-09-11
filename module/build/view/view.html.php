<?php
/**
 * The view file of build module's view method of ZenTaoMS.
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
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <table class='table-1'> 
    <caption><?php echo $lang->build->view;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->build->product;?></th>
      <td><?php echo $build->productName;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->name;?></th>
      <td class='<?php if($build->deleted) echo 'deleted';?>'><?php echo $build->name;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->builder;?></th>
      <td><?php echo $users[$build->builder];?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->date;?></th>
      <td><?php echo $build->date;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->scmPath;?></th>
      <td><?php strpos($build->scmPath,  'http') === 0 ? printf(html::a($build->scmPath))  : printf($build->scmPath);?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->build->filePath;?></th>
      <td><?php strpos($build->filePath, 'http') === 0 ? printf(html::a($build->filePath)) : printf($build->filePath);?></td>
    </tr>  

    <tr>
      <th class='rowhead'><?php echo $lang->build->desc;?></th>
      <td><?php echo nl2br($build->desc);?></td>
    </tr>  
  </table>
  <div class='a-center f-16px strong'>
    <?php
    $browseLink = $this->session->buildList ? $this->session->buildList : $this->createLink('project', 'build', "projectID=$build->project");
    if(!$build->deleted)
    {
        common::printLink('build', 'edit',   "buildID=$build->id", $lang->edit);
        common::printLink('build', 'delete', "buildID=$build->id", $lang->delete, 'hiddenwin');
    }
    echo html::a($browseLink, $lang->goback);
    ?>
  </div>
  <?php include '../../common/view/action.html.php';?>
</div>  
<?php include '../../common/view/footer.html.php';?>
