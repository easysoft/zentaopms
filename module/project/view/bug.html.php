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
      <span class='text'><?php echo html::icon($lang->icons['bug']);?> <?php echo $lang->project->bug;?></span>
      <?php if($build) echo ' <span class="label label-danger">Build:' . $build->name . '</span>'; ?>
    </span>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printIcon('bug', 'export', "productID=$productID&orderBy=$orderBy", '', 'button', '', '', "export iframe");?>
    <?php common::printIcon('bug', 'create', "productID=$productID&branch=$branchID&extra=projectID=$project->id");?>
  </div>
</div>
<div id="mainContent" class='main-content'>
  <div id='queryBox' class='show'></div>
  <form class='main-table' method='post' id='projectBugForm' data-ride="table">
    <table class='table has-sort-head' id='bugList'>
      <thead>
        <tr>
          <?php $vars = "projectID={$project->id}&orderBy=%s&build=$buildID&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-80px'>    <?php common::printOrderLink('severity',     $orderBy, $vars, $lang->bug->severityAB);?></th>
          <th class='w-pri'>     <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
          <th>                   <?php common::printOrderLink('title',        $orderBy, $vars, $lang->bug->title);?></th>
          <th class='w-user'>    <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-user'>    <?php common::printOrderLink('assignedTo',   $orderBy, $vars, $lang->assignedToAB);?></th>
          <th class='w-user'>    <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedBy);?></th>
          <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
          <th class='w-150px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($bugs as $bug):?>
      <tr>
        <td class='c-id'>
          <div class="checkbox-primary">
            <input type='checkbox' name='bugIDList[]'  value='<?php echo $bug->id;?>'/> 
            <label></label>
            <?php printf('%03d', $bug->id);?>
          </div>
        </td>
        <td><span class='<?php echo 'severity' . zget($lang->bug->severityList, $bug->severity, $bug->severity)?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity)?></span></td>
        <td><span class='label-pri <?php echo 'label-pri-' . $bug->pri?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
        <td class='text-left' title="<?php echo $bug->title?>"><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, null, "style='color: $bug->color'");?></td>
        <td><?php echo zget($users, $bug->openedBy, $bug->openedBy);?></td>
        <td><?php echo zget($users, $bug->assignedTo, $bug->assignedTo);?></td>
        <td><?php echo zget($users, $bug->resolvedBy, $bug->resolvedBy);?></td>
        <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
        <td class='c-actions'>
          <?php $params = "bugID=$bug->id";?>
          <div class='more'>
            <?php
            common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'search', '', 'iframe', true);
            common::printIcon('bug', 'create',     "product=$bug->product&branch=$bug->branch&extra=bugID=$bug->id", $bug, 'list', 'copy');
            ?>
          </div>
          <?php
          common::printIcon('bug', 'assignTo',   $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
          common::printIcon('bug', 'edit',       $params, $bug, 'list');
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php if(count($bugs)):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php if(common::hasPriv('bug', 'batchAssignTo')):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->bug->assignedTo?> <span class="caret"></span></button>
          <?php 
          $withSearch = count($memberPairs) > 10;
          $actionLink = $this->createLink('bug', 'batchAssignTo', "projectID={$project->id}&type=project");
          echo "<div class='dropdown-menu search-list' data-ride='searchList'>";
          if($withSearch)
          {
              echo '<div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example">';
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
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php js::set('replaceID', 'bugList')?>
<?php include '../../common/view/footer.html.php';?>
