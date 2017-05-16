<?php js::set('flow', $this->config->global->flow);?>
<?php if(!isset($branch)) $branch = 0;?>
<?php if($this->config->global->flow == 'onlyTest'):?>
<style>
.nav > li > .btn-group > a, .nav > li > .btn-group > a:hover, .nav > li > .btn-group > a:focus{background: #1a4f85; border-color: #164270;}
.outer.with-side #featurebar {background: none; border: none; line-height: 0; margin: 0; min-height: 0; padding: 0; }
#querybox #searchform{border-bottom: 1px solid #ddd; margin-bottom: 20px;}
</style>
<div id='featurebar'>
  <ul class='submenu hidden'>
    <?php
    $hasBrowsePriv = common::hasPriv('testcase', 'browse');
    $hasGroupPriv  = common::hasPriv('testcase', 'groupcase');
    $hasZeroPriv   = common::hasPriv('story', 'zerocase');
    ?>
    <?php
    if($this->methodName == 'browse') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";
    ?>
    <li class='pull-right'>
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
    </li>

    <li class='pull-right'>
      <a class='dropdown-toggle' data-toggle='dropdown' id='importAction'><i class='icon-upload-alt'></i> <?php echo $lang->import ?><span class='caret'></span></a>
      <ul class='dropdown-menu' id='importActionMenu'>
      <?php 
      $misc = common::hasPriv('testcase', 'import') ? "class='export'" : "class=disabled";
      $link = common::hasPriv('testcase', 'import') ?  $this->createLink('testcase', 'import', "productID=$productID&branch=$branch") : '#';
      echo "<li>" . html::a($link, $lang->testcase->importFile, '', $misc) . "</li>";

      $misc = common::hasPriv('testcase', 'importFromLib') ? '' : "class=disabled";
      $link = common::hasPriv('testcase', 'importFromLib') ?  $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch") : '#';
      echo "<li>" . html::a($link, $lang->testcase->importFromLib, '', $misc) . "</li>";
      ?>
      </ul>
    </li>

    <li class='pull-right'>
      <a class='dropdown-toggle' data-toggle='dropdown'>
        <i class='icon-download-alt'></i> <?php echo $lang->export ?>
        <span class='caret'></span>
      </a>
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
    </li>
  </ul>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<?php else:?>
<div id='featurebar'>
  <ul class='nav'>
    <li>
      <div class='label-angle<?php if(!empty($moduleID)) echo ' with-close';?>'>
        <?php
        echo !empty($moduleID) ? $moduleName : $this->lang->tree->all;
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
    $menuType = $menuItem->name;
    if(!$config->testcase->needReview and empty($config->testcase->forceReview) and $menuType == 'wait') continue;
    if($this->config->global->flow == 'onlyTest' and (strpos(',needconfirm,group,zerocase,', ',' . $menuType . ',') !== false)) continue;
    if($hasBrowsePriv and strpos($menuType, 'QUERY') === 0)
    {
        $queryID = (int)substr($menuType, 5);
        echo "<li id='{$menuType}Tab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&branch=$branch&browseType=bySearch&param=$queryID"), $menuItem->text) . "</li>";
    }
    elseif($hasBrowsePriv and ($menuType == 'all' or $menuType == 'needconfirm' or $menuType == 'wait'))
    {
        echo "<li id='{$menuType}Tab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&branch=$branch&browseType=$menuType"), $menuItem->text) . "</li>";
    }
    elseif($hasBrowsePriv and $menuType == 'suite')
    {
        $currentSuiteID = isset($suiteID) ? (int)$suiteID : 0;
        $currentSuite   = zget($suiteList, $currentSuiteID, '');
        $currentLable   = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;

        echo "<li id='bysuiteTab' class='dropdown'>";
        echo html::a('javascript:;', $currentLable . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu' style='max-height:240px; overflow-y:auto'>";

        foreach ($suiteList as $suiteID => $suite)
        {
            $suiteName = $suite->name;
            if($suite->type == 'public') $suiteName .= " <span class='label label-info'>{$lang->testsuite->authorList[$suite->type]}</span>";

            echo '<li' . ($suiteID == (int)$currentSuiteID ? " class='active'" : '') . '>';
            echo html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=bySuite&param=$suiteID"), $suiteName);
            echo "</li>";
        }

        echo '</ul></li>';
    }
    elseif($hasGroupPriv and $menuType == 'group')
    {
        $groupBy  = isset($groupBy) ? $groupBy : '';
        $current  = zget($lang->testcase->groups, isset($groupBy) ? $groupBy : '', '');
        if(empty($current)) $current = $lang->testcase->groups[''];

        echo "<li id='groupTab' class='dropdown'>";
        echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";

        foreach ($lang->testcase->groups as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
            echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=$key"), $value);
            echo "</li>";
        }

        echo '</ul></li>';
    }
    elseif($hasZeroPriv and $menuType == 'zerocase')
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
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' id='importAction'><i class='icon-upload-alt'></i> <?php echo $lang->import ?><span class='caret'></span></button>
        <ul class='dropdown-menu' id='importActionMenu'>
        <?php 
        $misc = common::hasPriv('testcase', 'import') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'import') ?  $this->createLink('testcase', 'import', "productID=$productID&branch=$branch") : '#';
        echo "<li>" . html::a($link, $lang->testcase->importFile, '', $misc) . "</li>";

        $misc = common::hasPriv('testcase', 'importFromLib') ? '' : "class=disabled";
        $link = common::hasPriv('testcase', 'importFromLib') ?  $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch") : '#';
        echo "<li>" . html::a($link, $lang->testcase->importFromLib, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
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
<?php endif;?>

<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
