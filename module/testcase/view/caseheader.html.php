<?php if(!isset($branch)) $branch = 0;?>
<div id='featurebar'>
  <ul class='nav'>
    <li>
      <div class='label-angle<?php if(!empty($moduleID)) echo ' with-close';?>'>
        <?php
        echo isset($moduleID) ? $moduleName : $this->lang->tree->all;
        if(!empty($moduleID))
        {
            $removeLink = $browseType == 'bymodule' ? inlink('browse', "productID=$productID&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
            echo html::a($removeLink, "<i class='icon icon-remove'></i>", '', "class='text-muted'");
        }
        ?>
      </div>
    </li>
    <?php
    $hasBrowsePriv = common::hasPriv('testcase', 'browse');
    $hasGroupPriv  = common::hasPriv('testcase', 'groupcase');
    $hasZeroPriv   = common::hasPriv('story', 'zerocase');
    ?>
    <?php foreach(customModel::getFeatureMenu('testcase', 'browse') as $menuItem):?>
    <?php
    if(isset($menuItem->hidden)) continue;
    $menyType = $menuItem->name;
    if($hasBrowsePriv and strpos($menyType, 'QUERY') === 0)
    {
        $queryID = (int)substr($menyType, 5);
        echo "<li id='{$menyType}Tab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&branch=$branch&browseType=bySearch&param=$queryID"), $menuItem->text) . "</li>";
    }
    elseif($hasBrowsePriv and ($menyType == 'all' or $menyType == 'needconfirm'))
    {
        echo "<li id='{$menyType}Tab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&branch=$branch&browseType=$menyType"), $menuItem->text) . "</li>";
    }
    elseif($hasBrowsePriv and $menyType == 'suite')
    {
        echo "<li id='bysuiteTab' class='dropdown'>";
        $currentSuiteID = isset($suiteID) ? (int)$suiteID : 0;
        $currentSuite   = zget($suiteList, $currentSuiteID, '');
        $currentName    = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;
        echo html::a('javascript:;', $currentName . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu' style='max-height:240px; overflow-y:auto'>";
        foreach ($suiteList as $suiteID => $suite)
        {
            echo '<li' . ($suiteID == (int)$currentSuiteID ? " class='active'" : '') . '>';
            $suiteName = $suite->name;
            if($suite->type == 'public') $suiteName .= " <span class='label label-info'>{$lang->testsuite->authorList[$suite->type]}</span>";
            echo html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=bySuite&param=$suiteID"), $suiteName);
        }
        echo '</ul></li>';
    }
    elseif($hasGroupPriv and $menyType == 'group')
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
            echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=$key"), $value);
        }
        echo '</ul></li>';
    }
    elseif($hasZeroPriv and $menyType == 'zerocase')
    {
        echo "<li id='zerocaseTab'>" . html::a($this->createLink('story', 'zeroCase', "productID=$productID"), $lang->story->zeroCase) . '</li>';
    }
    ?>
    <?php endforeach;?>
    <?php
    if($this->methodName == 'browse') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";
    ?>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
          <i class='icon-download-alt'></i> <?php echo $lang->export ?>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='exportActionMenu'>
        <?php 
        $misc = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy") : '#';
        echo "<li>" . html::a($link, $lang->testcase->export, '', $misc) . "</li>";

        $misc = common::hasPriv('testcase', 'exportTemplet') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'exportTemplet') ?  $this->createLink('testcase', 'exportTemplet', "productID=$productID") : '#';
        echo "<li>" . html::a($link, $lang->testcase->exportTemplet, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' id='importAction'>
            <i class='icon-upload-alt'></i> <?php echo $lang->import ?>
            <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='importActionMenu'>
        <?php 
        $misc = common::hasPriv('testcase', 'import') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'import') ?  $this->createLink('testcase', 'import', "productID=$productID&branch=$branch") : '#';
        echo "<li>" . html::a($link, $lang->testcase->importFile, '', $misc) . "</li>";

        $misc = common::hasPriv('testcase', 'importLib') ? '' : "class=disabled";
        $link = common::hasPriv('testcase', 'importLib') ?  $this->createLink('testcase', 'importLib', "productID=$productID&branch=$branch") : '#';
        echo "<li>" . html::a($link, $lang->testcase->importFromLib, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
      <?php common::printIcon('testcase', 'report', "productID=$productID&browseType=$browseType&branchID=$branch&moduleID=$moduleID"); ?>
    </div>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php
        $initModule = isset($moduleID) ? (int)$moduleID : 0;
        $misc = common::hasPriv('testcase', 'create') ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
        $link = common::hasPriv('testcase', 'create') ?  $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule") : '#';
        echo html::a($link, "<i class='icon-plus'></i>" . $lang->testcase->create, '', $misc);
        ?>
        <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu pull-right'>
        <?php 
        $misc = common::hasPriv('testcase', 'batchCreate') ? '' : "class=disabled";
        $link = common::hasPriv('testcase', 'batchCreate') ?  $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule") : '#';
        echo "<li>" . html::a($link, $lang->testcase->batchCreate, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
    </div>
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
