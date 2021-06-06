<?php js::set('flow', $config->global->flow);?>
<?php $isProjectApp  = $this->app->openApp == 'project'?>
<?php $currentModule = $isProjectApp ? 'project'  : 'testcase';?>
<?php $currentMethod = $isProjectApp ? 'testcase' : 'browse';?>
<?php $projectParam  = $isProjectApp ? "projectID={$this->session->project}&" : '';?>
<?php if(!isset($branch)) $branch = 0;?>
<?php if($config->global->flow == 'full'):?>
<style>
.btn-group a i.icon-plus {font-size: 16px;}
.btn-group a.btn-primary {border-right: 1px solid rgba(255,255,255,0.2);}
.btn-group button.dropdown-toggle.btn-primary {padding:6px;}
.body-modal #mainMenu>.btn-toolbar {width: auto;}
</style>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      if($this->app->rawMethod == 'browseunits')
      {
          echo $lang->testtask->unitTag[$browseType];
      }
      else
      {
          echo !empty($moduleID) ? $moduleName : $this->lang->tree->all;
          if(!empty($moduleID))
          {
              $removeLink = $browseType == 'bymodule' ? $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
              echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted' data-app='{$this->app->openApp}'");
          }
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
    <?php
    $hasBrowsePriv = $isProjectApp ? common::hasPriv('project', 'testcase') : common::hasPriv('testcase', 'browse');
    $hasGroupPriv  = common::hasPriv('testcase', 'groupcase');
    $hasZeroPriv   = common::hasPriv('story', 'zerocase');
    $hasUnitPriv   = common::hasPriv('testtask', 'browseunits');
    ?>
    <?php foreach(customModel::getFeatureMenu('testcase', 'browse') as $menuItem):?>
    <?php
    if(isset($menuItem->hidden)) continue;
    $menuType = $menuItem->name;
    if(!$config->testcase->needReview and empty($config->testcase->forceReview) and $menuType == 'wait') continue;
    if($hasBrowsePriv and $menuType == 'QUERY')
    {
        $searchBrowseLink = $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=bySearch&param=%s");
        $isBySearch       = $browseType == 'bysearch';
        include '../../common/view/querymenu.html.php';
    }
    elseif($hasBrowsePriv and ($menuType == 'all' or $menuType == 'needconfirm' or $menuType == 'wait'))
    {
        echo html::a($this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$menuType"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link' id='{$menuType}Tab' data-app='{$this->app->openApp}'");
    }
    elseif($hasBrowsePriv and $menuType == 'suite' and $this->app->openApp == 'qa')
    {
        $currentSuiteID = isset($suiteID) ? (int)$suiteID : 0;
        $currentSuite   = zget($suiteList, $currentSuiteID, '');
        $currentLable   = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;

        echo "<div id='bysuiteTab' class='btn-group'>";
        echo html::a('javascript:;', "<span class='text'>{$currentLable}</span>" . " <span class='caret'></span>", '', "class='btn btn-link' data-toggle='dropdown'");
        if(empty($productID) or common::canModify('product', $product))
        {
            echo "<ul class='dropdown-menu' style='max-height:240px; overflow-y:auto'>";

            if(empty($suiteList))
            {
                echo '<li>';
                echo html::a($this->createLink('testsuite', 'create', "productID=$productID"), "<i class='icon-plus'></i>" . $lang->testsuite->create);
                echo '</li>';
            }
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
        $groupBy = isset($groupBy)  ? $groupBy : '';
        $active  = !empty($groupBy) ? 'btn-active-text' : '';

        echo "<div id='groupTab' class='btn-group'>";
        echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story"), "<span class='text'>{$lang->testcase->groupByStories}</span>", '', "class='btn btn-link $active' data-app='{$this->app->openApp}'");
        echo '</div>';
    }
    elseif($hasZeroPriv and $menuType == 'zerocase')
    {
        $projectID = $isProjectApp ? $this->session->project : 0;
        echo html::a($this->createLink('story', 'zeroCase', "productID=$productID&branch=$branch&orderBy=id_desc&projectID=$projectID"), "<span class='text'>{$lang->story->zeroCase}</span>", '', "class='btn btn-link' id='zerocaseTab' data-app='{$this->app->openApp}'");
    }
    elseif($hasUnitPriv and $menuType == 'browseunits')
    {
        echo html::a($this->createLink('testtask', 'browseUnits', "productID=$productID"), "<span class='text'>{$lang->testcase->browseUnits}</span>", '', "class='btn btn-link' id='browseunitsTab' data-app='{$this->app->openApp}'");
    }
    ?>
    <?php endforeach;?>
    <?php
    if($this->methodName == 'browse') echo "<a id='bysearchTab' class='btn btn-link querybox-toggle'><i class='icon-search icon'></i> {$lang->testcase->bySearch}</a>";
    ?>
  </div>
  <?php if(!isonlybody()):?>
  <div class='btn-toolbar pull-right'>
    <?php if(!empty($productID)): ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>
        <i class='icon icon-export muted'></i> <?php echo $lang->export ?>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu pull-right' id='exportActionMenu'>
      <?php
      $class = common::hasPriv('testcase', 'export') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy&taskID=0&browseType=$browseType") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->export, '', $misc . "data-app={$this->app->openApp}") . "</li>";

      $class = common::hasPriv('testcase', 'exportTemplet') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'exportTemplet') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'exportTemplet') ?  $this->createLink('testcase', 'exportTemplet', "productID=$productID") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->exportTemplet, '', $misc . "data-app={$this->app->openApp} data-width='50%'") . "</li>";
      ?>
      </ul>
    </div>
    <?php endif;?>
    <?php if(empty($productID) or common::canModify('product', $product)):?>
    <?php if(!empty($productID)): ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i> <?php echo $lang->import ?><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
      <?php
      $class = common::hasPriv('testcase', 'import') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'import') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'import') ?  $this->createLink('testcase', 'import', "productID=$productID&branch=$branch") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->fileImport, '', $misc . "data-app={$this->app->openApp}") . "</li>";

      $class = common::hasPriv('testcase', 'importFromLib') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'importFromLib') ? "data-app='{$this->app->openApp}'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'importFromLib') ?  $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->importFromLib, '', $misc . "data-app={$this->app->openApp}") . "</li>";
      ?>
      </ul>
    </div>
    <?php endif;?>
    <?php $initModule = isset($moduleID) ? (int)$moduleID : 0;?>
    <?php if(!common::checkNotCN()):?>
    <div class='btn-group dropdown'>
      <?php
      $createTestcaseLink = $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule");
      $batchCreateLink    = $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");

      $buttonLink  = '';
      $buttonTitle = '';
      if(common::hasPriv('testcase', 'batchCreate'))
      {
          $buttonLink  = !empty($productID) ? $batchCreateLink : '';
          $buttonTitle = $lang->testcase->batchCreate;
      }
      if(common::hasPriv('testcase', 'create'))
      {
          $buttonLink  = $createTestcaseLink;
          $buttonTitle = $lang->testcase->create;
      }

      $hidden = empty($buttonLink) ? 'hidden' : '';
      echo html::a($buttonLink, "<i class='icon-plus'></i> " . $buttonTitle, '', "class='btn btn-primary $hidden' data-app='{$this->app->openApp}'");
      ?>
      <?php if(!empty($productID) and common::hasPriv('testcase', 'batchCreate') and common::hasPriv('testcase', 'create')):?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu'>
        <li><?php echo html::a($createTestcaseLink, $lang->testcase->create);?></li>
        <li><?php echo html::a($batchCreateLink, $lang->testcase->batchCreate, '', "data-app='{$this->app->openApp}'");?></li>
      </ul>
      <?php endif;?>
    </div>
    <?php if($this->app->rawMethod == 'browseunits' and (empty($productID) or common::canModify('product', $product))):?>
      <?php common::printLink('testtask', 'importUnitResult', "product=$productID", "<i class='icon icon-import'></i> " . $lang->testtask->importUnitResult, '', "class='btn btn-primary' data-app='{$this->app->openApp}'");?>
    <?php endif;?>
    <?php else:?>
    <div class='btn-group dropdown-hover'>
      <?php
      $link = common::hasPriv('testcase', 'create') ? $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule") : '###';
      $disabled = common::hasPriv('testcase', 'create') ? '' : "disabled";
      echo html::a($link, "<i class='icon icon-plus'></i> {$lang->testcase->create} </span><span class='caret'>", '', "class='btn btn-primary $disabled' data-app='project'");
      ?>
      <ul class='dropdown-menu'>
        <?php $disabled = common::hasPriv('testcase', 'batchCreate') ? '' : "class='disabled'";?>
        <li <?php echo $disabled?>>
        <?php
        $batchLink = $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");
        echo "<li>" . html::a($batchLink, "<i class='icon icon-plus'></i>" . $lang->testcase->batchCreate, '', "data-app='{$this->app->openApp}'") . "</li>";
        ?>
        </li>
      </ul>
    </div>
    <?php endif;?>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php endif;?>

<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
