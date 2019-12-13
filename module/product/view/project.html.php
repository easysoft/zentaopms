<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 2343 2011-11-21 05:24:56Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->project->featureBar['all'] as $key => $label):?>
    <?php echo html::a(inlink("project", "status=$key&productID=$productID"), "<span class='text'>{$label}</span>" . ($status == $key ? " <span class='label label-light label-badge'>" . count($projectStats) . "</span>" : ''), '', "class='btn btn-link" . ($status == $key ? ' btn-active-text' : '') . "' id='{$key}Tab'");?>
    <?php endforeach;?>
    <span class="label label-info projectInfo"><?php echo $lang->product->projectInfo;?></span>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($projectStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->project->noProject;?></span>
      <?php if(common::hasPriv('project', 'create')):?>
      <?php echo html::a($this->createLink('project', 'create'), "<i class='icon icon-plus'></i> " . $lang->project->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table table-project'>
    <table class="table table-fixed">
      <thead>
        <tr>
          <th><?php echo $lang->projectCommon;?></th>
          <th class='w-150px'><?php echo $lang->project->code;?></th>
          <th class='w-120px'><?php echo $lang->project->end;?></th>
          <th class='w-80px'><?php echo $lang->project->status;?></th>
          <th class='w-80px'><?php echo $lang->project->totalEstimate;?></th>
          <th class='w-50px'><?php echo $lang->project->totalConsumed;?></th>
          <th class='w-50px'><?php echo $lang->project->totalLeft;?></th>
          <th class='w-150px'><?php echo $lang->project->progress;?></th>
          <th class='w-100px'><?php echo $lang->project->burn;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $id = 0;?>
        <?php foreach($projectStats as $project):?>
        <tr>
          <td class='text-left'><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id), $project->name, '_parent');?></td>
          <td><?php echo $project->code;?></td>
          <td><?php echo $project->end;?></td>
          <?php if(isset($project->delay)):?>
          <td class='c-status' title='<?php echo $lang->project->delayed;?>'>
            <span class="status-project status-delayed"><?php echo $lang->project->delayed;?></span>
          </td>
          <?php else:?>
          <?php $status = $this->processStatus('project', $project);?>
          <td class='c-status' title='<?php echo $status;?>'>
            <span class="status-project status-<?php echo $project->status?>"><?php echo $status;?></span>
          </td>
          <?php endif;?>
          <td><?php echo $project->hours->totalEstimate;?></td>
          <td><?php echo $project->hours->totalConsumed;?></td>
          <td><?php echo $project->hours->totalLeft;?></td>
          <td class="c-progress">
            <div class="progress progress-text-left">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->hours->progress;?>%">
              <span class="progress-text"><?php echo $project->hours->progress;?>%</span>
              </div>
            </div>
          </td>
          <td id='spark-<?php echo $id++?>' class='c-spark sparkline' values='<?php echo join(',', $project->burns);?>'></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
