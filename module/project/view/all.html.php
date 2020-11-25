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
<style>
.table-children {border-left: 2px solid #cbd0db; border-right: 2px solid #cbd0db;}
.table tbody > tr.table-children.table-child-top {border-top: 2px solid #cbd0db;}
.table tbody > tr.table-children.table-child-bottom {border-bottom: 2px solid #cbd0db;}
.table td.has-child > a:not(.plan-toggle) {max-width: 90%; max-width: calc(100% - 30px); display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.table td.has-child > .plan-toggle {color: #838a9d; position: relative; top: 1px;}
.table td.has-child > .plan-toggle:hover {color: #006af1; cursor: pointer;}
.table td.has-child > .plan-toggle > .icon {font-size: 16px; display: inline-block; transition: transform .2s; -ms-transform:rotate(-90deg); -moz-transform:rotate(-90deg); -o-transform:rotate(-90deg); -webkit-transform:rotate(-90deg); transform: rotate(-90deg);}
.table td.has-child > .plan-toggle > .icon:before {text-align: left;}
.table td.has-child > .plan-toggle.collapsed > .icon {-ms-transform:rotate(90deg); -moz-transform:rotate(90deg); -o-transform:rotate(90deg); -webkit-transform:rotate(90deg); transform: rotate(90deg);}
.main-table tbody > tr.table-children > td:first-child::before {width: 3px;}
@-moz-document url-prefix() {.main-table tbody > tr.table-children > td:first-child::before {width: 4px;}}
</style>
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
  </div>
</div>
<div id='mainContent'>
  <?php $canOrder = (common::hasPriv('project', 'updateOrder') and strpos($orderBy, 'order') !== false)?>
  <?php $canBatchEdit = common::hasPriv('project', 'batchEdit'); ?>
  <form class='main-table' id='projectsForm' method='post' action='<?php echo inLink('batchEdit', "projectID=$projectID");?>' data-ride='table'>
    <table class='table has-sort-head table-fixed' id='projectList'>
      <?php $vars = "status=$status&projectID=$projectID&orderBy=%s&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-id'>
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
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
      <tbody class='sortable' id='projectTableList'>
        <?php foreach($executionStats as $execution):?>
        <tr data-id='<?php echo $execution->id ?>' data-order='<?php echo $execution->order ?>'>
          <td class='c-id'>
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='projectIDList[<?php echo $execution->id;?>]' value='<?php echo $execution->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $execution->id);?>
          </td>
          <td class='text-left <?php if(!empty($execution->children)) echo 'has-child';?>' title='<?php echo $execution->name?>'>
            <?php
            if(isset($execution->delay)) echo "<span class='label label-danger label-badge'>{$lang->project->delayed}</span> ";
            echo !empty($execution->children) ? $execution->name : html::a($this->createLink('project', 'view', 'project=' . $execution->id), $execution->name);
            ?>
            <?php if(!empty($execution->children)):?>
              <a class="plan-toggle" data-id="<?php echo $execution->id;?>"><i class="icon icon-angle-double-right"></i></a>
            <?php endif;?>
          </td>
          <td class='text-left' title="<?php echo $execution->code;?>"><?php echo $execution->code;?></td>
          <td><?php echo zget($users, $execution->PM);?></td>
          <td><?php echo $execution->end;?></td>
          <?php $projectStatus = $this->processStatus('project', $execution);?>
          <td class='c-status' title='<?php echo $projectStatus;?>'>
            <span class="status-project status-<?php echo $execution->status?>"><?php echo $projectStatus;?></span>
          </td>
          <td><?php echo $execution->hours->totalEstimate;?></td>
          <td><?php echo $execution->hours->totalConsumed;?></td>
          <td><?php echo $execution->hours->totalLeft;?></td>
          <td class="c-progress">
            <div class="progress progress-text-left">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $execution->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $execution->hours->progress;?>%">
              <span class="progress-text"><?php echo $execution->hours->progress;?>%</span>
              </div>
            </div>
          </td>
          <td id='spark-<?php echo $execution->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $execution->burns);?>'></td>
          <?php if($canOrder):?>
          <td class='sort-handler'><i class="icon icon-move"></i></td>
          <?php endif;?>
        </tr>
        <?php if(!empty($execution->children)):?>
         <?php $i = 0;?>
           <?php foreach($execution->children as $key => $child):?>
           <?php $class  = $i == 0 ? ' table-child-top' : '';?>
           <?php $class .= ($i + 1 == count($execution->children)) ? ' table-child-bottom' : '';?>
           <tr class='table-children<?php echo $class;?> parent-<?php echo $execution->id;?>' data-id='<?php echo $child->id?>'>
             <td class='c-id'>
               <?php if($canBatchEdit):?>
               <div class="checkbox-primary">
                 <input type='checkbox' name='projectIDList[<?php echo $child->id;?>]' value='<?php echo $child->id;?>' />
                 <label></label>
               </div>
               <?php endif;?>
               <?php printf('%03d', $child->id);?>
             </td>
             <td class='text-left' title='<?php echo $child->name?>'>
               <?php
               if(isset($child->delay)) echo "<span class='label label-danger label-badge'>{$lang->project->delayed}</span> ";
               echo "<span class='label label-badge label-light' title='{$lang->programplan->children}'>{$lang->programplan->childrenAB}</span>";
               echo html::a($this->createLink('project', 'view', 'project=' . $child->id), $child->name);
               ?>
             </td>
             <td class='text-left' title="<?php echo $child->code;?>"><?php echo $child->code;?></td>
             <td><?php echo zget($users, $child->PM);?></td>
             <td><?php echo $child->end;?></td>
             <?php $projectStatus = $this->processStatus('project', $child);?>
             <td class='c-status' title='<?php echo $projectStatus;?>'>
               <span class="status-project status-<?php echo $child->status?>"><?php echo $projectStatus;?></span>
             </td>
             <td><?php echo $child->hours->totalEstimate;?></td>
             <td><?php echo $child->hours->totalConsumed;?></td>
             <td><?php echo $child->hours->totalLeft;?></td>
             <td class="c-progress">
               <div class="progress progress-text-left">
                 <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $child->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $child->hours->progress;?>%">
                 <span class="progress-text"><?php echo $child->hours->progress;?>%</span>
                 </div>
               </div>
             </td>
             <td id='spark-<?php echo $child->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $child->burns);?>'></td>
             <?php if($canOrder):?>
             <td class='sort-handler'><i class="icon icon-move"></i></td>
             <?php endif;?>
           </tr>
           <?php $i ++;?>
           <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($executionStats):?>
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
<script>
$("#<?php echo $status;?>Tab").addClass('btn-active-text');
$(document).on('click', '.plan-toggle', function(e)
{
    var $toggle = $(this);
    var id      = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

    e.stopPropagation();
    e.preventDefault();
});
</script>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
