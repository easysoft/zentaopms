<?php
/**
 * The project block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<div class="panel-body has-table">
  <table class='table table-borderless table-hover table-fixed-head block-projects'>
    <thead>
      <tr class='text-center'>
        <th class='text-left c-name'><?php echo $lang->project->name;?></th>
        <th class="c-deadline"><?php echo $lang->project->end;?></th>
        <?php if($longBlock):?>
        <th class="c-status"><?php echo $lang->statusAB;?></th>
        <th class="c-estimate"><?php echo $lang->project->totalEstimate;?></th>
        <th class="c-consumed"><?php echo $lang->project->totalConsumed;?></th>
        <th class="c-left"><?php echo $lang->project->totalLeft;?></th>
        <?php endif;?>
        <th class="c-progress"><?php echo $lang->project->progress;?></th>
        <?php if($longBlock):?>
        <th class="c-spark"><?php echo $lang->project->burn;?></th>
        <?php endif;?>
      </tr>
    </thead>
    <tbody>
     <?php $id = 0; ?>
     <?php foreach($projectStats as $project):?>
      <?php
      $appid    = isset($_GET['entry']) ? "class='app-btn text-center' data-id='{$this->get->entry}'" : "class='text-center'";
      $viewLink = $this->createLink('project', 'task', 'project=' . $project->id);
      ?>
      <tr data-url='<?php echo empty($sso) ? $viewLink : $sso . $sign . 'referer=' . base64_encode($viewLink); ?>' <?php echo $appid?>>
        <td class='text-left c-name' title='<?php echo $project->name;?>'><nobr><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id), $project->name, '', "title='$project->name'");?></nobr></td>
        <td class="c-deadline"><?php echo $project->end;?></td>
        <?php if($longBlock):?>
        <td class="c-status">
          <?php if(isset($project->delay)):?>
          <span class="project-status-delayed"><span class="label label-dot"></span> <?php echo $lang->project->delayed;?></span>
          <?php else:?>
          <span class="project-status-<?php echo $project->status?>"><span class="label label-dot"></span> <?php echo zget($lang->project->statusList, $project->status, '');?></span>
          <?php endif;?>
        </td>
        <td class="c-estimate"><?php echo $project->hours->totalEstimate;?></td>
        <td class="c-consumed"><?php echo $project->hours->totalConsumed;?></td>
        <td class="c-left"><?php echo $project->hours->totalLeft;?></td>
        <?php endif;?>
        <td class="c-progress">
          <div class="progress progress-text-left">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->hours->progress;?>%">
            <span class="progress-text"><?php echo $project->hours->progress;?>%</span>
            </div>
          </div>
        </td>
        <?php if($longBlock):?>
        <td id='spark-<?php echo $id++?>' class='c-spark sparkline' values='<?php echo join(',', $project->burns);?>'></td>
        <?php endif;?>
     </tr>
     <?php endforeach;?>
    </tbody>
  </table>
</div>
