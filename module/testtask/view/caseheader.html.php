<div id='featurebar'>
  <div class='heading'>
    <?php echo "<span class='prefix'>" . html::icon($lang->icons['usecase']) . '</span><strong>' . $task->name . '</strong>';?>
  </div>
  <div class='nav'>
    <?php
    echo "<li id='allTab'>" . html::a($this->inlink('cases', "taskID=$taskID&browseType=all&param=0"), $lang->testtask->allCases) . "</li>";
    echo "<li id='assignedtomeTab'>" . html::a($this->inlink('cases', "taskID=$taskID&browseType=assignedtome&param=0"), $lang->testtask->assignedToMe) . "</li>";

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


    if($this->methodName == 'cases') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";
    echo '<li>' . html::a(inlink('view', "taskID=$taskID"), $lang->testtask->view) . '</li>';
    ?>
  </div>
  <div class='actions'>
    <?php
    echo "<div class='btn-group'>";
    common::printIcon('testtask', 'linkCase', "taskID=$task->id", '', 'button', 'link');
    common::printIcon('testcase', 'export', "productID=$productID&orderBy=`case`_desc&taskID=$task->id", '', 'button', '', '', 'iframe export');
    echo '</div>';
    echo "<div class='btn-group'>";
    common::printRPN($this->session->testtaskList, '');
    echo '</div>';
    ?>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>

<?php foreach(glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php") as $fileName) include_once $fileName; ?>
