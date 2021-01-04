<?php
/**
 * The html template file of putInto method of personnel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.main-table thead>tr>th:first-child{padding-left: 0px;}
</style>
<div id="mainContent" class="main-row fade">
  <?php if(!empty($inputPersonnel)):?>
  <div class="main-col">
    <form class="main-table table-product" data-ride="table" method="post" action="">
      <table class="table has-sort-head table-fixed table-bordered text-center">
        <thead>
          <tr>
            <th rowspan='2' class="w-80px"><?php echo $lang->personnel->name;?></th>
            <th rowspan='2'><?php echo $lang->personnel->role;?></th>
            <th rowspan='2'><?php echo $lang->personnel->projects;?></th>
            <th rowspan='2'><?php echo $lang->personnel->executions;?></th>
            <th colspan="2"><?php echo $lang->personnel->workingHours;?></th>
            <th colspan="3"><?php echo $lang->personnel->task;?></th>
            <th colspan="3"><?php echo $lang->personnel->bug;?></th>
            <th colspan="2"><?php echo $lang->personnel->createStory;?></th>
            <th colspan="3"><?php echo $lang->personnel->issue;?></th>
            <th colspan="3"><?php echo $lang->personnel->risk;?></th>
          </tr>
          <tr>
            <th><?php echo $lang->personnel->putInto;?></th>
            <th><?php echo $lang->personnel->surplus;?></th>
            <th><?php echo $lang->personnel->created;?></th>
            <th><?php echo $lang->personnel->finished;?></th>
            <th><?php echo $lang->personnel->pending;?></th>
            <th><?php echo $lang->personnel->created;?></th>
            <th><?php echo $lang->personnel->resolved;?></th>
            <th><?php echo $lang->personnel->pending;?></th>
            <th><?php echo $lang->personnel->UR;?></th>
            <th><?php echo $lang->personnel->SR;?></th>
            <th><?php echo $lang->personnel->created;?></th>
            <th><?php echo $lang->personnel->resolved;?></th>
            <th><?php echo $lang->personnel->pending;?></th>
            <th><?php echo $lang->personnel->created;?></th>
            <th><?php echo $lang->personnel->resolved;?></th>
            <th><?php echo $lang->personnel->pending;?></th>
          </tr>
        </thead>
        <tbody class="sortable">
          <?php foreach($inputPersonnel as $personnel):?>
          <tr>
            <td title='<?php echo $personnel['realname'];?>'><?php echo $personnel['realname'];?></td>
            <td title='<?php echo $personnel['role'];?>'><?php echo $personnel['role'];?></td>
            <td><?php echo $personnel['projects'];?></td>
            <td><?php echo $personnel['executions'];?></td>
            <td><?php echo round($personnel['consumedTask'], 2);?></td>
            <td><?php echo round($personnel['leftTask'], 2);?></td>
            <td><?php echo $personnel['createdTask'];?></td>
            <td><?php echo $personnel['finishedTask'];?></td>
            <td><?php echo $personnel['pendingTask'];?></td>
            <td><?php echo $personnel['createdBug'];?></td>
            <td><?php echo $personnel['resolvedBug'];?></td>
            <td><?php echo $personnel['pendingBug'];?></td>
            <td><?php echo $personnel['UR'];?></td>
            <td><?php echo $personnel['SR'];?></td>
            <td><?php echo $personnel['createdIssue'];?></td>
            <td><?php echo $personnel['resolvedIssue'];?></td>
            <td><?php echo $personnel['pendingIssue'];?></td>
            <td><?php echo $personnel['createdRisk'];?></td>
            <td><?php echo $personnel['resolvedRisk'];?></td>
            <td><?php echo $personnel['pendingRisk'];?></td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
    </form>
  </div>
  <?php else:?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->personnel->emptyTip;?></span>
      </p>
    </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
