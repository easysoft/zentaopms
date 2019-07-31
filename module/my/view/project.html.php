<?php
/**
 * The project view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: project.html.php 5095 2013-07-11 06:03:40Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->my->myProject;?></span></span>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('project', 'create')) echo html::a(helper::createLink('project', 'create'), "<i class='icon-plus'></i> " . $lang->my->home->createProject, '', "class='btn btn-primary'") ?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($projects)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->project->noProject;?></span>
      <?php if(common::hasPriv('project', 'create')):?>
      <?php echo html::a($this->createLink('project', 'create'), "<i class='icon icon-plus'></i> " . $lang->my->home->createProject, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table has-sort-head table-fixed" id='projectList'>
    <thead>
      <tr class='text-center'>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th class='w-160px text-left'><?php echo $lang->project->code;?></th>
        <th class='c-name text-left'><?php echo $lang->project->name;?></th>
        <th class='c-date'><?php echo $lang->project->begin;?></th>
        <th class='c-date'><?php echo $lang->project->end;?></th>
        <th class='c-status'><?php echo $lang->statusAB;?></th>
        <th class='c-user'><?php echo $lang->team->role;?></th>
        <th class='c-date'><?php echo $lang->team->join;?></th>
        <th class='w-110px'><?php echo $lang->team->hours;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($projects as $project):?>
      <?php $projectLink = $this->createLink('project', 'browse', "projectID=$project->id");?>
      <tr class='text-center'>
        <td><?php echo html::a($projectLink, $project->id);?></td>
        <td class='text-left'><?php echo $project->code;?></td>
        <td class='text-left'><?php echo html::a($projectLink, $project->name);?></td>
        <td><?php echo $project->begin;?></td>
        <td><?php echo $project->end;?></td>
        <td class="c-status">
          <?php if(isset($project->delay)):?>
          <span class="status-project status-delayed" title='<?php echo $lang->project->delayed;?>'> <?php echo $lang->project->delayed;?></span>
          <?php else:?>
          <?php $statusName = $this->processStatus('project', $project);?>
          <span class="status-project status-<?php echo $project->status?>" title='<?php echo $statusName;?>'> <?php echo $statusName;?></span>
          <?php endif;?>
        </td>
        <td><?php echo $project->role;?></td>
        <td><?php echo $project->join;?></td>
        <td><?php echo $project->hours;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
