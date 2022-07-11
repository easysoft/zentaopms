<?php include '../../common/view/header.html.php';?>
<?php js::set('projectID', $projectID);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='btn-group'>
      <?php $viewName = $productID != 0 ? zget($productList,$productID) : $lang->product->allProduct;?>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
          $class = '';
          if($productID == 0) $class = 'class="active"';
          echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&projectID=$projectID&orderby=$orderBy"), $lang->product->allProduct) . "</li>";
          foreach($productList as $key => $product)
          {
              $class = $productID == $key ? 'class="active"' : '';
              echo "<li $class>" . html::a($this->createLink('project', 'execution', "status=$status&projectID=$projectID&orderby=$orderBy&productID=$key"), $product) . "</li>";
          }
        ?>
      </ul>
    </div>
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a($this->createLink('project', 'execution', "status=$key&projectID=$projectID&orderBy=$orderBy&productID=$productID"), $label, '', "class='btn btn-link' id='{$key}Tab'");?>
    <?php endforeach;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=project", "<i class='icon-export muted'> </i> " . $lang->export, '', "class='btn btn-link export'")?>
     <?php if(common::hasPriv('programplan', 'create') and $isStage):?>
     <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-primary'");?>
    <?php else: ?>
    <?php if(common::hasPriv('execution', 'create')) echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-primary create-execution-btn' data-app='execution' onclick='$(this).removeAttr(\"data-toggle\")'");?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->execution->noExecution;?></span>
      <?php if($status == "all"):?>
        <?php if(common::hasPriv('programplan', 'create') and $isStage):?>
        <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-info'");?>
        <?php else: ?>
          <?php if(common::hasPriv('execution', 'create')):?>
          <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-info' data-app='execution'");?>
          <?php endif;?>
        <?php endif;?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <?php $canBatchEdit = common::hasPriv('execution', 'batchEdit'); ?>
  <form class='main-table' id='executionForm' method='post' action='<?php echo inLink('batchEdit');?>' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false' data-enable-empty-nested-row='true' data-replace-id='executionTableList' data-preserve-nested='false'>
    <div class="table-header fixed-right">
      <table class="table table-from has-sort-head table-fixed table-nested" id="executionList">
        <?php $vars = "status=$status&orderBy=%s";?>	
        <thead>
          <tr>
            <th class='c-name'><?php echo $lang->programplan->name;?></th>
            <th class='c-user'><?php echo $lang->execution->owner;?></th>
            <th class='c-status'><?php echo $lang->project->status;?></th>
            <th class='c-hours'><?php echo $lang->project->progress;?></th>
            <th class='c-date'><?php echo $lang->programplan->begin;?></th>
            <th class='c-date'><?php echo $lang->programplan->end;?></th>
            <th class='c-hours'><?php echo $lang->task->estimateAB;?></th>
            <th class='c-hours'><?php echo $lang->task->consumedAB;?></th>
            <th class='c-hours'><?php echo $lang->task->leftAB;?> </th>
            <th class='c-progress'><?php echo $lang->execution->burn;?> </th>
            <th class='text-center c-actions-6'><?php echo $lang->actions;?></th> 
          </tr>
        </thead>
        <tbody id="executionTableList">
          <?php foreach($executionStats as $execution):?>
          <?php
          $trClass  = '';
          $trAttrs  = "data-id='$execution->id' data-order='$execution->order' data-nested='true'";
          $trClass .= !empty($execution->children) ? ' is-top-level table-nest-child-hide' : '';
          ?>
          <tr <?php echo $trAttrs;?> class="row-program <?php echo $trClass;?>">
            <td>
              <?php
                $icon = '';
                if( /* $execution->hasChild || $execution->hasTask*/ true )
                {
                    $icon = '';
                    $class = ' table-nest-toggle';
                }
              ?>
              <span id = <?php echo $execution->id;?> class="table-nest-icon icon <?php echo $class . $icon;?>"></span>
              <?php echo $execution->name;?>
            </td>
            <td><?php echo zget($users, $execution->PM);?></td>
            <td><?php echo zget($lang->project->statusList, $execution->status);?></td>
            <td><?php echo html::ring($execution->hours->progress); ?></td>
            <td><?php echo $execution->begin;?></td>
            <td><?php echo $execution->end;?></td>
            <td class='hours' title='<?php echo $execution->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalEstimate . $this->lang->execution->workHourUnit;?></td>
            <td class='hours' title='<?php echo $execution->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalConsumed . $this->lang->execution->workHourUnit;?></td>
            <td class='hours' title='<?php echo $execution->hours->totalLeft     . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalLeft     . $this->lang->execution->workHourUnit;?></td>
            <td id='spark-<?php echo $execution->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $execution->burns);?>'></td>
            <td></td>
          </tr>

          <?php if(!empty($execution->children)):?>
          <?php foreach($execution->children as $child):?>
          <?php
          $trClass  = '';
          $trAttrs  = "data-id={$child->id} data-parent={$child->parent}";
          $trClass .= " is-nest-child";
          if(empty($child->path)) $child->path = $execution->path . "$child->id,";
          $trAttrs .= " data-nest-parent='$child->parent' data-order='$child->order' data-nest-path='$child->path'";
          ?>
          <tr <?php echo $trAttrs;?> class='<?php echo $trClass;?>'>
            <td>
              <?php echo $child->name;?>
            </td>
            <td><?php echo zget($users, $child->PM);?></td>
            <td><?php echo zget($lang->project->statusList, $child->status);?></td>
            <td><?php echo html::ring($child->hours->progress); ?></td>
            <td><?php echo $child->begin;?></td>
            <td><?php echo $child->end;?></td>
            <td class='hours' title='<?php echo $child->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalEstimate . $this->lang->execution->workHourUnit;?></td>
            <td class='hours' title='<?php echo $child->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalConsumed . $this->lang->execution->workHourUnit;?></td>
            <td class='hours' title='<?php echo $child->hours->totalLeft . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalLeft . $this->lang->execution->workHourUnit;?></td>
            <td id='spark-<?php echo $child->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $child->burns);?>'></td>
            <td></td>
          </tr>
          <?php endforeach;?>

          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('status', $status)?>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('executionStats', $executionStats);?>
