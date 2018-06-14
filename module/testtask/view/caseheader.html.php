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
    if($hasCasesPriv) echo html::a($this->inlink('cases', "taskID=$taskID&browseType=all&param=0"), "<span class='text'>{$lang->testtask->allCases}</span>", '', "id='allTab' class='btn btn-link'");
    if($hasCasesPriv) echo html::a($this->inlink('cases', "taskID=$taskID&browseType=assignedtome&param=0"), "<span class='text'>{$lang->testtask->assignedToMe}</span>", '', "id='assignedtomeTab' class='btn btn-link'");

    if($hasGroupPriv and $config->global->flow != 'onlyTest')
    {
        echo "<div class='btn-group'>";
        $active  = $browseType == 'group' ? 'btn-active-text' : '';
        $groupBy = isset($groupBy) ? $groupBy : '';
        $current = zget($lang->testcase->groups, isset($groupBy) ? $groupBy : '', '');
        if(empty($current)) $current = $lang->testcase->groups[''];
        echo html::a('javascript:;', "<span class='text'>{$current} <span class='caret'></span></span>", '', "class='btn btn-link $active' data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->testcase->groups as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
            echo html::a($this->inlink('groupCase', "taskID=$taskID&groupBy=$key"), $value);
        }
        echo '</ul></div>';
    }

    if($this->methodName == 'cases') echo "<a class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon icon-search muted'></i>{$lang->testcase->bySearch}</a>";
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    common::printIcon('testtask', 'linkCase', "taskID=$task->id", '', 'button', 'link');
    common::printIcon('testcase', 'export', "productID=$productID&orderBy=`case`_desc&taskID=$task->id", '', 'button', '', '', 'export');
    common::printIcon('testtask', 'report', "productID=$productID&taskID=$task->id&browseType=$browseType&branchID=$task->branch&moduleID=" . (empty($moduleID) ? '' : $moduleID));
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
