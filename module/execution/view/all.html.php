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
.table th.hours {padding-right: 8px !important;}
.main-table tbody > tr.table-children > td:first-child::before {width: 3px;}
td.hours {text-align: right; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
@-moz-document url-prefix() {.main-table tbody > tr.table-children > td:first-child::before {width: 4px;}}
</style>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=$key&projectID=$projectID&orderBy=$orderBy&productID=$productID"), "<span class='text'>{$label}</span>", '', "class='btn btn-link' id='{$key}Tab' data-app='$from'");?>
    <?php endforeach;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'export', "status=$status&productID=$productID&orderBy=$orderBy&from=$from", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'")?>
    <?php if(common::hasPriv('execution', 'create')) echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . ((($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-primary' data-app='$from'");?>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $from == 'execution' ? $lang->execution->noExecutions : $lang->execution->noExecution;?></span>
      <?php if(common::hasPriv('execution', 'create')):?>
      <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . ((($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->createExec : $lang->execution->create), '', "class='btn btn-info' data-app='$from'");?>
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
          <th><?php common::printOrderLink('name', $orderBy, $vars, (($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->execName : $lang->execution->name);?></th>
          <th class='thWidth'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->execution->owner);?></th>
          <th class='w-80px'><?php common::printOrderLink('end', $orderBy, $vars, $lang->execution->end);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $from == 'execution' ? $lang->execution->execStatus : $lang->execution->status);?></th>
          <th class='w-70px text-right hours'><?php echo $lang->execution->totalEstimate;?></th>
          <th class='w-70px text-right hours'><?php echo $lang->execution->totalConsumed;?></th>
          <th class='w-70px text-right hours'><?php echo $lang->execution->totalLeft;?></th>
          <th class='w-60px'><?php echo $lang->execution->progress;?></th>
          <th class='w-100px'><?php echo $lang->execution->burn;?></th>
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
          <td class='text-left <?php if(!empty($execution->children)) echo 'has-child';?>' title='<?php echo $execution->name?>'>
            <?php
            if(isset($execution->delay)) echo "<span class='label label-danger label-badge'>{$lang->execution->delayed}</span> ";
            echo !empty($execution->children) ? $execution->name : html::a($this->createLink('execution', 'task', 'execution=' . $execution->id), $execution->name);
            ?>
            <?php if(!empty($execution->children)):?>
              <a class="plan-toggle" data-id="<?php echo $execution->id;?>"><i class="icon icon-angle-double-right"></i></a>
            <?php endif;?>
          </td>
          <td><?php echo zget($users, $execution->PM);?></td>
          <td><?php echo $execution->end;?></td>
          <?php $executionStatus = $this->processStatus('execution', $execution);?>
          <td class='c-status text-center' title='<?php echo $executionStatus;?>'>
            <span class="status-execution status-<?php echo $execution->status?>"><?php echo $executionStatus;?></span>
          </td>
          <td class='hours' title='<?php echo $execution->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalEstimate . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalConsumed . $this->lang->execution->workHourUnit;?></td>
          <td class='hours' title='<?php echo $execution->hours->totalLeft     . ' ' . $this->lang->execution->workHour;?>'><?php echo $execution->hours->totalLeft     . $this->lang->execution->workHourUnit;?></td>
          <td class="c-progress">
            <div class='progress-pie' data-doughnut-size='90' data-color='#00da88' data-value='<?php echo $execution->hours->progress;?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
              <div class='progress-info'><?php echo $execution->hours->progress;?></div>
            </div>
          </td>
          <td id='spark-<?php echo $execution->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $execution->burns);?>'></td>
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
               if(isset($child->delay)) echo "<span class='label label-danger label-badge'>{$lang->execution->delayed}</span> ";
               echo "<span class='label label-badge label-light' title='{$lang->programplan->children}'>{$lang->programplan->childrenAB}</span>";
               echo html::a($this->createLink('execution', 'task', 'execution=' . $child->id), $child->name);
               ?>
             </td>
             <td><?php echo zget($users, $child->PM);?></td>
             <td><?php echo $child->end;?></td>
             <?php $executionStatus = $this->processStatus('execution', $child);?>
             <td class='c-status' title='<?php echo $executionStatus;?>'>
               <span class="status-execution status-<?php echo $child->status?>"><?php echo $executionStatus;?></span>
             </td>
             <td class='hours' title='<?php echo $child->hours->totalEstimate . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalEstimate . ' ' . $this->lang->execution->workHourUnit;?></td>
             <td class='hours' title='<?php echo $child->hours->totalConsumed . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalConsumed . ' ' . $this->lang->execution->workHourUnit;?></td>
             <td class='hours' title='<?php echo $child->hours->totalLeft     . ' ' . $this->lang->execution->workHour;?>'><?php echo $child->hours->totalLeft     . ' ' . $this->lang->execution->workHourUnit;?></td>
             <td class="c-progress">
               <div class='progress-pie' data-doughnut-size='90' data-color='#00da88' data-value='<?php echo $child->hours->progress;?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                 <div class='progress-info'><?php echo $child->hours->progress;?></div>
               </div>
             </td>
             <td id='spark-<?php echo $child->id?>' class='sparkline text-left no-padding' values='<?php echo join(',', $child->burns);?>'></td>
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
</script>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
