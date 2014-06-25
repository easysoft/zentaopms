<?php
/**
 * The bug view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: bug.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <?php echo html::icon($lang->icons['bug']);?> <?php echo $lang->project->bug;?>
    <?php if($build) echo ' <span class="label label-danger">Build:' . $build->name . '</span>'; ?>
  </div>
  <div class='actions'>
    <?php common::printIcon('bug', 'create', "productID=$productID&extra=projectID=$project->id");?>
  </div>
</div>
<table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='bugList'>
  <thead>
    <tr>
      <?php $vars = "projectID={$project->id}&orderBy=%s&build=$buildID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <th class='w-id'>      <?php common::printOrderLink('id',           $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-severity'><?php common::printOrderLink('severity',     $orderBy, $vars, $lang->bug->severityAB);?></th>
      <th class='w-pri'>     <?php common::printOrderLink('pri',          $orderBy, $vars, $lang->priAB);?></th>
      <th>                   <?php common::printOrderLink('title',        $orderBy, $vars, $lang->bug->title);?></th>
      <th class='w-user'>    <?php common::printOrderLink('openedBy',     $orderBy, $vars, $lang->openedByAB);?></th>
      <th class='w-user'>    <?php common::printOrderLink('assignedTo',   $orderBy, $vars, $lang->assignedToAB);?></th>
      <th class='w-user'>    <?php common::printOrderLink('resolvedBy',   $orderBy, $vars, $lang->bug->resolvedBy);?></th>
      <th class='w-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
      <th class='w-140px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($bugs as $bug):?>
  <tr class='text-center'>
    <td><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->id, '_blank');?></td>
    <td><span class='<?php echo 'severity' . $lang->bug->severityList[$bug->severity]?>'><?php echo $lang->bug->severityList[$bug->severity]?></span></td>
    <td><span class='<?php echo 'pri' . $lang->bug->priList[$bug->pri]?>'><?php echo $lang->bug->priList[$bug->pri]?></span></td>
    <td class='text-left' title="<?php echo $bug->title?>"><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?></td>
    <td><?php echo zget($users, $bug->openedBy, $bug->openedBy);?></td>
    <td><?php echo zget($users, $bug->assignedTo, $bug->assignedTo);?></td>
    <td><?php echo zget($users, $bug->resolvedBy, $bug->resolvedBy);?></td>
    <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
    <td class='text-right'>
      <?php
      $params = "bugID=$bug->id";
      common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'search', '', 'iframe', true);
      common::printIcon('bug', 'assignTo',   $params, '',   'list', '', '', 'iframe', true);
      common::printIcon('bug', 'resolve',    $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
      common::printIcon('bug', 'edit',       $params, $bug, 'list');
      common::printIcon('bug', 'create',     "product=$bug->product&extra=bugID=$bug->id", $bug, 'list', 'copy');
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='9'><?php $pager->show();?></td></tr></tfoot>
</table>
<?php js::set('replaceID', 'bugList')?>
<?php include '../../common/view/footer.html.php';?>
