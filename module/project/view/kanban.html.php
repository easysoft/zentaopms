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
<?php if(empty($projectGroup)) continue;?>
<div id="kanban" class="main-table fade auto-fade-in" data-ride="table" data-checkable="false" data-group="true">
  <div class="cell">
    <div class='detail'>
      <div class='detail-title'><?php echo $lang->project->typeList[$type];?></div>
      <div class='detail-content'>
        <table class='table no-margin' style='background-color: #efefef;'>
          <thead class='text-center'>
            <tr>
              <th rowspan='2' style="border-right: unset !important" class='w-20px'></th>
              <th rowspan='2' style="border-left: unset !important"><?php echo $lang->project->waitProjects;?></th>
              <th colspan='2'><?php echo $lang->project->statusList['doing'];?></th>
              <th rowspan='2'><?php echo $lang->project->closedProjects;?></th>
            </tr>
            <tr>
              <th><?php echo $lang->project->doingProjects;?></th>
              <th><?php echo $lang->project->doingExecutions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($projectGroup as $programID => $statusList):?>
            <tr class='board-program'>
              <td class='text-center' style='background: <?php echo $lang->project->laneColorList[$colorIndex];?>; color: #fff; padding-left: 2px; writing-mode: vertical-lr;'><?php echo zget($programPairs, $programID);?></td>
              <?php foreach(array('wait','doing','closed') as $status):?>
              <?php if($status == 'doing'):?>
              <td class='board-doing'>
                <?php if(isset($statusList[$status])):?>
                <?php foreach($statusList[$status] as $project):?>
                <div class='board-doing-project'>
                  <div class='board-item' <?php echo "style='border-left: 3px solid " . (isset($project->delay) ? 'red' : "#0BD986") . "'";?>>
                    <div class='table-row'>
                      <div class='table-col'>
                      <?php
                      if(common::hasPriv('project', 'index'))
                      {
                          echo html::a($this->createLink('project', 'index', "projectID=$project->id"), $project->name, '', "title='{$project->name}'");
                      }
                      else
                      {
                          echo "<span title='{$project->name}'>{$project->name}</span>";
                      }
                      ?>
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
                <?php endforeach;?>
                <?php endif;?>
              </td>
              <td class='board-doing'>
                <?php if(isset($statusList[$status])):?>
                <?php foreach($statusList[$status] as $project):?>
                <div class='board-doing-execution'>
                  <?php if(isset($latestExecutions[$project->id])):?>
                  <?php $execution = $latestExecutions[$project->id];?>
                  <div class='board-item' <?php echo "style='border-left: 3px solid " . (isset($execution->delay) ? 'red' : "#0BD986") . "'";?>>
                    <div class='table-row'>
                      <div class='table-col'>
                      <?php
                      if(common::hasPriv('execution', 'task'))
                      {
                          echo html::a($this->createLink('execution', 'task', "executionID=$execution->id"), $execution->name, '', "title='{$execution->name}'");
                      }
                      else
                      {
                          echo "<span title='{$execution->name}'>{$execution->name}</span>";
                      }
                      ?>
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
                <?php endforeach;?>
                <?php endif;?>
              </td>
              <?php else:?>
              <td class='board-<?php echo $status;?>'>
                <div class='board-project'>
                  <?php if(isset($statusList[$status])):?>
                  <?php foreach($statusList[$status] as $project):?>
                  <div class='board-item' <?php echo "style='border-left: 3px solid " . $lang->execution->statusColorList[$status] . "'";?>>
                  <?php
                  if(common::hasPriv('project', 'index'))
                  {
                      echo html::a($this->createLink('project', 'index', "projectID=$project->id"), $project->name, '', "title='{$project->name}'");
                  }
                  else
                  {
                      echo "<span title='{$project->name}'>{$project->name}</span>";
                  }
                  ?>
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
<?php endforeach;?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
