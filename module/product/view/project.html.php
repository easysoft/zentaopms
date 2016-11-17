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
<?php include '../../common/view/sparkline.html.php';?>
<div id='titlebar'>
  <div class='heading'><i class='icon-folder-close'></i> <?php echo $lang->product->project;?>  </div>
</div>
<table class='table table-fixed'>
  <thead>
    <tr class='colhead'>
      <th><?php echo $lang->project->name;?></th>
      <th class='w-100px'><?php echo $lang->project->code;?></th>
      <th class='w-120px'><?php echo $lang->project->end;?></th>
      <th class='w-80px'><?php echo $lang->project->status;?></th>
      <th class='w-50px'><?php echo $lang->project->totalEstimate;?></th>
      <th class='w-50px'><?php echo $lang->project->totalConsumed;?></th>
      <th class='w-50px'><?php echo $lang->project->totalLeft;?></th>
      <th class='w-150px'><?php echo $lang->project->progess;?></th>
      <th class='w-100px'><?php echo $lang->project->burn;?></th>
    </tr>
  </thead>
  <?php foreach($projectStats as $project):?>
  <tr class='text-center'>
    <td class='text-left'><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id), $project->name, '_parent');?></td>
    <td><?php echo $project->code;?></td>
    <td><?php echo $project->end;?></td>
    <?php if(isset($project->delay)):?>
    <td class='status-delay'><?php echo $lang->project->delayed;?></td>
    <?php else:?>
    <td class='status-<?php echo $project->status?>'><?php echo $lang->project->statusList[$project->status];?></td>
    <?php endif;?>
    <td><?php echo $project->hours->totalEstimate;?></td>
    <td><?php echo $project->hours->totalConsumed;?></td>
    <td><?php echo $project->hours->totalLeft;?></td>
    <td class='text-left w-150px'>
      <img src='<?php echo $defaultTheme;?>images/main/green.png' width=<?php echo $project->hours->progress;?> height='13' text-align: />
      <small><?php echo $project->hours->progress;?>%</small>
    </td>
    <td class='projectline text-left' values='<?php echo join(',', $project->burns);?>'></td>
 </tr>
 <?php endforeach;?>
</table>
<?php include '../../common/view/footer.html.php';?>
