<?php
/**
 * The bug view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: bug.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'>
      <span class='text'><?php echo $lang->bug->allBugs;?></span>
      <span class="label label-light label-badge"><?php echo $pager->recTotal;?></span>
      <?php if($build) echo ' <span class="label label-danger">Build:' . $build->name . '</span>';?>
    </span>
    <a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> <?php echo $lang->bug->search;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('bug', 'export', "productID=$productID&orderBy=$orderBy", "<i class='icon icon-export muted'> </i>" . $lang->bug->export, '', "class='btn btn-link export'");?>
    <?php common::printLink('bug', 'create', "productID=$productID&branch=$branchID&extra=projectID=$project->id", "<i class='icon icon-plus'></i> " . $lang->bug->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent">
  <div class="cell" id="queryBox"></div>
  <?php if(empty($bugs)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->bug->noBug;?></span>
      <?php if(common::hasPriv('bug', 'create')):?>
      <span class="text-muted"><?php echo $lang->youCould;?></span>
      <?php echo html::a($this->createLink('bug', 'create', "productID=$productID&branch=$branchID&extra=projectID=$project->id"), "<i class='icon icon-plus'></i> " . $lang->bug->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' method='post' id='projectBugForm' data-ride="table">
    <table class='table has-sort-head' id='bugList'>
      <?php $canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');?>
      <?php $vars = "projectID={$project->id}&orderBy=%s&build=$buildID&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <thead>
        <tr>
          <th class='c-id'>
            <?php if($canBatchAssignTo):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-80px'>    <?php common::printOrderLink('severity',     $orderBy, $vars, $lang->bug->severityAB);?></th>
          <th class='c-pri'>     <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
          <th>                   <?php common::printOrderLink('title',        $orderBy, $vars, $lang->bug->title);?></th>
          <th class='w-user'>    <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-110px'>   <?php common::printOrderLink('assignedTo',   $orderBy, $vars, $lang->assignedToAB);?></th>
          <th class='w-user'>    <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedBy);?></th>
          <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
          <th class='c-actions text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($bugs as $bug):?>
      <tr>
        <td class='cell-id'>
          <?php if($canBatchAssignTo):?>
          <?php echo html::checkbox('bugIDList', array($bug->id => sprintf('%03d', $bug->id)));?>
          <?php else:?>
          <?php printf('%03d', $bug->id);?>
          <?php endif;?>
        </td>
        <td><span class='<?php echo 'label-severity';?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>' data-severity='<?php echo $bug->severity;?>'></span></td>
        <td><span class='label-pri <?php echo 'label-pri-' . $bug->pri?>' title='<?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
        <td class='text-left' title="<?php echo $bug->title?>"><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, null, "style='color: $bug->color'");?></td>
        <td><?php echo zget($users, $bug->openedBy, $bug->openedBy);?></td>
        <td class='c-assignedTo has-btn text-left'>
          <?php $assignedTo = zget($users, $bug->assignedTo, $bug->assignedTo);?>
          <?php $params = "bugID=$bug->id";?>
          <?php $class = $bug->assignedTo == $this->app->user->account ? 'text-red' : 'text-primary';?>
          <?php if(common::hasPriv('bug', 'assignTo')):?>
          <?php echo html::a($this->createLink('bug', 'assignTo', $params, '', 'true'), "<i class='icon icon-hand-right'></i> <span class='$class'>$assignedTo</span>", '', "class='iframe btn btn-icon-left btn-sm'");?>
          <?php else:?>
          <?php echo $assignedTo;?>
          <?php endif;?>
        </td>
        <td><?php echo zget($users, $bug->resolvedBy, $bug->resolvedBy);?></td>
        <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
        <td class='c-actions'>
          <?php
          if($bug->status == 'active') common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'confirm', '', 'iframe', true);
          if($bug->status == 'active') common::printIcon('bug', 'resolve', $params, $bug, 'list', 'check', '', 'iframe', true);
          if($bug->status != 'active') common::printIcon('bug', 'close',   $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'create', "product=$bug->product&branch=$bug->branch&extra=$params", $bug, 'list', 'copy');
          common::printIcon('bug', 'edit',   $params, $bug, 'list');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($canBatchAssignTo):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->bug->assignedTo?> <span class="caret"></span></button>
          <?php 
          $withSearch = count($memberPairs) > 10;
          $actionLink = $this->createLink('bug', 'batchAssignTo', "projectID={$project->id}&type=project");
          echo "<div class='dropdown-menu search-list' data-ride='searchList'>";
          if($withSearch)
          {
              echo '<div class="input-control search-box has-icon-left has-icon-right search-example">';
              echo '<input id="userSearchBox" type="search" class="form-control search-input" autocomplete="off" />';
              echo '<label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>';
              echo '<a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>';
              echo '</div>';
          }
          echo '<div class="list-group">';
          foreach($memberPairs as $key => $value)
          {
              if(empty($key)) continue;
              echo html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\")", $value, '', "data-key='@$key'");
          }
          echo "</div>";
          echo "</div>";
          ?>
        </div>
      </div>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('replaceID', 'bugList');?>
<?php js::set('browseType', $type);?>
<?php include '../../common/view/footer.html.php';?>
