<?php
/**
 * The project kanban view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Qiyu Xie
 * @package     project
 * @version     $Id: kanban.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $colorIndex = 0;?>

<?php if(empty($kanbanGroup)):?>
<div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->project->empty;?></span></p>
</div>
<?php else:?>

<?php foreach($kanbanGroup as $type => $projectGroup):?>
<?php if(!empty($projectGroup)):?>
<div id="kanban" class="main-table fade auto-fade-in" data-ride="table" data-checkable="false" data-group="true">
  <div class="cell">
    <div class='detail'>
      <div class='detail-title'><?php echo $lang->project->kanban->typeList[$type];?></div>
      <div class='detail-content'>
        <table class='table no-margin' style='background-color: #efefef;'>
          <thead class='text-center'>
            <tr>
              <th rowspan='2' class='w-20px'></th>
              <th rowspan='2'><?php echo $lang->project->kanban->waitProjects;?></th>
              <th colspan='2'><?php echo $lang->project->statusList['doing'];?></th>
              <th rowspan='2'><?php echo $lang->project->kanban->closedProjects;?></th>
            </tr>
            <tr>
              <th><?php echo $lang->project->kanban->doingProjects;?></th>
              <th><?php echo $lang->project->kanban->doingExecutions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($projectGroup as $programID => $statusList):?>
            <tr>
              <td style='background: <?php echo $lang->project->kanban->laneColorList[$colorIndex];?>; color: #fff; padding-left: 2px;'><?php echo zget($programPairs, $programID);?></td>
              <?php foreach(array('wait','doing','closed') as $status):?>
              <?php if($status == 'doing'):?>
              <td colspan='2'>
                <div class='board-doing'>
                  <?php if(isset($statusList[$status])):?>
                  <?php foreach($statusList[$status] as $project):?>
                  <div class='table-row'>
                    <div class='table-col board-doing-project'>
                      <div class='board-item' <?php echo "style='border-left: 3px solid " . (isset($project->delay) ? 'red' : "#0BD986") . "'";?>>
                        <div class='table-row'>
                          <div class='table-col'>
                            <span><?php echo $project->name;?></span>
                          </div>
                          <div class='table-col'>
                            <div class="c-progress">
                              <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo round($project->hours->progress);?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                                <div class='progress-info'><?php echo round($project->hours->progress);?></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='table-col board-doing-execution'>
                      <?php if(isset($latestExecutions[$project->id])):?>
                      <?php $execution = $latestExecutions[$project->id];?>
                      <div class='board-item' <?php echo "style='border-left: 3px solid " . (isset($execution->delay) ? 'red' : "#0BD986") . "'";?>>
                        <div class='table-row'>
                          <div class='table-col'>
                            <span><?php echo $execution->name;?></span>
                          </div>
                          <div class='table-col'>
                            <div class="c-progress">
                              <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo round($execution->hours->progress);?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                                <div class='progress-info'><?php echo round($execution->hours->progress);?></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php endif;?>
                    </div>
                  </div>
                  <?php endforeach;?>
                  <?php endif;?>
                </div>
              </td>
              <?php else:?>
              <td>
                <div class='board-project'>
                  <?php if(isset($statusList[$status])):?>
                  <?php foreach($statusList[$status] as $project):?>
                  <div class='board-item' <?php echo "style='border-left: 3px solid " . $lang->execution->statusColorList[$status] . "'";?>>
                    <span><?php echo $project->name;?></span>
                  </div>
                  <?php endforeach;?>
                  <?php endif;?>
                </div>
              </td>
              <?php endif;?>
              <?php endforeach;?>
            </tr>
            <?php $colorIndex ++;?>
            <?php if($colorIndex > 9) $colorIndex = 0;?>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<?php endforeach;?>

<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
