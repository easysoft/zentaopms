<?php include '../../common/view/header.html.php';?>
<?php
js::import($jsRoot . 'dtable/min.js');
css::import($jsRoot . 'dtable/min.css');

$cols       = $this->execution->generateCol();
$executions = $this->execution->generateRow($executionStats, $users, $productID);

js::set('cols', json_encode($cols));
js::set('data', json_encode($executions));

js::set('orderBy', $orderBy);
js::set('status', $status);
js::set('unfoldExecutions', array());
js::set('from', $from);

/* Replace Iteration to Execution. */
js::set('checkedSummary', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->checkedExecSummary));
js::set('pageSummary', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->pageExecSummary));
js::set('executionSummary', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->executionSummary));
js::set('checkedExecutions', str_replace($lang->executionCommon, $lang->execution->common, $lang->execution->checkedExecutions));
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
    <div id="myTable"></div>
    <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <script>
  cols = JSON.parse(cols);
  data = JSON.parse(data);
  const options = {
      height: 'auto',
      striped: true,
      cols: cols,
      data: data,
  };
  
  $('#myTable').dtable(options);
  </script>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
