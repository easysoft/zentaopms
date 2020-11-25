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
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->my->myExecutions;?></span></span>
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
      <tr class='text-center'>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='c-name text-left'><?php echo $lang->my->name;?></th>
        <th class='w-160px text-left'><?php echo $lang->my->code;?></th>
        <th class='w-160px text-left'><?php echo $lang->typeAB;?></th>
        <th class='c-name text-left'><?php echo $lang->my->projects;?></th>
        <th class='c-date'><?php echo $lang->project->begin;?></th>
        <th class='c-date'><?php echo $lang->project->end;?></th>
        <th class='c-status'><?php echo $lang->statusAB;?></th>
        <th class='c-user'><?php echo $lang->team->role;?></th>
        <th class='c-date'><?php echo $lang->team->join;?></th>
        <th class='w-110px'><?php echo $lang->team->hours;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($executions as $execution):?>
      <?php $link = $this->createLink('project', 'browse', "id=$execution->id", '', '', $execution->project);?>
      <tr class='text-center'>
        <td><?php echo html::a($link, $execution->id);?></td>
        <td class='c-name text-left'><?php echo html::a($link, $execution->name);?></td>
        <td class='text-left'><?php echo $execution->code;?></td>
        <td class='text-left'><?php echo zget($lang->project->typeList, $execution->type);?></td>
        <td class='c-name text-left'><?php echo html::a($this->createLink('project', 'browse', "id=$execution->project", '', '', $execution->project), $execution->parentName, '', "title='$execution->parentName'");?></td>
        <td><?php echo $execution->begin;?></td>
        <td><?php echo $execution->end;?></td>
        <td class="c-status">
          <?php if(isset($execution->delay)):?>
          <span class="status-project status-delayed" title='<?php echo $lang->project->delayed;?>'> <?php echo $lang->project->delayed;?></span>
          <?php else:?>
          <?php $statusName = $this->processStatus('project', $execution);?>
          <span class="status-project status-<?php echo $execution->status?>" title='<?php echo $statusName;?>'> <?php echo $statusName;?></span>
          <?php endif;?>
        </td>
        <td><?php echo $execution->role;?></td>
        <td><?php echo $execution->join;?></td>
        <td><?php echo $execution->hours;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
