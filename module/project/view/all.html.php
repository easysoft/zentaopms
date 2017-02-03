<?php
/**
 * The html template file of all method of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<div id='featurebar'>
  <div class='actions'>
    <?php common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn'")?>
  </div>
  <ul class='nav'>
    <?php echo "<li id='undoneTab'>" . html::a(inlink("all", "status=undone&projectID=$project->id"), $lang->project->undone) . '</li>';?>
    <?php echo "<li id='allTab'>" . html::a(inlink("all", "status=all&projectID=$project->id"), $lang->project->all) . '</li>';?>
    <?php echo "<li id='waitTab'>" . html::a(inlink("all", "status=wait&projectID=$project->id"), $lang->project->statusList['wait']) . '</li>';?>
    <?php echo "<li id='doingTab'>" . html::a(inlink("all", "status=doing&projectID=$project->id"), $lang->project->statusList['doing']) . '</li>';?>
    <?php echo "<li id='suspendedTab'>" . html::a(inlink("all", "status=suspended&projectID=$project->id"), $lang->project->statusList['suspended']) . '</li>';?>
    <?php echo "<li id='doneTab'>" . html::a(inlink("all", "status=done&projectID=$project->id"), $lang->project->statusList['done']) . '</li>';?>
    <?php echo "<li>" . html::select('product', $products, $productID, "class='chosen' onchange='byProduct(this.value, $projectID)'") . '</li>';?>
  </ul>
</div>
<?php $canOrder = (common::hasPriv('project', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
<form id='projectsForm' class='form-condensed' method='post' action='<?php echo inLink('batchEdit', "projectID=$projectID");?>'>
<table class='table table-fixed tablesorter table-datatable table-selectable' id='projectList'>
  <?php $vars = "status=$status&projectID=$projectID&orderBy=%s&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
  <thead>
    <tr>
      <th class='w-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
      <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->project->name);?></th>
      <th class='w-100px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->project->code);?></th>
      <th class='w-90px'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->project->PM);?></th>
      <th class='w-80px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->project->end);?></th>
      <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->project->status);?></th>
      <th class='w-70px'><?php echo $lang->project->totalEstimate;?></th>
      <th class='w-70px'><?php echo $lang->project->totalConsumed;?></th>
      <th class='w-70px'><?php echo $lang->project->totalLeft;?></th>
      <th class='w-150px'><?php echo $lang->project->progess;?></th>
      <th class='w-100px'><?php echo $lang->project->burn;?></th>
      <?php if($canOrder):?>
      <th class='w-60px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->project->updateOrder);?></th>
      <?php endif;?>
    </tr>
  </thead>
  <?php $canBatchEdit = common::hasPriv('project', 'batchEdit'); ?>
  <tbody class='sortable' id='projectTableList'>
  <?php foreach($projectStats as $project):?>
  <tr class='text-center' data-id='<?php echo $project->id ?>' data-order='<?php echo $project->order ?>'>
    <td class='cell-id'>
      <?php if($canBatchEdit):?>
      <input type='checkbox' name='projectIDList[<?php echo $project->id;?>]' value='<?php echo $project->id;?>' /> 
      <?php endif;?>
      <?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), sprintf('%03d', $project->id));?>
    </td>
    <td class='text-left' title='<?php echo $project->name?>'><?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name);?></td>
    <td class='text-left'><?php echo $project->code;?></td>
    <td><?php echo $users[$project->PM];?></td>
    <td><?php echo $project->end;?></td>
    <?php if(isset($project->delay)):?>
    <td class='status-delay'><?php echo $lang->project->delayed;?></td>
    <?php else:?>
    <td class='status-<?php echo $project->status?>'><?php echo $lang->project->statusList[$project->status];?></td>
    <?php endif;?>
    <td><?php echo $project->hours->totalEstimate;?></td>
    <td><?php echo $project->hours->totalConsumed;?></td>
    <td><?php echo $project->hours->totalLeft;?></td>
    <td class='text-left w-150px'>
      <img class='progressbar' src='<?php echo $webRoot;?>theme/default/images/main/green.png' alt='' height='16' width='<?php echo $project->hours->progress == 0 ? 1 : round($project->hours->progress);?>'>
      <small><?php echo $project->hours->progress;?>%</small>
    </td>
    <td class='projectline text-left' values='<?php echo join(',', $project->burns);?>'></td>
    <?php if($canOrder):?>
    <td class='sort-handler'><i class="icon icon-move"></i></td>
    <?php endif;?>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='<?php echo $canOrder ? 12 : 11?>'>
        <div class='table-actions clearfix'>
          <?php if($canBatchEdit and !empty($projectStats)):?>
          <?php echo html::selectButton();?>
          <?php echo html::submitButton($lang->project->batchEdit);?>
          <?php endif;?>
          <?php if(!$canOrder and common::hasPriv('project', 'updateOrder')) echo html::a(inlink('all', "status=$status&projectID=$projectID&order=order_desc&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"), $lang->project->updateOrder, '', "class='btn'");?>
        </div>
        <?php $pager->show();?>
      </td>
    </tr>
  </tfoot>
</table>
</form>
<script>$("#<?php echo $status;?>Tab").addClass('active');</script>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
