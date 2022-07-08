<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
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
  <form class='main-table' id='executionForm' method='post' action='<?php echo inLink('batchEdit');?>' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false' data-enable-empty-nested-row='true' data-replace-id='executionTableList' data-preserve-nested='true'>
    <div class="table-header fixed-right">
      <table class="table table-from has-sort-head table-fixed table-nested" id="executionList">
        <?php $vars = "status=$status&orderBy=%s";?>	
        <thead>
          <tr>
            <th class='table-nest-title'>
              <?php echo $lang->idAB;?>
            </th>
            <th class='c-progress'><?php echo $lang->programplan->name;?></th>
            <th class='c-progress'><?php echo $lang->execution->owner;?></th>
            <th class='c-progress'><?php echo $lang->programplan->status;?></th>
            <th class='c-progress'><?php echo $lang->project->progress;?></th>
            <th class='c-progress'><?php echo $lang->programplan->begin;?></th>
            <th class='c-progress'><?php echo $lang->programplan->end;?></th>
            <th class='c-progress'><?php echo $lang->task->estimateAB;?></th>
            <th class='c-progress'><?php echo $lang->task->consumedAB;?></th>
            <th class='c-progress'><?php echo $lang->task->leftAB;?> </th>
            <th class='c-progress'><?php echo $lang->execution->burn;?> </th>
            <th class='text-center c-actions-6'><?php echo $lang->actions;?></th> 
          </tr>
        </thead>
        <tbody id="executionTableList">
          <?php foreach($executionStats as $stage):?>
          <?php
          $trClass  = '';
          $trAttrs  = "data-id='$stage->id' data-order='$stage->order' data-parent='$stage->parent'";
          $trAttrs .= " data-nested='true'";
          $trClass .= $stage->parent == '0' ? ' is-top-level table-nest-child-hide' : ' table-nest-hide';
          ?>          
            <tr row-id = <?php echo $stage->id;?>  class="row-program table-nest-child-hide" >
	        <td class='text-left table-nest-title'>
              <?php echo $stage->id;?>
            </td>
            <td>
              <?php
                $icon = '';
                if( /* $stage->hasChild || $stage->hasTask*/ true )
                {
                    $icon = '';
                    $class = ' table-nest-toggle';
                }
              ?>
                <span id = <?php echo $stage->id;?> class="table-nest-icon icon <?php echo $class . $icon;?>"></span>
                <?php echo $stage->name;?>
            </td>
            <td><?php echo $stage->id;?></td>
            <td><?php echo $stage->name;?></td>
            <td><?php echo $stage->id;?></td>
            <td><?php echo $stage->name;?></td>
            <td><?php echo $stage->id;?></td>
            <td><?php echo $stage->name;?></td>
            <td><?php echo $stage->id;?></td>
            <td><?php echo $stage->name;?></td>
            <td><?php echo $stage->name;?></td>
            <td><?php echo $stage->name;?></td>
          </tr>
          <?php if(!empty($stage->tasks)):?>
          <?php foreach($stage->tasks as $task):?>
          <tr parent-id = <?php echo $stage->id;?> >
            <td><?php echo $task->id;?></td>
            <td><?php echo $task->name;?></td>
            <td><?php echo $task->id;?></td>
            <td><?php echo $task->name;?></td>
            <td><?php echo $task->id;?></td>
            <td><?php echo $task->name;?></td>
            <td><?php echo $task->id;?></td>
            <td><?php echo $task->name;?></td>
            <td><?php echo $task->id;?></td>
            <td><?php echo $task->name;?></td>
            <td><?php echo $task->name;?></td>
            <td><?php echo $task->name;?></td>
          </tr>
          <?php endforeach;?>
          <tr type = "load-btn-row" parent-id = <?php echo $stage->id;?> >
            <td></td>
            <td>
              <span parent-id = <?php echo $stage->id;?> class = "load-btn" current-page = 1 >加载更多...</span>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <nav class="btn-toolbar pull-right setting"></nav>
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
    $("#executionList").on('click','.table-nest-icon',function(e)
    {   /* add event when click expand button */ 
        var that = e.target;
        var $trSelected = $(`tr[row-id = ${$(that).attr('id')}]`);
        if ($trSelected.hasClass("table-nest-child-hide"))
        {    /* table expand */
             var executionID = $(this).attr('id');
             var currentPage = 1;
             var pageSize    = 50;
             var link = createLink('task', 'ajaxGetTasksByExecution', 'executionID=' + executionID + '&currentPage=' + currentPage + '&pageSize=' +      pageSize);
             $.get(link, function(data)
             {
                 var newTasks = JSON.parse(data);
                 $trSelected.after("<tr></tr>");
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
    $("#executionList").on('click','.load-btn', function(e)
        {
            var that = e.target;
            var executionID = $(this).attr('parent-id');
            var currentPage = $(this).attr('current-page') + 1;
            var pageSize    = 50;
            var link = createLink('task', 'ajaxGetTasksByExecution', 'executionID=' + executionID + '&currentPage=' + currentPage + '&pageSize=' + pageSize);
        })
});
</script>
<?php include '../../common/view/footer.html.php';?>
