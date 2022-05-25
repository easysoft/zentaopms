<?php
/**
 * The html template file of all method of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     execution
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if($from == 'project'):?>
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
    <?php endif;?>
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=$key&projectID=$projectID&orderBy=$orderBy&productID=$productID"), $label, '', "class='btn btn-link' id='{$key}Tab' data-app='$from'");?>
    <?php endforeach;?>
    <?php if($from == 'execution' and $this->config->systemMode == 'new'):?>
    <div class='input-control w-180px'>
      <?php echo html::select('project', $projects, $projectID, "class='form-control chosen' data-placeholder='{$lang->execution->selectProject}'");?>
    </div>
    <?php endif;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=$from", "<i class='icon-export muted'> </i> " . $lang->export, '', "class='btn btn-link export'")?>
     <?php if(common::hasPriv('programplan', 'create') and $isStage):?>
     <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-primary'");?>
    <?php else: ?>
    <?php if(common::hasPriv('execution', 'create')) echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . ((($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-primary create-execution-btn' data-app='execution' onclick='$(this).removeAttr(\"data-toggle\")'");?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $from == 'execution' ? $lang->execution->noExecutions : $lang->execution->noExecution;?></span>
      <?php if(common::hasPriv('programplan', 'create') and $isStage):?>
      <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-info'");?>
      <?php else: ?>
      <?php if(common::hasPriv('execution', 'create')):?>
      <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . (($from == 'execution' and $config->systemMode == 'new') ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-info' data-app='execution'");?>
      <?php endif;?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <?php $canBatchEdit = common::hasPriv('execution', 'batchEdit'); ?>
  <form class='main-table' id='executionsForm' method='post' action='<?php echo inLink('batchEdit');?>' data-ride='table'>
    <table class='table has-sort-head table-fixed' id='executionList'>
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
          <th><?php common::printOrderLink('name', $orderBy, $vars, ($from == 'execution' and $config->systemMode == 'new') ? $lang->execution->execName : $lang->execution->name);?></th>
          <?php if(!$isStage):?>
          <th class='c-code'><?php common::printOrderLink('code', $orderBy, $vars, ($from == 'execution' and $config->systemMode == 'new') ? $lang->execution->execCode : $lang->execution->code);?></th>
          <?php endif;?>
          <?php if($config->systemMode == 'new' and $this->app->tab == 'execution'):?>
          <th class='c-begin'><?php common::printOrderLink('projectName', $orderBy, $vars, $lang->execution->projectName);?></th></th>
          <?php endif;?>
          <th class='c-pm'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->execution->owner);?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $from == 'execution' ? $lang->execution->execStatus : $lang->execution->status);?></th>
          <th class='c-progress'><?php echo $lang->execution->progress;?></th>
          <?php if($isStage):?>
          <th class='c-percent'><?php common::printOrderLink('percent', $orderBy, $vars, $lang->programplan->percent);?></th>
          <th class='c-attribute'><?php common::printOrderLink('attribute', $orderBy, $vars, $lang->programplan->attribute);?></th>
          <th class='c-begin'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->execution->begin);?></th>
          <th class='c-end'><?php common::printOrderLink('end', $orderBy, $vars, $lang->execution->end);?></th>
          <th class='c-realBegan'><?php common::printOrderLink('realBegan', $orderBy, $vars, $lang->execution->realBegan);?></th>
          <th class='c-realEnd'><?php common::printOrderLink('realEnd', $orderBy, $vars, $lang->execution->realEnd);?></th>
          <th class='c-action'><?php echo $lang->actions;?></th>
          <?php else:;?>
          <th class='c-begin'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->execution->begin);?></th>
          <th class='c-end'><?php common::printOrderLink('end', $orderBy, $vars, $lang->execution->end);?></th>
          <th class='c-estimate text-right hours'><?php echo $lang->execution->totalEstimate;?></th>
          <th class='c-consumed text-right hours'><?php echo $lang->execution->totalConsumed;?></th>
          <th class='c-left text-right hours'><?php echo $lang->execution->totalLeft;?></th>
          <?php endif;?>

          <?php if(!$isStage):?>
          <th class='c-burn'><?php echo $lang->execution->burn;?></th>
          <?php endif;?>
          <?php
          $extendFields = $this->execution->getFlowExtendFields();
          foreach($extendFields as $extendField) echo "<th rowspan='2'>{$extendField->name}</th>";
          ?>
        </tr>
      </thead>
      <tbody class='sortable' id='executionTableList'>
        <?php foreach($executionStats as $execution):?>
        <tr data-id='<?php echo $execution->id ?>' data-order='<?php echo $execution->order ?>'>
          <td class='c-id'>
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='executionIDList[<?php echo $execution->id;?>]' value='<?php echo $execution->id;?>' autocomplete='off' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $execution->id);?>
          </td>
          <?php
          $executionName  = $execution->name;
          $onlyChildStage = ($execution->grade == 2 and $execution->project != $execution->parent);
          if($onlyChildStage and isset($parents[$execution->parent])) $executionName = $parents[$execution->parent]->name . '/' . $executionName;
          ?>
          <td class='text-left c-name sort-handler <?php if(!empty($execution->children)) echo 'has-child';?> flex' title='<?php echo $executionName?>'>
            <?php if($config->systemMode == 'new'):?>
            <span class='project-type-label label label-outline <?php echo $execution->type == 'stage' ? 'label-warning' : 'label-info';?>'><?php echo $lang->execution->typeList[$execution->type]?></span>
            <?php endif;?>
            <?php
            $executionLink = $execution->projectModel == 'kanban' ? html::a($this->createLink('execution', 'kanban', 'executionID=' . $execution->id), $executionName, '', "class='text-ellipsis'") : html::a($this->createLink('execution', 'task', 'execution=' . $execution->id), $executionName, '', "class='text-ellipsis'");
            if($onlyChildStage) echo "<span class='label label-badge label-light label-children'>{$lang->programplan->childrenAB}</span> ";
            echo !empty($execution->children) ? $execution->name :  $executionLink;
            if(isset($execution->delay)) echo "<span class='label label-danger label-badge'>{$lang->execution->delayed}</span> ";
            ?>
            <?php if(!empty($execution->children)):?>
              <a class="plan-toggle" data-id="<?php echo $execution->id;?>"><i class="icon icon-angle-double-right"></i></a>
            <?php endif;?>
          </td>
          <?php if(!$isStage):?>
          <td title='<?php echo $execution->code;?>'><?php echo $execution->code;?></td>
          <?php endif;?>
          <?php if($config->systemMode == 'new' and $this->app->tab == 'execution'):?>
          <td class='c-begin' title='<?php echo $execution->projectName;?>'>
             <span class="status-execution status-<?php echo $execution->projectName?>"><?php echo $execution->projectName;?></span>
          </td>
          <?php endif;?>
          <td><?php echo zget($users, $execution->PM);?></td>
          <?php $executionStatus = $this->processStatus('execution', $execution);?>
          <td class='c-status text-center' title='<?php echo $executionStatus;?>'>
            <span class="status-execution status-<?php echo $execution->status?>"><?php echo $executionStatus;?></span>
          </td>
          <td class="c-progress">
            <?php echo html::ring($execution->hours->progress); ?>
          </td>
          <?php if($isStage):?>
          <td><?php echo $execution->percent . '%';?></td>
          <td><?php echo zget($lang->stage->typeList, $execution->attribute, '');?></td>
          <td><?php echo helper::isZeroDate($execution->begin)     ? '' : $execution->begin;?></td>
          <td><?php echo helper::isZeroDate($execution->end)       ? '' : $execution->end;?></td>
          <td><?php echo helper::isZeroDate($execution->realBegan) ? '' : $execution->realBegan;?></td>
          <td><?php echo helper::isZeroDate($execution->realEnd)   ? '' : $execution->realEnd;?></td>
          <td class="c-actions text-center c-actions">
            <?php
                common::printIcon('execution', 'start', "executionID={$execution->id}", $execution, 'list', '', '', 'iframe', true);
                $class = !empty($execution->children) ? 'disabled' : '';
                common::printIcon('task', 'create', "executionID={$execution->id}", $execution, 'list', '', '', $class, false, "data-app='execution'");

                if($execution->grade == 1 && $this->loadModel('programplan')->isCreateTask($execution->id))
                {
                    common::printIcon('programplan', 'create', "program={$execution->parent}&productID=$productID&planID=$execution->id", $execution, 'list', 'split', '', '', '', '', $this->lang->programplan->createSubPlan);
                }
                else
                {
                    $disabled = ($execution->grade == 2) ? ' disabled' : '';
                    echo common::hasPriv('programplan', 'create') ? html::a('javascript:alert("' . $this->lang->programplan->error->createdTask . '");', '<i class="icon-programplan-create icon-split"></i>', '', 'class="btn ' . $disabled . '"') : '';
                }

                common::printIcon('programplan', 'edit', "stageID=$execution->id&projectID=$projectID", $execution, 'list', '', '', 'iframe', true);

                $disabled = !empty($execution->children) ? ' disabled' : '';
                if($execution->status != 'closed' and common::hasPriv('execution', 'close', $execution))
                {
                    common::printIcon('execution', 'close', "stageID=$execution->id", $execution, 'list', 'off', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->close);
                }
                elseif($execution->status == 'closed' and common::hasPriv('execution', 'activate', $execution))
                {
                    common::printIcon('execution', 'activate', "stageID=$execution->id", $execution, 'list', 'magic', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->activate);
                }

                if(common::hasPriv('execution', 'delete', $execution))
                {
                    common::printIcon('execution', 'delete', "stageID=$execution->id&confirm=no", $execution, 'list', 'trash', 'hiddenwin' , $disabled, '', '', $this->lang->programplan->delete);
                }
            ?></td>
          <?php else:?>
          <td><?php echo helper::isZeroDate($execution->begin) ? '' : $execution->begin;?></td>
          <td><?php echo helper::isZeroDate($execution->end)   ? '' : $execution->end;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalEstimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalConsumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalLeft     . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalLeft     . $this->lang->execution->workHourUnit;?></td>
          <?php endif;?>

          <?php if(!$isStage):?>
          <td id='spark-<?php echo $execution->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $execution->burns);?>'></td>
          <?php endif;?>
          <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $execution) . "</td>";?>
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
                 <input type='checkbox' name='executionIDList[<?php echo $child->id;?>]' value='<?php echo $child->id;?>' />
                 <label></label>
               </div>
               <?php endif;?>
               <?php printf('%03d', $child->id);?>
             </td>
             <td class='text-left' title='<?php echo $child->name?>'>
               <?php
               echo "<span class='label label-badge label-light' title='{$lang->programplan->children}'>{$lang->programplan->childrenAB}</span>";
               echo html::a($this->createLink('execution', 'task', 'execution=' . $child->id), $child->name);
               if(isset($child->delay)) echo "<span class='label label-danger label-badge'>{$lang->execution->delayed}</span> ";
               ?>
             </td>
             <?php if($from == 'execution'): ?>
             <td title = '<?php echo $child->code;?>'><?php echo $child->code;?>
               <?php if($config->systemMode == 'new'):?>
                 <td title = '<?php echo $child->projectName?>'><?php echo $child->projectName;?>
               <?php endif;?>
             <?php endif;?>
             <td><?php echo zget($users, $child->PM);?></td>
             <?php $executionStatus = $this->processStatus('execution', $child);?>
             <td class='c-status text-center' title='<?php echo $executionStatus;?>'>
               <span class="status-execution status-<?php echo $child->status?>"><?php echo $executionStatus;?></span>
             </td>
             <td class="c-progress">
               <?php echo html::ring($child->hours->progress); ?>
             </td>
             <?php if($from == 'project' and $isStage):?>
             <td><?php echo $child->percent . '%';?></td>
             <td><?php echo zget($lang->stage->typeList, $child->attribute, '');?></td>
             <td><?php echo helper::isZeroDate($child->begin)     ? '' : $child->begin;?></td>
             <td><?php echo helper::isZeroDate($child->end)       ? '' : $child->end;?></td>
             <td><?php echo helper::isZeroDate($child->realBegan) ? '' : $child->realBegan;?></td>
             <td><?php echo helper::isZeroDate($child->realEnd)   ? '' : $child->realEnd;?></td>
             <td class="c-actions text-center c-actions">
                <?php
                  common::printIcon('execution', 'start', "executionID={$child->id}", $child, 'list', '', '', 'iframe', true);
                  $class = !empty($child->children) ? 'disabled' : '';
                  common::printIcon('task', 'create', "executionID={$child->id}", $child, 'list', '', '', $class, false, "data-app='execution'");

                  if($child->grade == 1 && $this->loadModel('programplan')->isCreateTask($child->id))
                  {
                      common::printIcon('programplan', 'create', "program={$child->parent}&productID=$productID&stageID=$child->id", $child, 'list', 'split', '', '', '', '', $this->lang->programplan->createSubPlan);
                  }
                  else
                  {
                      $disabled = ($child->grade == 2) ? ' disabled' : '';
                      echo html::a('javascript:alert("' . $this->lang->programplan->error->createdTask . '");', '<i class="icon-programplan-create icon-split"></i>', '', 'class="btn ' . $disabled . '"');
                  }

                  common::printIcon('programplan', 'edit', "stageID=$child->id&projectID=$projectID", $child, 'list', '', '', 'iframe', true);

                  $disabled = !empty($child->children) ? ' disabled' : '';
                  if(common::hasPriv('execution', 'close', $child) and $child->status != 'closed')
                  {
                      common::printIcon('execution', 'close', "stageID=$child->id", $child, 'list', 'off', '' , $disabled . ' iframe', true, '', $this->lang->programplan->close);
                  }
                  elseif(common::hasPriv('execution', 'activate', $child) and $child->status == 'closed')
                  {
                      common::printIcon('execution', 'activate', "stageID=$child->id", $child, 'list', 'magic', '' , $disabled . ' iframe', true, '', $this->lang->programplan->activate);
                  }

                  if(common::hasPriv('execution', 'delete', $child))
                  {
                      common::printIcon('execution', 'delete', "stageID=$child->id&confirm=no", $child, 'list', 'trash', 'hiddenwin' , $disabled, '', '', $this->lang->programplan->delete);
                  }
                ?>
             </td>
             <?php else:?>
             <td class='c-begin' title='<?php echo helper::isZeroDate($child->begin) ? '' : $child->begin;?>'><?php echo helper::isZeroDate($child->begin) ? '' : $child->begin;?></td>
             <td class='c-begin' title='<?php echo helper::isZeroDate($child->end) ? '' : $child->end;?>'><?php echo helper::isZeroDate($child->end) ? '' : $child->end;?></td>
             <td class='hours' title='<?php echo $child->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalEstimate . ' ' . $this->lang->execution->workHourUnit;?></td>
             <td class='hours' title='<?php echo $child->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalConsumed . ' ' . $this->lang->execution->workHourUnit;?></td>
             <td class='hours' title='<?php echo $child->hours->totalLeft     . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalLeft     . ' ' . $this->lang->execution->workHourUnit;?></td>
             <td id='spark-<?php echo $child->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $child->burns);?>'></td>
             <?php endif;?>
             <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $child) . "</td>";?>
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
      <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->execution->batchEdit, '', 'btn');?></div>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
  <?php endif;?>
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

$('#project').change(function()
{
    var projectID = $('#project').val();
    location.href = createLink('execution', 'all', 'status=' + status + '&projectID=' + projectID);
});
</script>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('status', $status)?>
<?php include '../../common/view/footer.html.php';?>
