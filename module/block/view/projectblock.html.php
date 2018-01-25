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
<?php $jsRoot = $this->config->webRoot . 'js/';?>
<?php include '../../common/view/sparkline.html.php';?>
<?php $projectboxId = 'projectbox-' . rand(); ?>
<?php $longBlock = $block->grid >= 6;?>
<div id='<?php echo $projectboxId ?>'>
  <table class='table table-borderless table-fixed block-project'>
    <thead>
      <tr class='text-center'>
        <th class='text-left'><?php echo $lang->project->name;?></th>
        <th width='85'><?php echo $lang->project->end;?></th>
        <?php if($longBlock):?>
        <th width='80'><?php echo $lang->statusAB;?></th>
        <th width='60'><?php echo $lang->project->totalEstimate;?></th>
        <th width='60'><?php echo $lang->project->totalConsumed;?></th>
        <th width='60'><?php echo $lang->project->totalLeft;?></th>
        <?php endif;?>
        <th width='115'><?php echo $lang->project->progress;?></th>
        <?php if($longBlock):?>
        <th width='100' class='{sorter: false}'><?php echo $lang->project->burn;?></th>
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
        <td class='text-left' title='<?php echo $project->name;?>'><nobr><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id), $project->name, '', "title='$project->name'");?></nobr></td>
        <td><?php echo $project->end;?></td>
        <?php if($longBlock):?>
        <?php if(isset($project->delay)):?>
        <td><span class="project-status-delayed"><span class="label label-dot"></span> <?php echo $lang->project->delayed;?></span></td>
        <?php else:?>
        <td><span class="project-status-<?php echo $project->status?>"><span class="label label-dot"></span> <?php echo $lang->project->statusList[$project->status];?></span></td>
        <?php endif;?>
        <td><?php echo $project->hours->totalEstimate;?></td>
        <td><?php echo $project->hours->totalConsumed;?></td>
        <td><?php echo $project->hours->totalLeft;?></td>
        <?php endif;?>
        <td>
          <div class="progress-text-left">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->hours->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->hours->progress;?>%">
            <span class="progress-text"><?php echo $project->hours->progress;?>%</span>
            </div>
          </div>
        </td>
        <?php if($longBlock):?>
        <td id='spark-<?php echo $id++?>' class='spark' values='<?php echo join(',', $project->burns);?>'></td>
        <?php endif;?>
     </tr>
     <?php endforeach;?>
    </tbody>
  </table>
</div>
<script>
$(function()
{
    var $projectbox = $('#<?php echo $projectboxId ?>');
    var $sparks = $projectbox.find('.spark');
    $sparks.addClass('sparked').projectLine();
});
</script>
