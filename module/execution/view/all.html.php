<?php
/**
 * The html template file of all method of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     execution
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php include '../../common/view/datatable.fix.html.php';?>
<?php
$datatableId  = $this->moduleName . ucfirst($this->methodName);
$useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
?>
<?php
js::set('unfoldExecutions', array());
js::set('useDatatable', $useDatatable);
js::set('from', $from);

/* Replace Iteration to Execution. */
js::set('checkedSummary', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->checkedExecSummary));
js::set('pageSummary', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->pageExecSummary));
js::set('executionSummary', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->executionSummary));
js::set('checkedExecutions', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->checkedExecutions));
?>
<?php
/* Set unfold parent executionID. */
js::set('unfoldAll', $lang->execution->treeLevel['all']);
js::set('foldAll', $lang->execution->treeLevel['root']);
js::set('isCNLang', !$this->loadModel('common')->checkNotCN())
?>
<?php $canBatchEdit = common::hasPriv('execution', 'batchEdit');?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolBar pull-left'>
    <?php if($from == 'project'):?>
    <div class='btn-group'>
      <?php $viewName = $productID != 0 ? zget($productList,$productID) : $lang->product->allProduct;?>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          $class = '';
          if($productID == 0) $class = 'class="active"';
          echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&orderby=$orderBy"), $lang->product->allProduct) . "</li>";
          foreach($productList as $key => $product)
          {
              $class = $productID == $key ? 'class="active"' : '';
              echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&orderby=$orderBy&productID=$key"), $product) . "</li>";
          }
        ?>
      </ul>
    </div>
    <?php endif;?>
    <?php common::sortFeatureMenu();?>
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=$key&orderBy=$orderBy&productID=$productID"), $label, '', "class='btn btn-link' id='{$key}Tab' data-app='$from'");?>
    <?php endforeach;?>
    <?php if($canBatchEdit) echo html::checkbox('showEdit', array('1' => $lang->execution->editAction), $showBatchEdit);?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->execution->byQuery;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=$from", "<i class='icon-export muted'> </i> " . $lang->export, '', "class='btn btn-link export'")?>
    <?php if(common::hasPriv('execution', 'create')) echo html::a($this->createLink('execution', 'create'), "<i class='icon icon-sm icon-plus'></i> " . ($from == 'execution' ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-primary create-execution-btn' data-app='execution' onclick='$(this).removeAttr(\"data-toggle\")'");?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <div class="cell<?php if($status == 'bySearch') echo ' show';?>" id="queryBox" data-module='execution'></div>
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $from == 'execution' ? $lang->execution->noExecutions : $lang->execution->noExecution;?></span>
      <?php if(empty($allExecutionsNum)):?>
        <?php if(common::hasPriv('execution', 'create')):?>
        <?php echo html::a($this->createLink('execution', 'create'), "<i class='icon icon-plus'></i> " . ($from == 'execution' ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-info' data-app='execution'");?>
        <?php endif;?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='executionsForm' method='post' action='<?php echo inLink('batchEdit');?>'>
    <div class="table-header fixed-right">
      <nav class="btn-toolbar pull-right setting"></nav>
    </div>
    <?php
    $vars = "status=$status&orderBy=%s&productID=$productID&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
    if($useDatatable) include '../../common/view/datatable.html.php';
    else              include '../../common/view/tablesorter.html.php';

    $setting = $this->datatable->getSetting('execution');
    $widths  = $this->datatable->setFixedFieldWidth($setting);
    $columns = 0;
    ?>
    <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
    <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='executionList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
      <thead>
        <tr>
          <?php
          foreach($setting as $key => $value)
          {
              if($value->show)
              {
                  $this->datatable->printHead($value, $orderBy, $vars, $canBatchEdit);
                  $columns ++;
              }
          }
          ?>
        </tr>
      </thead>
      <tbody class='sortable' id='executionTableList'>
        <?php foreach($executionStats as $execution):?>
        <tr data-id='<?php echo $execution->id ?>' data-order='<?php echo $execution->order ?>' data-status='<?php echo $execution->status?>'>
          <?php foreach($setting as $key => $value) $this->execution->printCell($value, $execution, $users, $useDatatable ? 'datatable' : 'table', $isStage, $productID);?>
        </tr>
        <?php if(!empty($execution->children)):?>
        <?php $i = 0;?>
        <?php foreach($execution->children as $key => $child):?>
        <?php $class  = $i == 0 ? ' table-child-top' : '';?>
        <?php $class .= ($i + 1 == count($execution->children)) ? ' table-child-bottom' : '';?>
        <tr class='table-children<?php echo $class;?> parent-<?php echo $execution->id;?>' data-id='<?php echo $child->id?>'>
          <?php foreach($setting as $key => $value) $this->execution->printCell($value, $child, $users, $useDatatable ? 'datatable' : 'table', $isStage, $productID, true);?>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if(!$useDatatable) echo '</div>';?>
    <?php if($executionStats):?>
    <div class='table-footer'>
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->execution->batchEdit, '', 'btn');?>
      </div>
      <?php endif;?>
      <div class="table-statistic"></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
  <?php endif;?>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('status', $status)?>
<?php include '../../common/view/footer.html.php';?>
