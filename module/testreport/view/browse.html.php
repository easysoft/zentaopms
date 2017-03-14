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
<div class='container mw-1400px'>
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
        <th class='w-250px'><?php common::printOrderLink('objectID',    $orderBy, $vars, $lang->testreport->objectID);?></th>
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
        <?php
        $objectName = '';
        if($report->objectType == 'testtask' and isset($tasks[$report->objectID]))   $objectName = 'TESTTAST #' . $report->objectID . $tasks[$report->objectID];
        if($report->objectType == 'project' and isset($projects[$report->objectID])) $objectName = 'PROJECT #' . $report->objectID . $projects[$report->objectID];
        ?>
        <td class='text-left' title='<?php echo $objectName?>'><?php echo $objectName;?></td>
        <td><?php common::printIcon('testreport', 'delete', "id=$report->id", '', 'list', 'remove', 'hiddenwin');?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <?php endif;?>
    <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
