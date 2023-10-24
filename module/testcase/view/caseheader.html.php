<?php js::set('flow', $config->global->flow);?>
<?php $this->app->loadLang('zanode');?>
<?php $isProjectApp       = $this->app->tab == 'project'?>
<?php $currentModule      = $isProjectApp ? 'project'  : 'testcase';?>
<?php $currentMethod      = $isProjectApp ? 'testcase' : 'browse';?>
<?php $projectParam       = $isProjectApp ? "projectID={$this->session->project}&" : '';?>
<?php if(common::checkNotCN()):?>
<style> .btn-toolbar>.btn {margin-right: 3px !important;}</style>
<?php endif;?>
<?php if(!isset($branch)) $branch = 0;?>
<?php if($config->global->flow == 'full'):?>
<style>
.btn-group .icon-help {line-height: 30px;}
.btn-group .popover {width:300px;}
.btn-group a.btn-primary {border-right: 1px solid rgba(255,255,255,0.2);}
.btn-group button.dropdown-toggle.btn-primary {padding:6px;}
.body-modal #mainMenu>.btn-toolbar {width: auto;}
#mainMenu .pull-left .checkbox-primary {margin-top: 6px;}
#mainMenu .dividing-line {width: 1px; height: 16px; display: inline-block; background: #D8DBDE; margin: 7px 8px 0 0; float: left;}
#byTypeTab li.split{border-top: 1px solid #eee;}
</style>
<div id='mainMenu' class='clearfix'>
  <?php if($this->app->rawMethod == 'browse'):?>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      echo !empty($moduleID) ? $moduleName : $this->lang->tree->all;
      if(!empty($moduleID))
      {
          $removeLink = $browseType == 'bymodule' ? $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&caseType=&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted' data-app='{$this->app->tab}'");
      }
      ?>
    </div>
  </div>
  <?php endif;?>
  <div class='btn-toolbar pull-left'>
    <?php
    $hasBrowsePriv = $isProjectApp ? common::hasPriv('project', 'testcase') : common::hasPriv('testcase', 'browse');
    $hasGroupPriv  = common::hasPriv('testcase', 'groupcase');
    $hasZeroPriv   = common::hasPriv('testcase', 'zerocase');
    $hasUnitPriv   = common::hasPriv('testtask', 'browseunits');
    ?>

    <?php if($this->app->rawMethod == 'browseunits'):?>
    <?php
    $caseType = 'unit';
    $lang->testcase->typeList[''] = $lang->testcase->allType;

    $currentTypeName = zget($lang->testcase->typeList, $caseType, '');
    $currentLable    = empty($currentTypeName) ? $lang->testcase->allType : $currentTypeName;
    if(!isset($param)) $param = 0;

    echo "<div id='byTypeTab' class='btn-group'>";
    echo html::a('javascript:;', "<span class='text'>{$currentLable}</span>" . " <span class='caret'></span>", '', "class='btn btn-link' data-toggle='dropdown'");
    echo "<ul class='dropdown-menu' style='max-height:240px; overflow-y:auto; width:130px;'>";

    foreach($lang->testcase->typeList as $type => $typeName)
    {
        echo '<li' . ($type == 'unit' ? " class='active'" : '') . '>';
        if($hasUnitPriv and $type == 'unit')
        {
            echo html::a($this->createLink('testtask', 'browseUnits', "productID=$productID&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID"), "{$lang->testcase->browseUnits}", '', " data-app='{$this->app->tab}'");
        }
        elseif(isset($groupBy))
        {
            echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID&caseType=$type"), $typeName);
        }
        else
        {
            echo html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=all&param=$param&caseType=$type"), $typeName);
        }
        echo "</li>";
    }
    echo '</ul></div>';
    ?>
    <?php foreach($lang->testtask->unitTag as $key => $label):?>
    <?php echo html::a(inlink('browseUnits', "productID=$productID&browseType=$key&orderBy=$orderBy"), "<span class='text'>$label</span>", '', "id='{$key}UnitTab' class='btn btn-link' data-app='{$this->app->tab}'");?>
    <?php endforeach;?>
    <?php else:?>
    <?php
    $rawModule = $this->app->rawModule;
    $rawMethod = $this->app->rawMethod;
    if(!isset($lang->{$rawModule}->featureBar[$rawMethod]))
    {
        $rawModule = $app->tab == 'project' ? 'project' : 'testcase';
        $rawMethod = $rawModule == 'testcase' ? 'browse' : 'testcase';
    }
    ?>
    <?php foreach(customModel::getFeatureMenu($rawModule, $rawMethod) as $menuItem):?>
    <?php
    if(isset($menuItem->hidden)) continue;
    $menuType = $menuItem->name;
    $caseType = isset($caseType) ? $caseType : '';
    if(!$config->testcase->needReview and empty($config->testcase->forceReview) and $menuType == 'wait') continue;
    if($hasBrowsePriv and $menuType == 'QUERY' and in_array($browseType, array('all', 'needconfirm', 'bysuite')))
    {
        $searchBrowseLink = $this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=bySearch&param=%s");
        $isBySearch       = $browseType == 'bysearch';
        include '../../common/view/querymenu.html.php';
    }
    elseif($hasBrowsePriv and $menuType == 'casetype')
    {
        if($currentModule == 'project') continue;
        if($this->moduleName == 'testtask' and $this->methodName == 'browseunits') continue;
        if($this->moduleName == 'story' and $this->methodName == 'zerocase') continue;
        if($browseType == 'bysuite') continue;

        $lang->testcase->typeList[''] = $lang->testcase->allType;

        $currentTypeName = zget($lang->testcase->typeList, $caseType, '');
        $currentLable    = empty($currentTypeName) ? $lang->testcase->allType : $currentTypeName;
        if(!isset($param)) $param = 0;

        echo "<div id='byTypeTab' class='btn-group'>";
        echo html::a('javascript:;', "<span class='text'>{$currentLable}</span>" . " <span class='caret'></span>", '', "class='btn btn-link' data-toggle='dropdown'");
        echo "<ul class='dropdown-menu' style='max-height:240px; overflow-y:auto; width:130px;'>";

        foreach($lang->testcase->typeList as $type => $typeName)
        {
            echo '<li' . ($type == $caseType ? " class='active'" : '') . '>';
            if($hasUnitPriv and $type == 'unit')
            {
                echo html::a($this->createLink('testtask', 'browseUnits', "productID=$productID&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID"), "{$lang->testcase->browseUnits}", '', " data-app='{$this->app->tab}'");
            }
            elseif(isset($groupBy))
            {
                echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID&caseType=$type"), $typeName);
            }
            else
            {
                echo html::a($this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=$type"), $typeName);
            }
            echo "</li>";
        }
        echo '</ul></div>';
    }
    elseif($hasBrowsePriv and $menuType == 'autocase')
    {
        if($this->moduleName == 'testtask' and $this->methodName == 'browseunits') continue;
        if($this->moduleName == 'story' and $this->methodName == 'zerocase') continue;
        if($browseType == 'bysuite' or $browseType == 'bysearch') continue;

        echo html::checkbox('showAutoCase', array('1' => $lang->testcase->showAutoCase), '', $this->cookie->showAutoCase ? 'checked=checked' : '');
    }
    elseif($hasBrowsePriv and ($menuType == 'all' or $menuType == 'needconfirm' or $menuType == 'wait'))
    {
        echo html::a($this->createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$menuType&param=0&caseType=$caseType"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link' id='{$menuType}Tab' data-app='{$this->app->tab}'");
    }
    elseif($hasBrowsePriv and $menuType == 'suite' and $this->app->tab == 'qa')
    {
        $currentSuiteID = isset($suiteID) ? (int)$suiteID : 0;
        $currentSuite   = zget($suiteList, $currentSuiteID, '');
        $currentLable   = empty($currentSuite) ? $lang->testsuite->common : $currentSuite->name;

        echo "<div id='bysuiteTab' class='btn-group'>";
        echo html::a('javascript:;', "<span class='text'>{$currentLable}</span>" . " <span class='caret'></span>", '', "class='btn btn-link' data-toggle='dropdown'");
        echo "<ul class='dropdown-menu' style='max-height:240px; overflow-y:auto'>";

        if(empty($productID) or common::canModify('product', $product))
        {
            if(empty($suiteList))
            {
                echo '<li>';
                echo html::a($this->createLink('testsuite', 'create', "productID=$productID"), "<i class='icon-plus'></i> " . $lang->testsuite->create);
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
        echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link $active' data-app='{$this->app->tab}'");
        echo '</div>';
    }
    elseif($hasZeroPriv and $menuType == 'zerocase')
    {
        $projectID = $isProjectApp ? $this->session->project : 0;
        echo html::a($this->createLink('testcase', 'zeroCase', "productID=$productID&branch=$branch&orderBy=id_desc&projectID=$projectID"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link' id='zerocaseTab' data-app='{$this->app->tab}'");
    }

    ?>
    <?php endforeach;?>
    <?php
    if($this->methodName == 'browse') echo "<a id='bysearchTab' class='btn btn-link querybox-toggle'><i class='icon-search icon'></i> {$lang->testcase->bySearch}</a>";
    ?>
    <?php endif;?>
  </div>

  <?php $isZeroCase = $this->app->rawMethod == 'zerocase';?>
  <?php if(!isonlybody()):?>
  <div class='btn-toolbar pull-right'>
    <?php if(!$isZeroCase and common::hasPriv('testcase', 'createScene') || common::hasPriv('testcase', 'editScene') || common::hasPriv('testcase', 'deleteScene') || common::hasPriv('testcase', 'changeScene') || common::hasPriv('testcase', 'batchChangeScene') || common::hasPriv('testcase', 'updateOrder') || common::hasPriv('testcase', 'importXmind') || common::hasPriv('testcase', 'getXmindImport') || common::hasPriv('testcase', 'showXMindImport') || common::hasPriv('testcase', 'exportXmind')): ?>
    <div class='btn-group btn btn-link'>
      <?php echo html::checkbox('onlyScene', array('1' => $lang->testcase->onlyScene), '', $this->cookie->onlyScene ? 'checked=checked' : '');?>
    </div>
    <?php endif;?>
    <?php $moduleID = isset($moduleID) ? (int)$moduleID : 0;?>
    <?php if(!$isZeroCase and !empty($productID)): ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>
        <i class='icon icon-export muted'></i>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu pull-right' id='exportActionMenu'>
      <?php
      $class = common::hasPriv('testcase', 'export') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy&taskID=0&browseType=$browseType") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->export, '', $misc . "data-app={$this->app->tab}") . "</li>";

      $class = common::hasPriv('testcase', 'exportTemplate') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'exportTemplate') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'exportTemplate') ?  $this->createLink('testcase', 'exportTemplate', "productID=$productID") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->exportTemplate, '', $misc . "data-app={$this->app->tab} data-width='65%'") . "</li>";

      $class = common::hasPriv('testcase', 'exportXmind') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'exportXmind') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'exportXmind') ?  $this->createLink('testcase', 'exportXmind', "productID=$productID&moduleID=$moduleID&branch=$branch") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->xmindExport, '', $misc . "data-app={$this->app->tab}") . "</li>";
      ?>
      </ul>
    </div>
    <?php endif;?>
    <?php if(empty($productID) or common::canModify('product', $product)):?>
    <?php if(!$isZeroCase and !empty($productID) and (common::hasPriv('testcase', 'import') or common::hasPriv('testcase', 'importFromLib'))): ?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i> <span class='caret'></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
      <?php
      if(common::hasPriv('testcase', 'import')) echo "<li>" . html::a($this->createlink('testcase', 'import', "productID=$productID&branch=$branch"), $lang->testcase->fileImport, '', "class='export' data-app={$app->tab}") . "</li>";

      $link  = $this->createLink('testcase', 'importFromLib', "productID=$productID&branch=$branch&libID=0&orderBy=id_desc&browseType=&queryID=10&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID");
      if(common::hasPriv('testcase', 'importFromLib')) echo "<li>" . html::a($link, $lang->testcase->importFromLib, '', "data-app={$app->tab}") . "</li>";

      $class = common::hasPriv('testcase', 'importXmind') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'importXmind') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'importXmind') ?  $this->createLink('testcase', 'importXmind', "productID=$productID&branch=$branch") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->xmindImport, '', $misc . "data-app={$this->app->tab}") . "</li>";
      ?>
      </ul>
    </div>
    <?php endif;?>
    <?php if(!empty($productID)): ?>
    <div class='btn-group'>
      <?php common::printLink('testcase', 'automation', "productID=$productID", "<i class='icon-wrench muted'> </i>" . $lang->testcase->automation, '', "class='btn btn-link iframe' data-width='50%'", true, true)?>
    </div>
    <?php endif;?>

    <div class='btn-group dropdown'>
      <?php
      $createTestcaseLink = $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID");
      $batchCreateLink    = $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID");
      $createSceneLink    = $this->createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$moduleID");

      $buttonLink  = '';
      $buttonTitle = '';
      if(common::hasPriv('testcase', 'createScene'))
      {
          $buttonLink  = $createSceneLink;
          $buttonTitle = $lang->testcase->newScene;
      }
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
      echo html::a($buttonLink, "<i class='icon-plus'></i> " . $buttonTitle, '', "class='btn btn-primary $hidden' data-app='{$this->app->tab}'");
      ?>
      <?php if(!empty($productID) and common::hasPriv('testcase', 'batchCreate') and common::hasPriv('testcase', 'create')):?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu'>
        <li><?php echo html::a($createTestcaseLink, $lang->testcase->create);?></li>
        <li><?php echo html::a($batchCreateLink, $lang->testcase->batchCreate, '', "data-app='{$this->app->tab}'");?></li>
        <?php if(common::hasPriv('testcase', 'createScene')){ ?>
        <li><?php echo html::a($createSceneLink, $lang->testcase->newScene, '', "data-app='{$this->app->tab}'");?></li>
        <?php } ?>
      </ul>
      <?php endif;?>
    </div>
    <?php if($this->app->rawMethod == 'browseunits' and (empty($productID) or common::canModify('product', $product))):?>
      <?php common::printLink('testtask', 'importUnitResult', "product=$productID", "<i class='icon icon-import'></i> " . $lang->testtask->importUnitResult, '', "class='btn btn-primary' data-app='{$this->app->tab}'");?>
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
<script>
$(function()
{
    var $allTab           = $('#allTab');
    var $waitTab          = $('#waitTab');
    var $needconfirmTab   = $('#needconfirmTab');
    var $groupTab         = $('#groupTab');
    var $zerocaseTab      = $('#zerocaseTab');
    var $bysuiteTab       = $('#bysuiteTab');
    var $browseunitsTab   = $('#browseunitsTab');
    var hasAllTab         = $allTab.length > 0;
    var hasWaitTab        = $waitTab.length > 0;
    var hasNeedconfirmTab = $needconfirmTab.length > 0;
    var hasGroupTab       = $groupTab.length > 0;
    var hasZerocaseTab    = $zerocaseTab.length > 0;
    var hasbysuiteTab     = $bysuiteTab.length > 0;
    var hasBrowseunitsTab = $browseunitsTab.length > 0;

    if((hasAllTab || hasWaitTab) && (hasNeedconfirmTab || hasGroupTab || hasbysuiteTab || hasZerocaseTab || hasBrowseunitsTab))
    {
        if(hasWaitTab)
        {
            $waitTab.after("<div class='dividing-line'></div>");
        }
        else
        {
            $allTab.after("<div class='dividing-line'></div>");
        }
    }

    if((hasNeedconfirmTab || hasGroupTab || hasZerocaseTab) && (hasbysuiteTab || hasBrowseunitsTab))
    {
        if(hasZerocaseTab)
        {
            $zerocaseTab.after("<div class='dividing-line'></div>");
        }
        else if(hasGroupTab)
        {
            $groupTab.after("<div class='dividing-line'></div>");
        }
        else
        {
            $needconfirmTab.after("<div class='dividing-line'></div>");
        }
    }

    $('[data-toggle="popover"]').popover();
});
</script>
