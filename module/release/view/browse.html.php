<?php
/**
 * The browse view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->release->confirmDelete)?>
<?php js::set('pageAllSummary', $lang->release->pageAllSummary)?>
<?php js::set('pageSummary', $lang->release->pageSummary)?>
<?php js::set('type', $type)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    common::sortFeatureMenu();
    foreach($lang->release->featureBar['browse'] as $featureType => $label)
    {
        $active = $type == $featureType ? 'btn-active-text' : '';
        $label  = "<span class='text'>$label</span>";
        if($type == $featureType) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        echo html::a(inlink('browse', "productID={$product->id}&branch=$branch&type=$featureType"), $label, '', "id='{$featureType}Tab' class='btn btn-link $active'");
    }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('product', $product)):?>
    <?php common::printLink('release', 'create', "productID=$product->id&branch=$branch", "<i class='icon icon-plus'></i> {$lang->release->create}", '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($releases)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->release->noRelease;?></span>
      <?php if(common::canModify('product', $product) and common::hasPriv('release', 'create')):?>
      <?php echo html::a($this->createLink('release', 'create', "productID=$product->id&branch=$branch"), "<i class='icon icon-plus'></i> " . $lang->release->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table table-bordered" id='releaseList'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->release->id;?></th>
        <th class="c-name"><?php echo $lang->release->name;?></th>
        <th class='c-build'><?php echo $lang->release->includedBuild;?></th>
        <?php if($showBranch):?>
        <th class="c-name w-200px"><?php echo $lang->release->branch;?></th>
        <?php endif;?>
        <th class='c-project'><?php echo $lang->release->relatedProject;?></th>
        <th class='text-center c-status'><?php echo $lang->release->status;?></th>
        <th class='c-date text-center'><?php echo $lang->release->date;?></th>
        <?php
        $extendFields = $this->release->getFlowExtendFields();
        foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
        ?>
        <th class='c-actions-6 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $this->loadModel('project');
      $this->loadModel('execution');
      ?>
      <?php foreach($releases as $release):?>
      <?php $canBeChanged = common::canBeChanged('release', $release);?>
      <?php $buildCount   = count($release->builds);?>
      <?php $rowspan      = $buildCount > 1 ? "rowspan='{$buildCount}'" : '';?>
      <?php $i = 1;?>
      <?php if($buildCount == 0) $release->builds = array('');?>
      <?php foreach($release->builds as $build):?>
      <tr data-type='<?php echo $release->status;?>'>
        <?php if($i == 1):?>
        <td class='c-id' <?php echo $rowspan?>><?php echo html::a(inlink('view', "releaseID=$release->id"), sprintf('%03d', $release->id));?></td>
        <td <?php echo $rowspan?>>
          <?php
          $flagIcon = $release->marker ? "<icon class='icon icon-flag red' title='{$lang->release->marker}'></icon> " : '';
          echo html::a(inlink('view', "release=$release->id"), $release->name, '', "title='$release->name'") . $flagIcon;
          ?>
        </td>
        <?php endif;?>
        <td title='<?php echo $build ? $build->name : ''?>'>
          <?php if($buildCount):?>
          <?php if($product->type != 'normal'):?>
          <span class='label label-outline label-badge'><?php echo $build->branchName;?></span>
          <?php endif;?>
          <?php
          $moduleName   = $build->execution ? 'build' : 'projectbuild';
          $canClickable = false;
          if($moduleName == 'projectbuild' and $this->project->checkPriv($build->project)) $canClickable = true;
          if($moduleName == 'build' and $this->execution->checkPriv($build->execution))    $canClickable = true;
          echo $canClickable ? html::a($this->createLink($moduleName, 'view', "buildID=$build->id"), $build->name, '', "data-app='project'") : $build->name;
          ?>
        <?php endif;?>
        </td>
        <?php if($i == 1):?>
        <?php if($showBranch):?>
        <td <?php echo $rowspan?> class='c-branch text-center'><?php echo $release->branchName; ?></td>
        <?php endif;?>
        <?php endif;?>
        <td><?php if($buildCount) echo $build->projectName;?></td>
        <?php if($i == 1):?>
        <?php $status = $this->processStatus('release', $release);?>
        <td class='c-status text-center' title='<?php echo $status;?>' <?php echo $rowspan?>>
          <span class="status-release status-<?php echo $release->status?>"><?php echo $status;?></span>
        </td>
        <td class='text-center' <?php echo $rowspan?>><?php echo $release->date;?></td>
        <?php foreach($extendFields as $extendField) echo "<td $rowspan>" . $this->loadModel('flow')->getFieldValue($extendField, $release) . "</td>";?>
        <td class='c-actions' <?php echo $rowspan?>><?php echo $this->release->buildOperateMenu($release, 'browse');?></td>
        <?php endif;?>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <div class="table-statistic"></div>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