<script>
$("#<?php echo $status;?>Tab").addClass('btn-active-text');

$(function()
{
    $("#executionList").on('click','.showmore',function(e)
    {   /* add event when click expand button */
        var that = e.target;
        var $trSelected = $(`tr[data-id = ${$(that).attr('id')}]`);
        if ($trSelected.hasClass("table-nest-child-hide"))
        {    /* table expand */
            var executionID = $(this).attr('id');
            var currentPage = 1;
            var pageSize    = 50;
            var link = createLink('task', 'ajaxGetTasksByExecution', 'executionID=' + executionID + '&currentPage=' + currentPage + '&pageSize=' +      pageSize);
            $.get(link, function(data)
            {
                var newTasks = JSON.parse(data);
                $trSelected.after("<tr><td>111</td></tr>");
                /* if (total > 50)
                     * 在第五十条后加一个tr 包裹一个td 中间含有一个 <span class = 'load-btn'></span>
                     * 点击事件如下
                     * {
                     *     
                     * }
                    * */
                $trSelected.removeClass("table-nest-child-hide");
                $(`tr[parent-id = ${$(that).attr('id')} ]`).show();
                $('#executionForm').table('initNestedList');
            })
        }
        else
        {   /* table close */
            $trSelected.addClass("table-nest-child-hide");
            $(`tr[parent-id = ${$(that).attr('id')} ]`).hide();

        }
    })
    $("#executionList").on('click', '.showmore', function(e)
    {
        var that = e.target;
        var executionID = $(this).attr('parent-id');
        var currentPage = $(this).attr('current-page') + 1;
        var pageSize    = 50;
        var link = createLink('task', 'ajaxGetTasksByExecution', 'executionID=' + executionID + '&currentPage=' + currentPage + '&pageSize=' + pageSize);
    })
    /* var addTableRow = function(param)
    {

        console.log('addRow');
    }*/
});
</script>
<?php include '../../common/view/footer.html.php';?>
