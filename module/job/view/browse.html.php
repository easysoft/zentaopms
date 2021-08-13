<?php
/**
 * The browse view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('job', 'browse'), "<span class='text'>{$lang->ci->task}</span>", '', "class='btn btn-link btn-active-text'");?>
    <?php echo html::a($this->createLink('compile', 'browse'), "<span class='text'>{$lang->ci->history}</span>", '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('job', 'create')) common::printLink('job', 'create', "", "<i class='icon icon-plus'></i> " . $lang->job->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($jobList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('job', 'create')):?>
    <?php echo html::a($this->createLink('job', 'create'), "<i class='icon icon-plus'></i> " . $lang->job->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='jobList' class='table has-sort-head table-fixed'>
      <thead>
        <tr class='text-left'>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px text-center'><?php common::printOrderLink('id', $orderBy, $vars, $lang->job->id);?></th>
          <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->job->name);?></th>
          <th class='w-150px'><?php common::printOrderLink('repo', $orderBy, $vars, $lang->job->repo);?></th>
          <th class='w-100px'><?php common::printOrderLink('engine', $orderBy, $vars, $lang->job->engine);?></th>
          <th class='w-80px'><?php common::printOrderLink('frame', $orderBy, $vars, $lang->job->frame);?></th>
          <th class='w-250px'><?php common::printOrderLink('server', $orderBy, $vars, $lang->job->buildSpec);?></th>
          <th class='text-left'><?php echo $lang->job->triggerType;?></th>
          <th class='w-100px text-center'><?php common::printOrderLink('lastStatus', $orderBy, $vars, $lang->job->lastStatus);?></th>
          <th class='w-130px'><?php common::printOrderLink('lastExec', $orderBy, $vars, $lang->job->lastExec);?></th>
          <th class='w-120px c-actions-3'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($jobList as $id => $job):?>
        <tr class='text-left'>
          <td class='text-center'><?php echo $id;?></td>
          <td class='text-left c-name' title='<?php echo $job->name;?>'><?php echo common::hasPriv('job', 'view') ? html::a($this->createLink('job', 'view', "jobID={$job->id}", 'html', true), $job->name, '', "class='iframe' data-width='90%'") : $job->name;?></td>
          <td title='<?php echo $job->repoName;?>'><?php echo $job->repoName;?></td>
          <td><?php echo zget($lang->job->engineList, $job->engine);?></td>
          <td><?php echo zget($lang->job->frameList, $job->frame);?></td>
          <?php if(strtolower($job->engine) == 'gitlab') $job->pipeline = $this->loadModel('gitlab')->getObjectNameForJob($job->server, $job->pipeline);?>
          <?php $jenkins = urldecode($job->pipeline) . '@' . $job->jenkinsName;?>
          <td class='c-name' title='<?php echo $jenkins;?>'><?php echo $jenkins;?></td>
          <?php $triggerConfig = $this->job->getTriggerConfig($job);?>
          <td class='c-name' title='<?php echo $triggerConfig;?>'><?php echo $triggerConfig;?></td>
          <td class='text-center'><?php if($job->lastStatus) echo zget($lang->compile->statusList, $job->lastStatus);?></td>
          <td><?php if($job->lastStatus) echo $job->lastExec;?></td>
          <td class='c-actions text-center'>
            <?php
            common::printIcon('compile', 'browse', "jobID=$id", '', 'list', 'history');
            common::printIcon('job', 'edit', "jobID=$id", '', 'list',  'edit');
            if(strtolower($job->engine) == 'jenkins') common::printIcon('job', 'exec', "jobID=$id", '', 'list',  'play', 'hiddenwin');
            if(strtolower($job->engine) == 'gitlab')  common::printIcon('job', 'exec', "jobID=$id&showForm=yes", '', 'list',  'play', '', '', false, "data-toggle='modal' data-type='ajax'");
            if(common::hasPriv('job', 'delete')) echo html::a($this->createLink('job', 'delete', "jobID=$id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->job->delete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($jobList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
