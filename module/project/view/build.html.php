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
<?php js::set('executions', $executions)?>
<?php js::set('projectID', $projectID)?>
<?php js::set('createExecution', $lang->project->createExecution)?>
<?php js::set('confirmDelete', $lang->build->confirmDelete)?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolbar pull-left">
    <?php
    $label  = "<span class='text'>{$lang->execution->build}</span>";
    $active = '';
    if($type == 'all')
    {
        $active = 'btn-active-text';
        $label .= " <span class='label label-light label-badge'>{$buildsTotal}</span>";
    }
    echo html::a(inlink('build', "projectID=$projectID&type=all"), $label, '', "class='btn btn-link $active' id='all'")
    ?>
    <div class="input-control space w-150px"><?php echo html::select('product', $products, $product, "onchange='changeProduct(this.value)' class='form-control chosen' data-placeholder='{$lang->productCommon}'");?></div>
    <a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> <?php echo $lang->execution->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('project', $project)) common::printLink('build', 'create', "executionID=&productID=&projectID=$projectID", "<i class='icon icon-plus'></i> " . $lang->build->create, '', "class='btn btn-primary' id='createBuild'");?>
  </div>
</div>
<div id="mainContent">
  <div class="cell <?php if($type == 'bysearch') echo 'show';?>" id="queryBox" data-module='projectBuild'></div>
  <?php if(empty($projectBuilds)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->build->noBuild;?></span></p>
  </div>
  <?php else:?>
  <div class='main-table' data-ride="table" data-checkable="false">
    <table class="table text-center" id='buildList'>
      <thead>
        <tr>
          <th class="c-id-sm"><?php echo $lang->build->id;?></th>
          <th class="c-name w-200px text-left"><?php echo $lang->build->product;?></th>
          <th class="c-name text-left"><?php echo $lang->build->name;?></th>
          <th class="c-name text-left"><?php echo $lang->executionCommon;?></th>
          <th class="c-url"><?php echo $lang->build->scmPath;?></th>
          <th class="c-url"><?php echo $lang->build->filePath;?></th>
          <th class="c-date"><?php echo $lang->build->date;?></th>
          <th class="c-user"><?php echo $lang->build->builder;?></th>
          <th class="c-actions-5"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($projectBuilds as $productID => $builds):?>
        <?php foreach($builds as $index => $build):?>
        <tr data-id="<?php echo $productID;?>">
          <td class="c-id-sm text-muted"><?php echo html::a(helper::createLink('build', 'view', "buildID=$build->id"), sprintf('%03d', $build->id), '', "data-app='project'");?></td>
          <td class="c-name text-left" title='<?php echo $build->productName;?>'><?php echo $build->productName;?></td>
          <td class="c-name">
            <?php if($build->branchName) echo "<span class='label label-outline label-badge'>{$build->branchName}</span>"?>
            <?php echo html::a($this->createLink('build', 'view', "build=$build->id"), $build->name, '', "data-app='project'");?>
          </td>
          <td class="c-name text-left" title='<?php echo $build->executionName;?>'><?php echo $build->executionName;?></td>
          <td class="c-url" title="<?php echo $build->scmPath?>"><?php  echo strpos($build->scmPath,  'http') === 0 ? html::a($build->scmPath)  : $build->scmPath;?></td>
          <td class="c-url" title="<?php echo $build->filePath?>"><?php echo strpos($build->filePath, 'http') === 0 ? html::a($build->filePath) : $build->filePath;?></td>
          <td class="c-date"><?php echo $build->date?></td>
          <td class="c-user em"><?php echo zget($users, $build->builder);?></td>
          <td class="c-actions">
            <?php
            if(common::hasPriv('build', 'linkstory') and common::hasPriv('build', 'view') and common::canBeChanged('build', $build))
            {
                echo html::a($this->createLink('build', 'view', "buildID=$build->id&type=story&link=true"), "<i class='icon icon-link'></i>", '', "class='btn' title='{$lang->build->linkStory}' data-app='project'");
            }
            common::printIcon('testtask', 'create', "product=$build->product&executionID={$build->execution}&build=$build->id&projectID=$projectID", $build, 'list', 'bullhorn', '', '', '', "data-app='project'");
            $lang->build->view = $lang->project->bug;
            common::printIcon('build', 'view', "buildID=$build->id&type=generatedBug", $build, 'list', 'bug', '', '', '', "data-app='project'");
            common::printIcon('build',   'edit', "buildID=$build->id", $build, 'list');
            if(common::hasPriv('build',  'delete', $build))
            {
                $deleteURL = $this->createLink('build', 'delete', "buildID=$build->id&confirm=yes");
                echo html::a("###", '<i class="icon-trash"></i>', '', "onclick='ajaxDelete(\"$deleteURL\", \"buildList\", confirmDelete)' class='btn' title='{$lang->build->delete}'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
