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
<?php js::set('confirmDelete', $lang->build->confirmDelete)?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolbar pull-left">
    <div class="pull-left table-group-btns">
      <button type="button" class="btn btn-link group-collapse-all"><?php echo $lang->project->treeLevel['root'];?> <i class="icon-fold-all"> </i></button>
      <button type="button" class="btn btn-link group-expand-all"><?php echo $lang->project->treeLevel['all'];?> <i class="icon-unfold-all"> </i></button>
    </div>
    <span class='btn btn-link btn-active-text'>
      <span class='text'><?php echo $lang->project->build;?></span>
      <span class='label label-light label-badge'><?php echo $buildsTotal;?></span>
    </span>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('build', 'create', "project=$project->id", "<i class='icon icon-plus'> </i>" . $lang->build->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent">
  <div class='main-table' data-ride="table" data-checkable="false" data-group="true">
    <table class="table table-grouped text-center" id='buildList'>
      <thead>
        <tr class="divider">
          <th class="c-side"><?php echo $lang->build->product;?></th>
          <th class="c-id-sm"><?php echo $lang->build->id;?></th>
          <th class="c-name"><?php echo $lang->build->name;?></th>
          <th class="c-url"><?php echo $lang->build->scmPath;?></th>
          <th class="c-url"><?php echo $lang->build->filePath;?></th>
          <th class="c-date"><?php echo $lang->build->date;?></th>
          <th class="c-user"><?php echo $lang->build->builder;?></th>
          <th class="c-actions-3"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($projectBuilds as $projectID => $builds):?>
        <?php $total = count($builds);?>
        <?php foreach($builds as $index => $build):?>
        <?php $trClass = $index == 0 ? 'divider-top' : '';?>
        <tr data-id="<?php echo $projectID;?>" class="<?php echo $trClass;?>">
          <?php if($index == 0):?>
          <td rowspan="<?php echo $total;?>" class="c-side text-left group-toggle">
            <a title="<?php echo $build->productName;?>" class="text-primary"><i class="icon-caret-down"></i> <?php echo $build->productName;?></a>
            <div class="small"><span class="text-muted"><?php echo $lang->project->build;?></span> <?php echo $total;?></div>
          </td>
          <?php endif;?>
          <td class="c-id-sm text-muted"><?php echo $build->id;?></td>
          <td class="c-name">
            <?php if($build->branchName) echo "<span class='label label-info label-badge'>{$build->branchName}</span>"?>
            <?php echo html::a($this->createLink('build', 'view', "build=$build->id"), $build->name);?>
          </td>
          <td class="c-url" title="<?php echo $build->scmPath?>"><?php  echo strpos($build->scmPath,  'http') === 0 ? html::a($build->scmPath)  : $build->scmPath;?></td>
          <td class="c-url" title="<?php echo $build->filePath?>"><?php echo strpos($build->filePath, 'http') === 0 ? html::a($build->filePath) : $build->filePath;?></td>
          <td class="c-date"><?php echo $build->date?></td>
          <td class="c-user em"><?php echo $users[$build->builder]?></td>
          <td class="c-actions">
            <div class='more'>
            <?php
            if(common::hasPriv('build',  'delete', $build))
            {
                $deleteURL = $this->createLink('build', 'delete', "buildID=$build->id&confirm=yes");
                echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"buildList\",confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn' title='{$lang->build->delete}'");
            }
            ?>
            </div>
            <?php
            if(common::hasPriv('build', 'linkstory') and common::hasPriv('build', 'view'))
            {
                echo html::a($this->createLink('build', 'view', "buildID=$build->id&type=story&link=true"), "<i class='icon icon-link'></i>", '', "class='btn' title='{$lang->build->linkStory}'");
            }
            common::printIcon('testtask', 'create', "product=$build->product&project=$project->id&build=$build->id", $build, 'list', 'bullhorn');
            $lang->project->bug = $lang->project->viewBug;
            common::printIcon('project', 'bug',  "project=$project->id&orderBy=status&build=$build->id", $build, 'list');
            common::printIcon('build',   'edit', "buildID=$build->id", $build, 'list');
            ?>
          </td>
        </tr>
        <?php if(($index + 1) == $total):?>
        <tr class="group-toggle group-summary divider hidden" data-id="<?php echo $projectID;?>">
          <td class="c-side text-left">
            <a title="<?php echo $build->productName;?>"><i class="icon-caret-right text-muted"></i> <?php echo $build->productName;?></a>
          </td>
          <td colspan="7" class="text-left">
            <div class="small with-padding"><span class="text-muted"><?php echo $lang->project->build;?></span> <?php echo $total;?></div>
          </td>
        </tr>
        <?php endif;?>
        <?php endforeach;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
