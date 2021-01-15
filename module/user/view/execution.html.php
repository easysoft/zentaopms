<?php
/**
 * The project view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: project.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<div id='mainContent'>
  <div class='main-table'>
    <table class='table has-sort-head table-fixed tablesorter'>
      <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->idAB;?></th>
          <th class="text-left"><?php echo $lang->user->name;?></th>
          <th class='w-date'><?php echo $lang->project->begin;?></th>
          <th class='w-date'><?php echo $lang->project->end;?></th>
          <th class='w-status'><?php echo $lang->statusAB;?></th>
          <th class='w-user'><?php echo $lang->team->role;?></th>
          <th class='w-date'><?php echo $lang->team->join;?></th>
          <th class='w-110px'><?php echo $lang->team->hours;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($executions as $execution):?>
        <?php $executionLink = $this->createLink('project', 'view', "projectID=$execution->id", '', false, $execution->project);?>
        <tr>
          <td><?php echo html::a($executionLink, $execution->id);?></td>
          <td>
            <span class='project-type-label label label-info label-outline'><?php echo zget($lang->user->executionTypeList, $execution->type);?></span>
            <?php echo html::a($executionLink, $execution->name);?>
          </td>
          <td><?php echo $execution->begin;?></td>
          <td><?php echo $execution->end;?></td>
          <?php if(isset($execution->delay)):?>
          <td class='project-delay'><?php echo $lang->project->delayed;?></td>
          <?php else:?>
          <td class='project-<?php echo $execution->status?>'><?php echo $this->processStatus('project', $execution);?></td>
          <?php endif;?>
          <td><?php echo $execution->role;?></td>
          <td><?php echo $execution->join;?></td>
          <td><?php echo $execution->hours;?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($executions):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
