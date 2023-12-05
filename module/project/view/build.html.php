<?php
/**
 * The build view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: build.html.php 4262 2013-01-24 08:48:56Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('projectID', $projectID)?>
<?php js::set('noDevStage', $lang->project->noDevStage)?>
<?php js::set('createExecution', $lang->project->createExecution)?>
<?php js::set('confirmDelete', $lang->build->confirmDelete)?>
<?php js::set('fromModule', $fromModule)?>
<?php js::set('fromMethod', $fromMethod)?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolbar pull-left">
    <?php
    common::sortFeatureMenu();
    foreach($lang->project->featureBar['build'] as $featureType => $label)
    {
        $activeClass = $type == $featureType ? 'btn-active-text' : '';
        $label       = "<span class='text'>$label</span>";
        if($type == $featureType) $label .= " <span class='label label-light label-badge'>{$buildsTotal}</span>";
        echo html::a($this->createLink($fromModule, $fromMethod, "projectID=$projectID&type=$featureType"), $label, '',"class='btn btn-link $activeClass' data-app={$app->tab} id=" . $featureType .'Tab');
    }
    ?>
    <?php if($project->hasProduct):?>
    <div class="input-control space w-150px"><?php echo html::select('product', $products, $product, "onchange='changeProduct(this.value)' class='form-control chosen' data-placeholder='{$lang->productCommon}'");?></div>
    <?php endif;?>
    <a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> <?php echo $lang->execution->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('project', $project)) common::printLink('projectbuild', 'create', "projectID=$projectID", "<i class='icon icon-plus'></i> " . $lang->build->create, '', "class='btn btn-primary' id='createBuild'");?>
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
          <th class="c-name text-left"><?php echo $lang->build->name;?></th>
          <?php if($project->hasProduct):?>
          <th class="c-name w-200px text-left"><?php echo $lang->build->product;?></th>
          <?php if($showBranch):?>
          <th class="c-name w-150px text-left"><?php echo $lang->build->branch;?></th>
          <?php endif;?>
          <?php endif;?>
          <?php if($project->multiple):?>
          <th class="c-name w-150px text-left"><?php echo $lang->executionCommon;?></th>
          <?php endif;?>
          <th class="c-url w-200px text-left"><?php echo $lang->build->url;?></th>
          <th class="c-date w-90px"><?php echo $lang->build->date;?></th>
          <th class="c-user w-70px"><?php echo $lang->build->builder;?></th>
          <th class="c-actions-5"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($projectBuilds as $productID => $builds):?>
        <?php foreach($builds as $index => $build):?>
        <tr data-id="<?php echo $productID;?>">
          <td class="c-id-sm text-muted"><?php echo html::a(helper::createLink($fromModule, 'view', "buildID=$build->id"), sprintf('%03d', $build->id), '', "data-app='project'");?></td>
          <td class="c-name" title='<?php echo $build->name;?>'>
            <span class='build'>
              <?php echo html::a($this->createLink($fromModule, 'view', "buildID=$build->id"), $build->name, '', "data-app='project' class='buildName'");?>
              <?php if(!$build->execution):?>
                <span class='icon icon-code-fork text-muted' title='<?php echo $lang->build->integrated;?>'></span>
              <?php endif;?>
            </span>
          </td>
          <?php if($project->hasProduct):?>
          <td class="c-name text-left" title='<?php echo $build->productName;?>'><?php echo $build->productName;?></td>
          <?php if($showBranch):?>
          <td class="c-name text-left" title='<?php echo $build->branchName;?>'><?php echo $build->branchName;?></td>
          <?php endif;?>
          <?php endif;?>
          <?php if($project->multiple):?>
          <?php if($build->execution):?>
          <td class="c-name text-left" title='<?php echo $build->executionName;?>'>
            <span class='execution'>
            <?php $executionName = $build->executionName;?>
            <?php if($build->executionDeleted) $executionName = "<del class='executionName'>$executionName</del>";?>
            <?php echo $executionName;?>
            <?php if($build->executionDeleted):?>
            <span class='label label-danger'><?php echo $lang->build->deleted;?></span>
            <?php endif; ?>
            </span>
          </td>
          <?php else:?>
          <td class="c-name text-left">
            <?php $childExecutions = array();?>
            <?php foreach($build->builds as $childBuild):?>
            <?php $childExecutions[$childBuild->execution] = $childBuild->execution;?>
            <?php endforeach;?>

            <?php foreach($childExecutions as $execution):?>
            <?php $executionName = zget($executions, $execution, '');?>
            <?php if($executionName):?>
            <span title="<?php echo $executionName;?>"><?php echo $executionName;?></span></br>
            <?php endif;?>
            <?php endforeach;?>
          </td>
          <?php endif;?>
          <?php endif;?>
          <td class="c-url text-left">
            <?php
            if($build->scmPath)
            {
                $colorStyle = strpos($build->scmPath, 'http') === 0 ? "style='color:#2463c7;'" : '';
                echo "<div><i class='icon icon-file-code' $colorStyle title='{$lang->build->scmPath}'></i> ";
                echo "<span title='{$build->scmPath}'>";
                echo $colorStyle ? html::a($build->scmPath, $build->scmPath, '_blank', $colorStyle) : $build->scmPath;
                echo '</span></div>';
            }
            if($build->filePath)
            {
                $colorStyle = strpos($build->filePath, 'http') === 0 ? "style='color:#2463c7;'" : '';
                echo "<div><i class='icon icon-download' $colorStyle title='{$lang->build->filePath}'></i> ";
                echo "<span title='{$build->filePath}'>";
                echo $colorStyle ? html::a($build->filePath, $build->filePath, '_blank', $colorStyle) : $build->filePath;
                echo '</span></div>';
            }
            ?>
          </td>
          <td class="c-date"><?php echo $build->date?></td>
          <td class="c-user em"><?php echo zget($users, $build->builder);?></td>
          <td class="c-actions"><?php echo $this->build->buildOperateMenu($build, 'browse');?></td>
        </tr>
        <?php endforeach;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
