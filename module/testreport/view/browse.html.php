<?php
/**
 * The browse view file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <strong><?php echo $lang->testreport->browse;?></strong>
  </div>
</div>
<table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='reportList'>
  <?php $vars = "objectID=$objectID&objectType=$objectType&extra=$extra&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
  <thead>
    <tr>
      <th class='w-id'>   <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
      <th>                <?php common::printOrderLink('title',       $orderBy, $vars, $lang->testreport->title);?></th>
      <th class='w-user'> <?php common::printOrderLink('createdBy',   $orderBy, $vars, $lang->openedByAB);?></th>
      <th class='w-120px'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->testreport->createdDate);?></th>
      <th class='w-250px'><?php common::printOrderLink('project',     $orderBy, $vars, $lang->testreport->project);?></th>
      <th class='w-250px'><?php echo $lang->testreport->testtask;?></th>
      <th class='w-50px'> <?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <?php if($reports):?>
  <tbody class='text-center'>
    <?php foreach($reports as $report):?>
    <tr>
      <td><?php echo $report->id?></td>
      <td class='text-left' title='<?php $report->title?>'><?php echo html::a(inlink('view', "reportID=$report->id&from=$objectType"), $report->title)?></td>
      <td><?php echo zget($users, $report->createdBy);?></td>
      <td><?php echo substr($report->createdDate, 2);?></td>
      <?php $projectName = '#' . $report->project . $projects[$report->project];?>
      <td class='text-left' title='<?php echo $projectName?>'><?php echo $projectName;?></td>
      <?php
      $taskName = '';
      foreach(explode(',', $report->tasks) as $taskID) $taskName .= '#' . $taskID . $tasks[$taskID] . ' ';
      ?>
      <td class='text-left' title='<?php echo $taskName?>'><?php echo $taskName;?></td>
      <td>
        <?php
        common::printIcon('testreport', 'edit', "id=$report->id", '', 'list');
        common::printIcon('testreport', 'delete', "id=$report->id", '', 'list', 'remove', 'hiddenwin');
        ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='7'><?php $pager->show();?></td></tr></tfoot>
  <?php else:?>
  <tbody><tr><td colspan='7'><?php echo $lang->testreport->noReport;?></td></tr></tbody>
  <?php endif;?>
</table>
<?php include '../../common/view/footer.html.php';?>
