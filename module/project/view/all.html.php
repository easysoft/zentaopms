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
<?php include '../../common/view/sortable.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php foreach($lang->project->featureBar['all'] as $key => $label):?>
    <?php echo html::a(inlink("all", "status=$key&projectID=$project->id&orderBy=$orderBy&productID=$productID"), "<span class='text'>{$label}</span>", '', "class='btn btn-link' id='{$key}Tab'");?>
    <?php endforeach;?>
    <div class='input-control space w-180px'>
      <?php echo html::select('product', $products, $productID, "class='chosen form-control' onchange='byProduct(this.value, $projectID, \"$status\")'");?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('project', 'export', "status=$status&productID=$productID&orderBy=$orderBy", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'")?>
    <?php common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn btn-primary'")?>
  </div>
</div>
<div id='mainContent'>
  <?php $canOrder = (common::hasPriv('project', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
  <form class='main-table' id='projectsForm' method='post' action='<?php echo inLink('batchEdit', "projectID=$projectID");?>' data-ride='table'>
    <table class='table has-sort-head table-fixed' id='projectList'>
      <?php $vars = "status=$status&projectID=$projectID&orderBy=%s&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->project->name);?></th>
          <th class='w-150px'><?php common::printOrderLink('code', $orderBy, $vars, $lang->project->code);?></th>
          <th class='thWidth'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->project->PM);?></th>
          <th class='w-90px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->project->end);?></th>
          <th class='w-90px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->project->status);?></th>
          <th class='w-70px'><?php echo $lang->project->totalEstimate;?></th>
          <th class='w-70px'><?php echo $lang->project->totalConsumed;?></th>
          <th class='w-70px'><?php echo $lang->project->totalLeft;?></th>
          <th class='w-150px'><?php echo $lang->project->progress;?></th>
          <th class='w-100px'><?php echo $lang->project->burn;?></th>
          <?php if($canOrder):?>
          <th class='w-60px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->project->orderAB);?></th>
          <?php endif;?>
        </tr>
      </thead>
      <?php $canBatchEdit = common::hasPriv('project', 'batchEdit'); ?>
      <tbody class='sortable' id='projectTableList'>
        <?php foreach($projectStats as $project):?>
        <tr data-id='<?php echo $project->id ?>' data-order='<?php echo $project->order ?>'>
          <td class='c-id'>
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='projectIDList[<?php echo $project->id;?>]' value='<?php echo $project->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $project->id);?>
          </td>
          <td class='text-left' title='<?php echo $project->name?>'>
            <?php
            if(isset($project->delay)) echo "<span class='label label-danger label-badge'>{$lang->project->delayed}</span> ";
            echo html::a($this->createLink('project', 'view', 'project=' . $project->id), $project->name);
            ?>
          </td>
          <td class='text-left' title="<?php echo $project->code;?>"><?php echo $project->code;?></td>
          <td><?php echo zget($users, $project->PM);?></td>
          <td><?php echo $project->end;?></td>
          <?php $projectStatus = $this->processStatus('project', $project);?>
          <td class='c-status' title='<?php echo $projectStatus;?>'>
            <span class="status-project status-<?php echo $project->status?>"><?php echo $projectStatus;?></span>
          </td>
          <td><?php echo $project->hours->totalEstimate;?></td>
          <td><?php echo $project->hours->totalConsumed;?></td>
          <td><?php echo $project->hours->totalLeft;?></td>
          <td class="c-progress">
            <div class="progress progress-text-left">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->hours->progress;?>%">
              <span class="progress-text"><?php echo $project->hours->progress;?>%</span>
              </div>
            </div>
          </td>
          <td id='spark-<?php echo $project->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $project->burns);?>'></td>
          <?php if($canOrder):?>
          <td class='sort-handler'><i class="icon icon-move"></i></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($projectStats):?>
    <div class='table-footer'>
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->project->batchEdit, '', 'btn');?></div>
      <?php endif;?>
      <?php if(!$canOrder and common::hasPriv('project', 'updateOrder')) echo html::a(inlink('all', "status=$status&projectID=$projectID&order=order_desc&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"), $lang->project->updateOrder, '', "class='btn'");?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<script>$("#<?php echo $status;?>Tab").addClass('btn-active-text');</script>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
