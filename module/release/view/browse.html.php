<?php
/**
 * The browse view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
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
  <table class="table" id='releaseList'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->release->id;?></th>
        <th class="c-name"><?php echo $lang->release->name;?></th>
        <th class="c-build"><?php echo $lang->release->build;?></th>
        <?php if($product->type != 'normal'):?>
        <th class='text-center c-branch'><?php echo $lang->product->branch;?></th>
        <?php endif;?>
        <th class="c-project"><?php echo $lang->release->project;?></th>
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
      <?php foreach($releases as $release):?>
      <?php $canBeChanged = common::canBeChanged('release', $release);?>
      <tr data-type='<?php echo $release->status;?>'>
        <td><?php echo html::a(inlink('view', "releaseID=$release->id"), sprintf('%03d', $release->id));?></td>
        <td>
          <?php
          $flagIcon = $release->marker ? "<icon class='icon icon-flag red' title='{$lang->release->marker}'></icon> " : '';
          echo html::a(inlink('view', "release=$release->id"), $release->name, '', "title='$release->name'") . $flagIcon;
          ?>
        </td>
        <td title='<?php echo $release->buildName?>'><?php echo empty($release->execution) ? $release->buildName : html::a($this->createLink('build', 'view', "buildID=$release->buildID"), $release->buildName);?></td>
        <?php if($product->type != 'normal'):?>
        <td class='text-center' title='<?php echo zget($branches, $release->branch, '');?>'><?php echo $branches[$release->branch];?></td>
        <?php endif;?>
        <td title='<?php echo $release->projectName?>'><?php echo $release->projectName;?></td>
        <?php $status = $this->processStatus('release', $release);?>
        <td class='c-status text-center' title='<?php echo $status;?>'>
          <span class="status-release status-<?php echo $release->status?>"><?php echo $status;?></span>
        </td>
        <td class='text-center'><?php echo $release->date;?></td>
        <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $release) . "</td>";?>
        <td class='c-actions'>
          <?php echo $this->release->buildOperateMenu($release, 'browse');?>
        </td>
      </tr>
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
