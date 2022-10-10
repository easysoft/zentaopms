<?php
/**
 * The execution view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    echo html::a(inlink('execution', "type=undone&orderBy=id_desc&recTotal=0&recPerPage={$pager->recPerPage}"),  "<span class='text'>{$lang->my->executionMenu->undone}</span>" . ($type == 'undone' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'undone' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('execution', "type=done&orderBy=id_desc&recTotal=0&recPerPage={$pager->recPerPage}"),  "<span class='text'>{$lang->my->executionMenu->done}</span>" . ($type == 'done' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'done' ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($executions)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
    </p>
  </div>
  <?php else:?>
  <table class="table has-sort-head table-fixed" id='executionList'>
    <thead>
      <tr class='text-left'>
        <th class='c-id'><?php echo $lang->idAB;?></th>
        <th class='c-name text-left'><?php echo $lang->my->name;?></th>
        <th class='c-status'><?php echo $lang->statusAB;?></th>
        <th class='c-user' title='<?php echo $lang->team->roleAB;?>'><?php echo $lang->team->roleAB;?></th>
        <th class='c-user text-center'><?php echo $lang->execution->myTask;?></th>
        <th class='c-date'><?php echo $lang->execution->begin;?></th>
        <th class='c-date'><?php echo $lang->execution->end;?></th>
        <th class='c-date'><?php echo $lang->team->join;?></th>
        <th class='c-hours align-right'><?php echo $lang->my->hours;?></th>
        <th class='c-progress'><?php echo $lang->execution->progress;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($executions as $execution):?>
      <?php $link = $this->createLink('execution', 'browse', "id=$execution->id", '', '', $execution->project);?>
      <tr class='text-left'>
        <td><?php echo html::a($link, sprintf('%03d', $execution->id));?></td>
        <td class='c-name text-left'>
          <?php if($config->systemMode == 'new'):?>
          <?php
          if($execution->type === 'stage') echo "<span class='project-type-label label label-outline label-warning'>{$lang->project->stage}</span> ";
          if($execution->type === 'sprint') echo "<span class='project-type-label label label-outline label-info'>{$lang->executionCommon}</span> ";
          if($execution->type === 'kanban') echo "<span class='project-type-label label label-outline label-info'>{$lang->execution->kanban}</span> ";
          ?>
          <?php endif;?>
          <?php echo html::a($link, $execution->name, '', "title='$execution->name'");?>
        </td>
        <td class="c-status">
          <?php if(isset($execution->delay)):?>
          <span class="status-project status-delayed" title='<?php echo $lang->execution->delayed;?>'><?php echo $lang->execution->delayed;?></span>
          <?php else:?>
          <?php $typeName = $this->processStatus('project', $execution);?>
          <span class="status-project status-<?php echo $execution->status?>" title='<?php echo $typeName;?>'><?php echo $typeName;?></span>
          <?php endif;?>
        </td>
        <td><?php echo $execution->role;?></td>
        <td class="text-center"><?php echo $execution->assignedToMeTasks;?></td>
        <td class='c-date'><?php echo $execution->begin;?></td>
        <td class='c-date'><?php echo $execution->end;?></td>
        <td class='c-date'><?php echo $execution->join;?></td>
        <td class="text-right"><?php echo $execution->hours;?></td>
        <td>
          <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo $execution->progress;?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
            <div class='progress-info'><?php echo $execution->progress;?></div>
          </div>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class="table-footer">
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
