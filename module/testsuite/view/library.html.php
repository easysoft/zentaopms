<?php
/**
 * The library view file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: library.html.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datatable.fix.html.php';
js::set('browseType',    $browseType);
js::set('moduleID',      $moduleID);
js::set('confirmDelete', $lang->testsuite->confirmDelete);
js::set('batchDelete',   $lang->testcase->confirmBatchDelete);
js::set('flow',   $this->config->global->flow);
?>
<?php if($this->config->global->flow == 'onlyTest'):?>
<style>
.nav > li > .btn-group > a, .nav > li > .btn-group > a:hover, .nav > li > .btn-group > a:focus{background: #1a4f85; border-color: #164270;}
.outer.with-side #featurebar {background: none; border: none; line-height: 0; margin: 0; min-height: 0; padding: 0; }
#querybox #searchform{border-bottom: 1px solid #ddd; margin-bottom: 20px;}
</style>
<div id='featurebar'>
  <ul class='submenu hidden'>
    <?php echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";?>

    <li class='right'>
      <div class='btn-group' id='createActionMenu'>
        <?php
        $misc = common::hasPriv('testsuite', 'createCase') ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
        $link = common::hasPriv('testsuite', 'createCase') ?  $this->createLink('testsuite', 'createCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)) : '#';
        echo html::a($link, "<i class='icon-plus'></i>" . $lang->testcase->create, '', $misc);
        ?>
        <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu pull-right'>
        <?php 
        $misc = common::hasPriv('testsuite', 'batchCreateCase') ? '' : "class=disabled";
        $link = common::hasPriv('testsuite', 'batchCreateCase') ?  $this->createLink('testsuite', 'batchCreateCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)) : '#';
        echo "<li>" . html::a($link, $lang->testcase->batchCreate, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
    </li>

    <li class='right'>
      <?php
      $link = common::hasPriv('testsuite', 'import') ?  $this->createLink('testsuite', 'import', "libID=$libID") : '#';
      if(common::hasPriv('testsuite', 'import')) echo html::a($link, "<i class='icon-upload-alt'></i> " . $lang->testcase->importFile, '', "class='export'");
      ?>
    </li>

    <li class='right'>
      <?php
      $link = common::hasPriv('testsuite', 'exportTemplet') ?  $this->createLink('testsuite', 'exportTemplet', "libID=$libID") : '#';
      if(common::hasPriv('testsuite', 'exportTemplet')) echo html::a($link, "<i class='icon-download-alt'></i> " . $lang->testsuite->exportTemplet, '', "class='export'");
      ?>
    </li>
  </ul>

  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<?php else:?>
<div id='featurebar'>
  <div class='heading'>
    <?php echo "<span class='prefix'>" . html::icon($lang->icons['usecase']) . '</span><strong>' . $libName . '</strong>';?>
  </div>
  <div class='nav'>
    <li>
      <div class='label-angle <?php echo !empty($moduleID) ? 'with-close' : ''?>'>
        <?php
        $this->app->loadLang('tree');
        echo isset($moduleID) ? $moduleName : $this->lang->tree->all;
        if(!empty($moduleID))
        {
            $removeLink = $browseType == 'bymodule' ? inlink('library', "libID=$libID&browseType=$browseType&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("libCaseModule")';
            echo html::a($removeLink, "<span class='close'>&times;</span>", '', "class='text-muted'");
        }
        ?>
      </div>
    </li>
    <?php $hasCasesPriv = common::hasPriv('testsuite', 'library'); ?>
    <?php
    if($hasCasesPriv) echo "<li id='allTab'>" . html::a($this->inlink('library', "libID=$libID&browseType=all"), $lang->testcase->allCases) . "</li>";
    if($hasCasesPriv and ($config->testcase->needReview or !empty($config->testcase->forceReview))) echo "<li id='waitTab'>" . html::a($this->inlink('library', "libID=$libID&browseType=wait"), $lang->testcase->statusList['wait']) . "</li>";
    echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";
    if(common::hasPriv('testsuite', 'libView')) echo '<li>' . html::a(inlink('libView', "libID=$libID"), $lang->testsuite->view) . '</li>';
    ?>
  </div>
  <div class='actions'>
    <div class='btn-group'>
     <?php
     $link = common::hasPriv('testsuite', 'exportTemplet') ?  $this->createLink('testsuite', 'exportTemplet', "libID=$libID") : '#';
     if(common::hasPriv('testsuite', 'exportTemplet')) echo html::a($link, "<i class='icon-download-alt'></i> " . $lang->testsuite->exportTemplet, '', "class='btn export'");

     $link = common::hasPriv('testsuite', 'import') ?  $this->createLink('testsuite', 'import', "libID=$libID") : '#';
     if(common::hasPriv('testsuite', 'import')) echo html::a($link, "<i class='icon-upload-alt'></i> " . $lang->testcase->importFile, '', "class='btn export'");
     ?>
    </div>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php
        $misc = common::hasPriv('testsuite', 'createCase') ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
        $link = common::hasPriv('testsuite', 'createCase') ?  $this->createLink('testsuite', 'createCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)) : '#';
        echo html::a($link, "<i class='icon-plus'></i>" . $lang->testcase->create, '', $misc);
        ?>
        <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu pull-right'>
        <?php 
        $misc = common::hasPriv('testsuite', 'batchCreateCase') ? '' : "class=disabled";
        $link = common::hasPriv('testsuite', 'batchCreateCase') ?  $this->createLink('testsuite', 'batchCreateCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)) : '#';
        echo "<li>" . html::a($link, $lang->testcase->batchCreate, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<?php endif;?>
<div class='side' id='treebox'>
  <a class='side-handle' data-id='testcaseTree'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $libName;?></strong></div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'>
          <?php common::printLink('tree', 'browse', "libID=$libID&view=caselib", $lang->tree->manage);?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <script>setTreeBox();</script>
  <form id='batchForm' method='post'>
    <?php $vars = "libID=$libID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='caseList'>
      <thead>
        <tr>
          <th class='w-id {sorter:false}'>    <?php common::printOrderLink('id',            $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-pri {sorter:false}'>   <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
          <th class='{sorter:false}'>         <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
          <th class='w-type {sorter:false}'>  <?php common::printOrderLink('type',          $orderBy, $vars, $lang->typeAB);?></th>
          <th class='w-user {sorter:false}'>  <?php common::printOrderLink('openedBy',      $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-100px {sorter:false}'> <?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
          <th class='w-70px {sorter:false}'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <?php if($cases):?>
      <tbody>
      <?php foreach($cases as $case):?>
      <tr class='text-center'>
        <?php $viewLink = $this->createLink('testcase', 'view', "caseID=$case->id&version=$case->version");?>
        <td class='cell-id'>
          <input type='checkbox' name='caseIDList[]'  value='<?php echo $case->id;?>'/> 
          <?php echo html::a($viewLink, sprintf('%03d', $case->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
        <td class='text-left' title="<?php echo $case->title?>">
          <?php if($modulePairs and $case->module)echo "<span title='{$lang->testcase->module}' class='label label-info label-badge'>{$modulePairs[$case->module]}</span> ";?>
          <?php echo html::a($viewLink, $case->title, null, "style='color: $case->color'");?>
        </td>
        <td><?php echo $lang->testcase->typeList[$case->type];?></td>
        <td><?php echo $users[$case->openedBy];?></td>
        <td class='<?php if(isset($run)) echo $run->status;?> testcase-<?php echo $case->status?>'> <?php echo $lang->testcase->statusList[$case->status];?>
        </td>
        <td>
          <?php
          if($config->testcase->needReview or !empty($config->testcase->forceReview)) common::printIcon('testcase', 'review',  "caseID=$case->id", $case, 'list', 'review', '', 'iframe');
          common::printIcon('testcase',  'edit',    "caseID=$case->id", $case, 'list');
          if(common::hasPriv('testcase', 'delete'))
          {
              $deleteURL = $this->createLink('testcase', 'delete', "caseID=$case->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"caseList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->testcase->delete}' class='btn-icon'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <?php endif;?>
      <tfoot>
        <tr>
          <td colspan='7'>
            <?php if($cases):?>
            <div class='table-actions clearfix'>
              <?php echo html::selectButton();?>
              <div class='btn-group dropup'>
                <?php
                $class = "class='disabled'";

                $actionLink = $this->createLink('testcase', 'batchEdit', "libID=$libID&branch=0&type=lib");
                $misc       = common::hasPriv('testcase', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
                echo html::commonButton($lang->edit, $misc);
                ?>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
                <ul class='dropdown-menu' id='moreActionMenu'>
                  <?php 
                  $actionLink = $this->createLink('testcase', 'batchDelete', "libID=$libID");
                  $misc = common::hasPriv('testcase', 'batchDelete') ? "onclick=\"confirmBatchDelete('$actionLink')\"" : $class;
                  echo "<li>" . html::a('#', $lang->delete, '', $misc) . "</li>";

                  if(common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview)))
                  {
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->testcase->review, '', "id='reviewItem'");
                      echo "<ul class='dropdown-menu'>";
                      unset($lang->testcase->reviewResultList['']);
                      foreach($lang->testcase->reviewResultList as $key => $result)
                      {
                          $actionLink = $this->createLink('testcase', 'batchReview', "result=$key");
                          echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                      }
                      echo '</ul></li>';
                  }
                  elseif($config->testcase->needReview or !empty($config->testcase->forceReview))
                  {
                      echo '<li>' . html::a('javascript:;', $lang->testcase->review,  '', $class) . '</li>';
                  }

                  if(common::hasPriv('testcase', 'batchChangeModule'))
                  {
                      $withSearch = count($modules) > 8;
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->testcase->moduleAB, '', "id='moduleItem'");
                      echo "<div class='dropdown-menu" . ($withSearch ? ' with-search' : '') . "'>";
                      echo '<ul class="dropdown-list">';
                      foreach($modules as $moduleId => $module)
                      {
                          $actionLink = $this->createLink('testcase', 'batchChangeModule', "moduleID=$moduleId");
                          echo "<li class='option' data-key='$moduleID'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                      }
                      echo '</ul>';
                      if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                      echo '</div></li>';
                  }
                  else
                  {
                      echo '<li>' . html::a('javascript:;', $lang->testcase->moduleAB, '', $class) . '</li>';
                  }
                  ?>
                </ul>
              </div>
            </div>
            <?php endif?>
            <div class='text-right'><?php $pager->show();?></div>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$('#module' + moduleID).addClass('active'); 
$('#<?php echo $this->session->libBrowseType?>Tab').addClass('active');
if(flow == 'onlyTest')
{
    $('#modulemenu > .nav > li.right').before($('#featurebar .submenu').html());
    toggleSearch();

    $('#modulemenu > .nav > li').removeClass('active');
    $('#modulemenu > .nav > li[data-id=' + browseType + ']').addClass('active');
}
</script>
<?php include '../../common/view/footer.html.php';?>
