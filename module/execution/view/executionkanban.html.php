<?php
/**
 * The execution kanban view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Qiyu Xie
 * @package     execution
 * @version     $Id: executionkanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="kanban" class="main-table fade auto-fade-in" data-ride="table" data-checkable="false" data-group="true">
  <table class="table no-margin table-grouped text-center">
    <thead>
      <tr>
        <th><?php echo $lang->execution->doingProject . ' (' . $projectCount . ')';?></th>
        <?php foreach($lang->execution->kanbanColType as $status => $col):?>
        <th><?php echo $col . ' (' . $statusCount[$status] . ')';?></th>
        <?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <?php $rowIndex = 0; ?>
      <?php foreach($kanbanGroup as $projectID => $executionList):?>
      <tr>
        <td class='board-project text-left color-<?php echo $rowIndex;?>'>
          <div data-id='<?php echo $projectID;?>'>
            <div class='text-center'>
              <?php $projectTitle = empty($projectID) ? $lang->execution->myExecutions : zget($projects, $projectID);?>
              <span class='group-title' title='<?php echo $projectTitle;?>'><?php echo $projectTitle;?></span>
            </div>
          </div>
        </td>
        <td class="c-boards no-padding text-left color-<?php echo $rowIndex;?>" colspan='4'>
          <div class="boards-wrapper">
            <div class="boards">
              <?php foreach($lang->execution->kanbanColType as $colStatus => $col):?>
              <div class="board s-<?php echo $colStatus?>" data-type="<?php echo $colStatus;?>">
                <div>
                  <?php if(!empty($executionList[$colStatus])):?>
                  <?php foreach($executionList[$colStatus] as $execution):?>
                  <div class='board-item' data-id='<?php echo $execution->id?>' id='task-<?php echo $execution->id?>' data-type='execution' <?php if($execution->status == 'doing' and isset($execution->delay)) echo "style='border-left: 3px solid #ff0000';";?>>
                    <div class='table-row'>
                      <div class='table-col'>
                        <?php
                        if(common::hasPriv('execution', 'task'))
                        {
                            echo html::a($this->createLink('execution', 'task', "executionID=$execution->id"), $execution->name, '', 'class="title kanbaniframe" title="' . $execution->name . '"');
                        }
                        else
                        {
                            echo "<span class='title' title='{$execution->name}'>{$execution->name}</span>";
                        }
                        ?>
                      </div>
                      <div class='table-col'>
                        <?php if($colStatus == 'doing'):?>
                        <div class="c-progress">
                          <div class='progress-pie' data-doughnut-size='90' data-color='#00da88' data-value='<?php echo $execution->hours->progress;?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                            <div class='progress-info'><?php echo $execution->hours->progress;?></div>
                          </div>
                        </div>
                        <?php endif?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach?>
                  <?php endif?>
                </div>
              </div>
              <?php endforeach;?>
            </div>
          </div>
        </td>
      </tr>
      <?php $rowIndex++; ?>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<style>
<?php foreach(array_keys($lang->execution->kanbanColType) as $status):?>
<?php echo ".s-$status .board-item {border-left: 3px solid {$lang->execution->statusColorList[$status]};}";?>
<?php endforeach;?>
<?php
$boardColorList = explode(',', $lang->execution->boardColorList);
$colorCounts    = count($boardColorList);
$colorIndex     = 0;
for($i = 0; $i <= $rowIndex; $i++)
{
    $colorIndex = $i % $colorCounts == 0 ? 0 : ++$colorIndex;
    echo "#kanban tbody > tr > td.color-$i {background-color: {$boardColorList[$colorIndex]};}";
}
?>
</style>
<?php include '../../common/view/footer.html.php';?>
