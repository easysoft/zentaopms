<?php
/**
 * The execution view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
    echo html::a(inlink('contribute', "mode=execution&type=undone&orderBy=id_desc&recTotal=0&recPerPage={$pager->recPerPage}"),  "<span class='text'>{$lang->my->executionMenu->undone}</span>" . ($type == 'undone' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'undone' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('contribute', "mode=execution&type=done&orderBy=id_desc&recTotal=0&recPerPage={$pager->recPerPage}"),  "<span class='text'>{$lang->my->executionMenu->done}</span>" . ($type == 'done' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'done' ? ' btn-active-text' : '') . "'");
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
  <table class="table has-sort-head table-fixed" id='projectList'>
    <thead>
      <tr class='text-left'>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='c-name text-left'><?php echo $lang->my->name;?></th>
        <th class='c-name text-left'><?php echo $lang->my->projects;?></th>
        <th class='c-date'><?php echo $lang->project->begin;?></th>
        <th class='c-date'><?php echo $lang->project->end;?></th>
        <th class='c-status'><?php echo $lang->statusAB;?></th>
        <th class='c-user'><?php echo $lang->team->role;?></th>
        <th class='c-date'><?php echo $lang->team->join;?></th>
        <th class='w-110px'><?php echo $lang->team->hours;?></th>
        <th class='w-60px'><?php echo $lang->project->waitTasks;?></th>
        <th class='c-progress'><?php echo $lang->project->progress;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($executions as $execution):?>
      <?php $link = $this->createLink('project', 'browse', "id=$execution->id", '', '', $execution->project);?>
      <tr class='text-left'>
        <td><?php echo html::a($link, sprintf('%03d', $execution->id));?></td>
        <td class='c-name text-left'>
          <span class='project-type-label label label-info label-outline'>
            <?php echo zget($lang->project->typeList, $execution->type);?>
          </span>
          <?php echo html::a($link, $execution->name, '', "title='$execution->name'");?>
        </td>
        <td class='c-name text-left'><?php echo html::a($this->createLink('project', 'browse', "id=$execution->project", '', '', $execution->project), $execution->projectName, '', "title='$execution->projectName'");?></td>
        <td><?php echo $execution->begin;?></td>
        <td><?php echo $execution->end;?></td>
        <td class="c-status">
          <?php if(isset($execution->delay)):?>
          <span class="status-project status-delayed" title='<?php echo $lang->project->delayed;?>'> <?php echo $lang->project->delayed;?></span>
          <?php else:?>
          <?php $typeName = $this->processStatus('project', $execution);?>
          <span class="status-project status-<?php echo $execution->status?>" title='<?php echo $typeName;?>'> <?php echo $typeName;?></span>
          <?php endif;?>
        </td>
        <td><?php echo $execution->role;?></td>
        <td><?php echo $execution->join;?></td>
        <td><?php echo $execution->hours;?></td>
        <td><?php echo $execution->waitTasks;?></td>
        <td><?php echo "<span class='pie-icon' data-percent='{$execution->progress}' data-border-color='#ddd' data-back-color='#f1f1f1'></span> {$execution->progress}%";?></td>
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
