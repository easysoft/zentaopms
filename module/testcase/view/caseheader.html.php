<?php js::set('flow', $config->global->flow);?>
<?php if(!isset($branch)) $branch = 0;?>
<?php if($config->global->flow == 'full'):?>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      echo !empty($moduleID) ? $moduleName : $this->lang->tree->all;
      if(!empty($moduleID))
      {
          $removeLink = $browseType == 'bymodule' ? inlink('browse', "productID=$productID&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
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
    if($config->global->flow == 'onlyTest' and (strpos(',needconfirm,group,zerocase,', ',' . $menuType . ',') !== false)) continue;
    if($hasBrowsePriv and $menuType == 'QUERY')
    {
        $searchBrowseLink = inlink('browse', "productID=$productID&branch=$branch&browseType=bySearch&param=%s");
        $isBySearch       = $browseType == 'bysearch';
        include '../../common/view/querymenu.html.php';
    }
    elseif($hasBrowsePriv and ($menuType == 'all' or $menuType == 'needconfirm' or $menuType == 'wait'))
    {
        echo html::a($this->createLink('testcase', 'browse', "productid=$productID&branch=$branch&browseType=$menuType"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link' id='{$menuType}Tab'");
    }
    elseif($hasBrowsePriv and $menuType == 'suite')
    {
        $currentSuiteID = isset($suiteID) ? (int)$suiteID : 0;
        $currentSuite   = zget($suiteList, $currentSuiteID, '');
        $currentLable   = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;

        echo "<div id='bysuiteTab' class='btn-group'>";
        echo html::a('javascript:;', $currentLable . " <span class='caret'></span>", '', "class='btn btn-link' data-toggle='dropdown'");
        echo "<ul class='dropdown-menu' style='max-height:240px; overflow-y:auto'>";

        if(empty($suiteList))
        {
            echo '<li>';
            echo html::a($this->createLink('testsuite', 'create', "productID=$productID"), "<i class='icon-plus'></i>" . $lang->testsuite->create);
            echo '</li>';
        }

        foreach($suiteList as $suiteID => $suite)
        {
            $suiteName = $suite->name;
            echo '<li' . ($suiteID == (int)$currentSuiteID ? " class='active'" : '') . '>';
            echo html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=bySuite&param=$suiteID"), $suiteName);
            echo "</li>";
        }

        echo '</ul></div>';
    }
    elseif($hasGroupPriv and $menuType == 'group')
    {
        $groupBy  = isset($groupBy)  ? $groupBy : '';
        $active   = !empty($groupBy) ? 'btn-active-text' : '';

        echo "<div id='groupTab' class='btn-group'>";
        echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story"), "<span class='text'>{$lang->testcase->groupByStories}</span>", '', "class='btn btn-link $active'");
        echo '</div>';
    }
    elseif($hasZeroPriv and $menuType == 'zerocase')
    {
        echo html::a($this->createLink('story', 'zeroCase', "productID=$productID"), "<span class='text'>{$lang->story->zeroCase}</span>", '', "class='btn btn-link' id='zerocaseTab'");
    }
    ?>
    <?php endforeach;?>
    <?php
    if($this->methodName == 'browse') echo "<a id='bysearchTab' class='btn btn-link querybox-toggle'><i class='icon-search icon'></i> {$lang->testcase->bySearch}</a>";
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>
        <i class='icon icon-export muted'></i> <?php echo $lang->export ?>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu' id='exportActionMenu'>
      <?php
      $class = common::hasPriv('testcase', 'export') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy&taskID=0&browseType=$browseType") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->export, '', $misc) . "</li>";

      $class = common::hasPriv('testcase', 'exportTemplet') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'exportTemplet') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'exportTemplet') ?  $this->createLink('testcase', 'exportTemplet', "productID=$productID") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->exportTemplet, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i> <?php echo $lang->import ?><span class='caret'></span></button>
      <ul class='dropdown-menu' id='importActionMenu'>
      <?php
      $class = common::hasPriv('testcase', 'import') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'import') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'import') ?  $this->createLink('testcase', 'import', "productID=$productID&branch=$branch") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->fileImport, '', $misc) . "</li>";

      $class = common::hasPriv('testcase', 'importFromLib') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'importFromLib') ? '' : "class=disabled";
      $link  = common::hasPriv('testcase', 'importFromLib') ?  $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->importFromLib, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
    <?php $initModule = isset($moduleID) ? (int)$moduleID : 0;?>
    <?php if(!common::checkNotCN()):?>
    <?php
    if(common::hasPriv('testcase', 'batchCreate'))
    {
        $link = $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");
        echo html::a($link, "<i class='icon-plus'></i> " . $lang->testcase->batchCreate, '', "class='btn btn-secondary'");
    }

    if(common::hasPriv('testcase', 'create'))
    {
        $link = $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule");
        echo html::a($link, "<i class='icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-primary'");
    }
    ?>
    <?php else:?>
    <div class='btn-group dropdown-hover'>
      <?php
      $link = common::hasPriv('testcase', 'create') ? $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule") : '###';
      $disabled = common::hasPriv('testcase', 'create') ? '' : "disabled";
      echo html::a($link, "<i class='icon icon-plus'></i> {$lang->testcase->create} </span><span class='caret'>", '', "class='btn btn-primary $disabled'");
      ?>
      <ul class='dropdown-menu'>
        <?php $disabled = common::hasPriv('testcase', 'batchCreate') ? '' : "class='disabled'";?>
        <li <?php echo $disabled?>>
        <?php
        $batchLink = $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");
        echo "<li>" . html::a($batchLink, "<i class='icon icon-plus'></i>" . $lang->testcase->batchCreate) . "</li>";
        ?>
        </li>
      </ul>
    </div>
    <?php endif;?>
  </div>
</div>
<?php endif;?>

<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
