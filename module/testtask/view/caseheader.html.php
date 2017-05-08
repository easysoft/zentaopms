<div id='featurebar'>
  <div class='heading'>
    <?php echo "<span class='prefix'>" . html::icon($lang->icons['usecase']) . '</span><strong>' . $task->name . '</strong>';?>
  </div>
  <div class='nav'>
    <li>
      <div class='label-angle <?php echo !empty($moduleID) ? 'with-close' : ''?>'>
        <?php
        $this->app->loadLang('tree');
        echo isset($moduleID) ? $moduleName : $this->lang->tree->all;
        if(!empty($moduleID))
        {
            $removeLink = $browseType == 'bymodule' ? inlink('cases', "taskID=$taskID&browseType=$browseType&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("taskCaseModule")';
            echo html::a($removeLink, "<span class='close'>&times;</span>", '', "class='text-muted'");
        }
        ?>
      </div>
    </li>
    <?php
    $hasCasesPriv = common::hasPriv('testtask', 'cases');
    $hasGroupPriv = common::hasPriv('testtask', 'groupcase');
    ?>
    <?php
    if($hasCasesPriv) echo "<li id='allTab'>" . html::a($this->inlink('cases', "taskID=$taskID&browseType=all&param=0"), $lang->testtask->allCases) . "</li>";
    if($hasCasesPriv) echo "<li id='assignedtomeTab'>" . html::a($this->inlink('cases', "taskID=$taskID&browseType=assignedtome&param=0"), $lang->testtask->assignedToMe) . "</li>";

    if($hasGroupPriv and $this->config->global->flow != 'onlyTest')
    {
        echo "<li id='groupTab' class='dropdown'>";
        $groupBy  = isset($groupBy) ? $groupBy : '';
        $current  = zget($lang->testcase->groups, isset($groupBy) ? $groupBy : '', '');
        if(empty($current)) $current = $lang->testcase->groups[''];
        echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->testcase->groups as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
            echo html::a($this->inlink('groupCase', "taskID=$taskID&groupBy=$key"), $value);
        }
        echo '</ul></li>';
    }

    if($this->methodName == 'cases') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";
    if(common::hasPriv('testtask', 'view')) echo '<li>' . html::a(inlink('view', "taskID=$taskID"), $lang->testtask->view) . '</li>';
    if(common::hasPriv('testreport', 'browse')) echo '<li>' . html::a($this->createLink('testreport', 'browse', "objectID=$productID&objectType=product&extra=$taskID"), $lang->testtask->reportField) . '</li>';
    ?>
  </div>
  <div class='actions'>
    <?php
    echo "<div class='btn-group'>";
    common::printIcon('testtask', 'linkCase', "taskID=$task->id", '', 'button', 'link');
    common::printIcon('testcase', 'export', "productID=$productID&orderBy=`case`_desc&taskID=$task->id", '', 'button', '', '', 'iframe export');
    common::printIcon('testtask', 'report', "productID=$productID&taskID=$task->id&browseType=$browseType&branchID=$task->branch&moduleID=$moduleID");
    echo '</div>';
    echo "<div class='btn-group'>";
    common::printRPN($this->session->testtaskList, '');
    echo '</div>';
    ?>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
