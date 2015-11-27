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
<table class='table table-data table-condensed table-hover table-striped table-fixed block-project'>
  <thead>
    <tr class='text-center'>
      <th><div class='text-left'><i class="icon-folder-close-alt icon"></i> <?php echo $lang->project->name;?></div></th>
      <th width='80'><?php echo $lang->project->end;?></th>
      <th width='50'><?php echo $lang->statusAB;?></th>
      <th width='45'><?php echo $lang->project->totalEstimate;?></th>
      <th width='45'><?php echo $lang->project->totalConsumed;?></th>
      <th width='45'><?php echo $lang->project->totalLeft;?></th>
      <th width='45'><?php echo $lang->project->progess;?></th>
    </tr>
  </thead>
  <tbody>
   <?php $id = 0; ?>
   <?php foreach($projectStats as $project):?>
    <?php $appid = isset($_GET['entry']) ? "class='app-btn text-center' data-id='{$this->get->entry}'" : "class='text-center'"?>
    <tr data-url='<?php echo $sso . $sign . 'referer=' . base64_encode($this->createLink('project', 'task', 'project=' . $project->id)); ?>' <?php echo $appid?>>
      <td class='text-left' title='<?php echo $project->name;?>'><?php echo $project->name;?></td>
      <td><?php echo $project->end;?></td>
      <td><?php echo $lang->project->statusList[$project->status];?></td>
      <td><?php echo $project->hours->totalEstimate;?></td>
      <td><?php echo $project->hours->totalConsumed;?></td>
      <td><?php echo $project->hours->totalLeft;?></td>
      <td class='text-left'><?php echo $project->hours->progress;?>%</td>
   </tr>
   <?php endforeach;?>
  </tbody>
</table>
<p class='hide block-project-link'><?php echo $listLink;?></p>
<script>
$('.block-project').dataTable();
$('.block-project-link').closest('.panel').find('.panel-heading .more').attr('href', $('.block-project-link').html());
</script>
