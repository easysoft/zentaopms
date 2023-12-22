<div id='mainMenu' class='clearfix'>
  <div id='sidebarHeader'>
    <div class="title">
      <?php
      $this->app->loadLang('tree');
      echo isset($moduleID) ? $moduleName : $this->lang->tree->all;
      if(!empty($moduleID))
      {
          $removeLink = $browseType == 'bymodule' ? inlink('cases', "taskID=$taskID&browseType=$browseType&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("taskCaseModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
    <?php
    $hasCasesPriv = common::hasPriv('testtask', 'cases');
    $hasGroupPriv = common::hasPriv('testtask', 'groupcase');
    ?>
    <?php
    if($hasCasesPriv) echo html::a($this->inlink('cases', "taskID=$taskID&browseType=all&param=0"), "<span class='text'>{$lang->testtask->allCases}</span>", '', "id='allTab' class='btn btn-link' data-app='{$app->tab}'");
    if($hasCasesPriv) echo html::a($this->inlink('cases', "taskID=$taskID&browseType=assignedtome&param=0"), "<span class='text'>{$lang->testtask->assignedToMe}</span>", '', "id='assignedtomeTab' class='btn btn-link' data-app='{$app->tab}'");

    if($hasGroupPriv)
    {
        $groupBy  = isset($groupBy)  ? $groupBy : '';
        $active   = !empty($groupBy) ? 'btn-active-text' : '';

        echo "<div id='groupTab' class='btn-group'>";
        echo html::a($this->createLink('testtask', 'groupCase', "taskID=$taskID&groupBy=story"), "<span class='text'>{$lang->testcase->groupByStories}</span>", '', "class='btn btn-link $active' data-app='{$app->tab}'");
        echo '</div>';
    }
    ?>

    <?php if($this->methodName == 'cases'):?>
    <div class='btn-group'>
      <?php $active = $suiteName == $lang->testtask->browseBySuite ? '' : 'btn-active-text';?>
      <a href='javascript:;' class='btn btn-link btn-limit <?php echo $active;?>' data-toggle='dropdown'><span class='text' title='<?php echo $suiteName;?>'><?php echo $suiteName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          foreach($suites as $key => $name) echo "<li>" . html::a(inlink('cases', "taskID=$taskID&browseType=bysuite&param=$key"), $name) . "</li>";
        ?>
      </ul>
    </div>
    <?php echo "<a class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon icon-search muted'></i> {$lang->testcase->bySearch}</a>";?>
    <?php endif;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    common::printIcon('testtask', 'linkCase', "taskID=$task->id", $task, 'button', 'link');
    common::printIcon('testcase', 'export', "productID=$productID&orderBy=sort_desc&taskID=$task->id", '', 'button', '', '', 'export');
    common::printIcon('testtask', 'report', "productID=$productID&taskID=$task->id&browseType=$browseType&branchID=$task->branch&moduleID=" . (empty($moduleID) ? '' : $moduleID));
    common::printIcon('testtask',   'view',     "taskID=$task->id", '', 'button', 'list-alt');
    common::printBack($this->session->testtaskList, 'btn btn-link');
    ?>
  </div>
</div>
<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
