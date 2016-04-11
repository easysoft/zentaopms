<?php
/**
 * The build view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: build.html.php 4262 2013-01-24 08:48:56Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->build->confirmDelete)?>
<div id='titlebar'>
  <div class='heading'>
    <?php echo html::icon($lang->icons['build']);?> <?php echo $lang->project->build;?>
  </div>
  <div class='actions'>
    <?php common::printIcon('build', 'create', "project=$project->id");?>
  </div>
</div>

<table class='table tablesorter table-fixed' id='buildList'>
  <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->build->id;?></th>
      <th class='w-120px'><?php echo $lang->build->product;?></th>
      <th><?php echo $lang->build->name;?></th>
      <th><?php echo $lang->build->scmPath;?></th>
      <th><?php echo $lang->build->filePath;?></th>
      <th class='w-date'><?php echo $lang->build->date;?></th>
      <th class='w-user'><?php echo $lang->build->builder;?></th>
      <th class='w-120px'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($builds as $build):?>
  <tr class='text-center'>
    <td><?php echo $build->id;?></td>
    <td title='<?php echo $build->productName?>'><?php echo $build->productName;?></td>
    <td class='text-left'>
      <?php if($build->branchName) echo "<span class='label label-info label-badge'>{$build->branchName}</span>"?>
      <?php echo html::a($this->createLink('build', 'view', "build=$build->id"), $build->name);?>
    </td>
    <td class='text-left' title="<?php echo $build->scmPath?>"><?php strpos($build->scmPath,  'http') === 0 ? printf(html::a($build->scmPath))  : printf($build->scmPath);?></td>
    <td class='text-left' title="<?php echo $build->filePath?>"><?php strpos($build->filePath, 'http') === 0 ? printf(html::a($build->filePath)) : printf($build->filePath);?></td>
    <td><?php echo $build->date?></td>
    <td><?php echo $users[$build->builder]?></td>
    <td class='text-right'>
      <?php 
      if(common::hasPriv('build', 'linkstory') and common::hasPriv('build', 'view'))
      {
          echo html::a($this->createLink('build', 'view', "buildID=$build->id&type=story&link=true"), "<i class='icon icon-link'></i>", '', "class='btn-icon' title='{$lang->build->linkStory}'");
      }
      common::printIcon('testtask', 'create', "product=$build->product&project=$project->id&build=$build->id", '', 'list', 'bullhorn');
      $lang->project->bug = $lang->project->viewBug;
      common::printIcon('project', 'bug',  "project=$project->id&orderBy=status&build=$build->id", '', 'list');
      common::printIcon('build', 'edit',   "buildID=$build->id", '', 'list');
      if(common::hasPriv('build', 'delete'))
      {
          $deleteURL = $this->createLink('build', 'delete', "buildID=$build->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"buildList\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->build->delete}'");
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
