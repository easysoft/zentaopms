<?php
/**
 * The browse view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $menus = customModel::getFeatureMenu('job', 'browse');
    foreach($menus as $menuItem)
    {
        $active = $menuItem->name == 'job' ? 'btn-active-text' : '';
        echo html::a($this->createLink($menuItem->name, 'browse', "repoID=$repoID"), "<span class='text'>{$menuItem->text}</span>", '', "class='btn btn-link $active'");
    }
    ?>
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
          <?php $vars = "repoID=$repoID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='c-id text-center'><?php common::printOrderLink('id', $orderBy, $vars, $lang->job->id);?></th>
          <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->job->name);?></th>
          <th class='c-repo'><?php common::printOrderLink('repo', $orderBy, $vars, $lang->job->repo);?></th>
          <th class='c-engine'><?php common::printOrderLink('engine', $orderBy, $vars, $lang->job->engine);?></th>
          <th class='c-frame'><?php common::printOrderLink('frame', $orderBy, $vars, $lang->job->frame);?></th>
          <th class='c-server'><?php common::printOrderLink('server', $orderBy, $vars, $lang->job->buildSpec);?></th>
          <th class='text-left'><?php echo $lang->job->triggerType;?></th>
          <th class='c-status text-center'><?php common::printOrderLink('lastStatus', $orderBy, $vars, $lang->job->lastStatus);?></th>
          <th class='c-exec'><?php common::printOrderLink('lastExec', $orderBy, $vars, $lang->job->lastExec);?></th>
          <th class='c-actions-4 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($jobList as $id => $job):?>
        <?php
        if(strtolower($job->engine) == 'gitlab')
        {
            $pipeline = json_decode($job->pipeline);
            if(is_numeric($job->pipeline)) $job->pipeline = $this->loadModel('gitlab')->getProjectName($job->server, $job->pipeline);
            if(isset($pipeline->reference))  $job->pipeline = $this->loadModel('gitlab')->getProjectName($job->server, $pipeline->project);
        }
        ?>
        <tr class='text-left'>
          <td class='text-center'><?php echo $id;?></td>
          <td class='text-left c-name' title='<?php echo $job->name;?>'><?php echo common::hasPriv('job', 'view') ? html::a($this->createLink('job', 'view', "jobID={$job->id}", 'html', true), $job->name, '', "class='iframe' data-width='90%'") : $job->name;?></td>
          <td title='<?php echo $job->repoName;?>'><?php echo $job->repoName;?></td>
          <td><?php echo zget($lang->job->engineList, $job->engine);?></td>
          <td><?php echo zget($lang->job->frameList, $job->frame);?></td>
          <?php $jenkins = urldecode($job->pipeline) . '@' . $job->jenkinsName;?>
          <td class='c-name' title='<?php echo $jenkins;?>'><?php echo $jenkins;?></td>
          <?php $triggerConfig = $this->job->getTriggerConfig($job);?>
          <td class='c-name' title='<?php echo $triggerConfig;?>'><?php echo $triggerConfig;?></td>
          <td class='text-center'><?php if($job->lastStatus) echo zget($lang->compile->statusList, $job->lastStatus);?></td>
          <td><?php if($job->lastStatus) echo $job->lastExec;?></td>
          <td class='c-actions text-center'>
            <?php
            common::printIcon('compile', 'browse', "repoID={$job->repo}&jobID=$id", '', 'list', 'history');
            common::printIcon('job', 'edit', "jobID=$id", '', 'list',  'edit');
            common::printIcon('job', 'exec', "jobID=$id", '', 'list',  'play', '', $job->canExec ? '' : 'disabled');
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
