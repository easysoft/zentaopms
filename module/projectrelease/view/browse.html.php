<?php
/**
 * The browse view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     release
 * @version     $Id: browse.html.php 4129 2020-11-25 11:58:14Z wwccss $
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
    foreach($lang->projectrelease->featureBar['browse'] as $featureType => $label)
    {
        $active = $type == $featureType ? 'btn-active-text' : '';
        $label  = "<span class='text'>$label</span>";
        if($type == $featureType) $label .= " <span class='label label-light label-badge'>" . $pager->recTotal . "</span>";
        echo html::a(inlink('browse', "projectID={$projectID}&executionID=$executionID&type=$featureType"), $label, '', "id='{$featureType}Tab' data-app='$from' class='btn btn-link $active'");
    }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('projectrelease', 'create', "projectID=$projectID", "<i class='icon icon-plus'></i> {$lang->release->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($releases)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->release->noRelease;?></span>
      <?php if(common::hasPriv('projectrelease', 'create')):?>
      <?php echo html::a($this->createLink('projectrelease', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->release->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table table-bordered table-condensed" id='releaseList'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->release->id;?></th>
        <th><?php echo $lang->release->name;?></th>
        <?php if($project->hasProduct):?>
        <th class='c-product'><?php echo $lang->projectrelease->product;?></th>
        <?php endif;?>
        <th class='c-build'><?php echo $lang->release->includedBuild;?></th>
        <?php if($showBranch):?>
        <th class='c-branch text-center'><?php echo $lang->release->branch;?></th>
        <?php endif;?>
        <th class='c-status text-center'><?php echo $lang->release->status;?></th>
        <th class='c-date text-center'><?php echo $lang->release->date;?></th>
        <?php
        $extendFields = $this->projectrelease->getFlowExtendFields();
        foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
        ?>
        <th class='c-actions-6 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($releases as $release):?>
      <?php
      $i = 0;
      $buildCount = count($release->buildInfos);
      $rowspan    = $buildCount > 1 ? "rowspan='$buildCount'" : '';
      if($buildCount == 0) $release->buildInfos = array('');
      foreach($release->buildInfos as $buildID => $build):
      ?>
      <tr data-type='<?php echo $release->status;?>'>
        <?php if($i == 0):?>
        <td class='c-id' <?php echo $rowspan?>><?php echo html::a(inlink('view', "releaseID=$release->id"), sprintf('%03d', $release->id));?></td>
        <td <?php echo $rowspan?>>
          <?php
          $flagIcon = $release->marker ? "<icon class='icon icon-flag red' title='{$lang->release->marker}'></icon> " : '';
          echo html::a(inlink('view', "release=$release->id"), $release->name, '', "data-app='$from'") . $flagIcon;
          ?>
        </td>
        <?php if($project->hasProduct):?>
        <td <?php echo $rowspan?> title='<?php echo $release->productName?>'><?php echo $release->productName?></td>
        <?php endif;?>
        <?php endif;?>
        <td class='c-build'>
          <?php
          if($buildCount)
          {
              if($build->branchName) echo "<span class='label label-outline label-badge'>{$build->branchName}</span> ";
              echo html::a($this->createLink($build->execution ? 'build' : 'projectbuild', 'view', "buildID=$buildID"), $build->name, '', "data-app='project' title='{$build->name}'");
          }
          ?>
        </td>
        <?php if($i == 0):?>
        <?php $status = $this->processStatus('release', $release);?>
        <?php if($showBranch):?>
        <td <?php echo $rowspan?> class='c-branch text-center'><?php echo $release->branchName; ?></td>
        <?php endif;?>
        <td <?php echo $rowspan?> class='c-status text-center' title='<?php echo $status;?>'>
          <span class="status-release status-<?php echo $release->status?>"><?php echo $status;?></span>
        </td>
        <td <?php echo $rowspan?> class='text-center'><?php echo $release->date;?></td>
        <?php foreach($extendFields as $extendField) echo "<td $rowspan>" . $this->loadModel('flow')->getFieldValue($extendField, $release) . "</td>";?>
        <td <?php echo $rowspan?> class='c-actions'><?php echo $this->projectrelease->buildOperateMenu($release, 'browse');?></td>
        <?php endif;?>
      </tr>
      <?php $i++; ?>
      <?php endforeach;?>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <div class="table-statistic"></div>
    <?php echo $pager->show('left', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
