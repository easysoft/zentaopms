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
<?php if(empty($totalData)): ?>
<div class='empty-tip'><?php common::printLink('project', 'create', '', "<i class='icon-plus'></i> " . $lang->project->create, '', "class='btn btn-primary'")?></div>
<?php else:?>
<div class="panel-body has-table scrollbar-hover">
  <table class='table table-borderless table-hover'>
    <thead>
      <tr class=''>
        <th colspan="3" class='c-name text-left'><?php echo '总投入';?></th>
        <th class="c-name"><?php echo $lang->block->totalStory;?></th>
        <th class="c-name"><?php echo $lang->block->totalBug;?></th>
      </tr>
    </thead>
    <tbody class="text-center">
      <tr>
        <td class="c-name"><i class="icon icon-user"></i><?php echo '总人数';?></td>
        <td class="c-name"><i class="icon icon-time"></i><?php echo '已消耗工时';?></td>
        <td class="c-name"><i class="icon icon-yen"></i><?php echo '已花费';?></td>
        <td class="c-name"><?php echo $totalData[$programID]->allStories;?></td>
        <td class="c-name"><?php echo $totalData[$programID]->allBugs;?></td>
      </tr>
      <tr>
        <td class="c-data"><?php echo $totalData[$programID]->teamCount;?></td>
        <td class="c-data"><?php echo $totalData[$programID]->consumed;?></td>
        <td class="c-data"><?php echo '无数据'?></td>
        <td class="c-data">
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $totalData[$programID]->doneStories;?>" aria-valuemin="0" aria-valuemax="<?php echo $totalData[$programID]->allStories;?>" style="width: <?php echo (($totalData[$programID]->doneStories/$totalData[$programID]->allStories)*100).'%'; ?>">
            </div>
          </div>
        </td>
        <td class="c-data">
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $totalData[$programID]->doneBugs;?>" aria-valuemin="0" aria-valuemax="<?php echo $totalData[$programID]->allBugs;?>" style="width: <?php echo (($totalData[$programID]->doneBugs/$totalData[$progaramID]->allBugs)*100).'%'; ?>">
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td></td>
        <td class="c-data"><?php echo '总预计'.' '.$totalData[$programID]->estimate;?></td>
        <td class="c-data"><?php echo '无数据';?></td>
        <td class="c-data"><?php echo "已完成".'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'."剩余".'<br/>'.$totalData[$programID]->doneStories.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$totalData[$programID]->leftStories;?></td>
        <td class="c-data"><?php echo "已完成".'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'."剩余".'<br/>'.$totalData[$programID]->doneBugs.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$totalData[$programID]->leftBugs;?></td>
      </tr>
    </tbody>
  </table>
</div>
<?php endif;?>
