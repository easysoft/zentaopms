<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sparkline.html.php';?>
<div id='featurebar'>
  <div class='actions'>
    <?php echo html::a($this->createLink('project', 'create'), "<i class='icon-plus'></i> " . $lang->project->create,'', "class='btn'") ?>
  </div>
  <ul class='nav'>
    <?php echo "<li id='undoneTab'>" . html::a(inlink("index", "locate=no&status=undone&projectID=$project->id"), $lang->project->undone) . '</li>';?>
    <?php echo "<li id='allTab'>" . html::a(inlink("index", "locate=no&status=all&projectID=$project->id"), $lang->project->all) . '</li>';?>
    <?php echo "<li id='waitTab'>" . html::a(inlink("index", "locate=no&status=wait&projectID=$project->id"), $lang->project->statusList['wait']) . '</li>';?>
    <?php echo "<li id='doingTab'>" . html::a(inlink("index", "locate=no&status=doing&projectID=$project->id"), $lang->project->statusList['doing']) . '</li>';?>
    <?php echo "<li id='suspendedTab'>" . html::a(inlink("index", "locate=no&status=suspended&projectID=$project->id"), $lang->project->statusList['suspended']) . '</li>';?>
    <?php echo "<li id='doneTab'>" . html::a(inlink("index", "locate=no&status=done&projectID=$project->id"), $lang->project->statusList['done']) . '</li>';?>
  </ul>
</div>
<form class='form-condensed' method='post' action='<?php echo inLink('batchEdit', "projectID=$projectID");?>'>
<table class='table table-fixed tablesorter'>
  <?php $vars = "locate=no&status=$status&projectID=$projectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
  <thead>
    <tr>
      <th class='w-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-200px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->project->name);?></th>
      <th><?php common::printOrderLink('code', $orderBy, $vars, $lang->project->code);?></th>
      <th><?php common::printOrderLink('PM', $orderBy, $vars, $lang->project->PM);?></th>
      <th><?php common::printOrderLink('end', $orderBy, $vars, $lang->project->end);?></th>
      <th><?php common::printOrderLink('status', $orderBy, $vars, $lang->project->status);?></th>
      <th><?php echo $lang->project->totalEstimate;?></th>
      <th><?php echo $lang->project->totalConsumed;?></th>
      <th><?php echo $lang->project->totalLeft;?></th>
      <th class='w-150px'><?php echo $lang->project->progess;?></th>
      <th class='w-100px'><?php echo $lang->project->burn;?></th>
    </tr>
  </thead>
  <?php $canBatchEdit = common::hasPriv('project', 'batchEdit'); ?>
  <?php foreach($projectStats as $project):?>
  <tr class='text-center'>
    <td>
      <?php if($canBatchEdit):?>
      <input type='checkbox' name='projectIDList[<?php echo $project->id;?>]' value='<?php echo $project->id;?>' /> 
      <?php endif;?>
      <?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), sprintf('%03d', $project->id));?>
    </td>
    <td class='text-left'><?php echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name);?></td>
    <td class='text-left'><?php echo $project->code;?></td>
    <td><?php echo $users[$project->PM];?></td>
    <td><?php echo $project->end;?></td>
    <td class='status-<?php echo $project->status?>'><?php echo $lang->project->statusList[$project->status];?></td>
    <td><?php echo $project->hours->totalEstimate;?></td>
    <td><?php echo $project->hours->totalConsumed;?></td>
    <td><?php echo $project->hours->totalLeft;?></td>
    <td class='text-left w-150px'>
      <div class="progressbar" style='width:<?php echo $project->hours->progress;?>px'>&nbsp;</div>
      <small><?php echo $project->hours->progress;?>%</small>
    </td>
    <td class='projectline text-left' values='<?php echo join(',', $project->burns);?>'></td>
  </tr>
  <?php endforeach;?>
  <?php if($canBatchEdit):?>
  <tfoot>
    <tr>
      <td colspan='11'>
        <div class='table-actions clearfix'>
        <?php echo "<div class='btn-group'>" . html::selectButton() . '</div>';?>
        <?php echo html::submitButton($lang->project->batchEdit);?>
        </div>
        <div class='text-right'><?php $pager->show();?></div>
      </td>
    </tr>
  </tfoot>
  <?php endif;?>
</table>
</form>
<script>$("#<?php echo $status;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
