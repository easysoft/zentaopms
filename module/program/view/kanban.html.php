<?php
/**
 * The html template file of kanban method of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="kanban" class="main-table fade auto-fade-in" data-ride="table" data-checkable="false" data-group="true">
  <?php foreach($kanbanGroup as $type => $programGroup):?>
  <?php if(empty($programGroup)) continue;?>
  <?php $colorIndex = 0;?>
  <div class="cell">
    <div class='detail'>
      <div class='detail-title'><?php echo $lang->program->kanban->typeList[$type];?></div>
      <div class='detail-content'>
        <table class="table no-margin table-grouped text-center" style='background: #efefef;'>
          <thead>
            <tr>
              <th rowspan='2' class='w-20px' style='background: #32C5FF; border: none'></th>
              <th rowspan='2'><?php echo $lang->program->kanban->openProducts;?></th>
              <th rowspan='2'><?php echo $lang->program->kanban->unexpiredPlans;?></th>
              <th rowspan='2'><?php echo $lang->program->kanban->waitingProjects;?></th>
              <th colspan='2'><?php echo $lang->program->statusList['doing'];?></th>
              <th rowspan='2'><?php echo $lang->program->kanban->normalReleases;?></th>
            </tr>
            <tr>
              <th><?php echo $lang->program->kanban->doingProjects;?></th>
              <th><?php echo $lang->program->kanban->doingExecutions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($programGroup as $programID => $program):?>
            <tr>
              <td class='lane-name text-ellipsis' style='background: <?php echo $lang->program->kanban->laneColorList[$colorIndex];?>; color: #fff; border: none;' rowspan='<?php echo $program->rowspan;?>' title=<?php echo $program->name;?>><?php echo $program->name;?></td>
              <?php $i = 0;?>
              <?php if(!empty($program->products)):?>
              <?php foreach($program->products as $productID => $product):?>
              <?php
              $scroll = '';
              $doingCounts  = isset($product->projects['doing']) ? count($product->projects['doing']) : 0;
              $planCounts   = isset($product->plans) ? count($product->plans) : 0;
              $releaseCount = isset($product->releases) ? count($product->releases) : 0;
              if(($doingCounts < $planCounts || $doingCounts < $releaseCount) and ($planCounts > 5 or $releaseCount > 5)) $scroll = 'scroll';
              ?>
              <?php if($i != 0) echo '<tr>';?>
              <td title=<?php echo $product->name;?> rowspan='<?php echo $product->rowspan;?>'><?php echo $product->name;?></td>
              <td class='normal-plan' rowspan='<?php echo $product->rowspan;?>'>
                <div class="<?php echo $scroll;?>">
                  <?php foreach($product->plans as $planID => $plan):?>
                  <div class='board-item'>
                    <div class='table-row'>
                      <div class='table-col text-ellipsis text-left'>
                        <?php echo html::a($this->createLink('productplan', 'view', "planID=$plan->id"), $plan->title);?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
              </td>
              <td class='wait-project' rowspan='<?php echo $product->rowspan?>'>
                <div class="<?php echo $scroll;?>">
                  <?php if(isset($product->projects['wait'])):?>
                  <?php foreach($product->projects['wait'] as $projectID => $project):?>
                  <div class='board-item' style='border-left: 3px solid #0991FF'>
                    <div class='table-row'>
                      <div class='table-col text-ellipsis text-left'>
                        <?php echo html::a($this->createLink('project', 'index', "projectID=$project->id"), $project->name);?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach;?>
                  <?php endif;?>
                </div>
              </td>
              <?php if(isset($product->projects['doing'])):?>
              <?php $index = 0;?>
              <?php foreach($product->projects['doing'] as $projectID => $project):?>
              <?php if($index != 0) echo "<tr>";?>
              <td class='doing-td project'>
                <div class='board'>
                  <div class='board-item' <?php echo "style='border-left: 3px solid " . (isset($project->delay) ? 'red' : "#0BD986") . "'";?>>
                    <div class='table-row'>
                      <div class='table-col'>
                        <?php echo html::a($this->createLink('project', 'index', "projectID=$project->id"), $project->name);?>
                      </div>
                      <div class='table-col'>
                        <div class="c-progress">
                          <?php $projectProgress = isset($project->hours->progress) ? $project->hours->progress : 0;?>
                          <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo round($projectProgress);?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                            <div class='progress-info'><?php echo round($projectProgress);?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class='doing-td execution'>
                <?php if(!empty($project->execution)):?>
                <div class='board'>
                  <div class='board-item' <?php echo "style='border-left: 3px solid " . (isset($project->execution->delay) ? 'red' : "#0BD986") . "'";?>>
                    <div class='table-row'>
                      <div class='table-col'>
                        <?php echo html::a($this->createLink('execution', 'task', "executionID={$project->execution->id}"), $project->execution->name);?>
                      </div>
                      <div class='table-col'>
                        <div class="c-progress">
                          <?php $executionProgress = isset($project->execution->hours->progress) ? $project->execution->hours->progress : 0;?>
                          <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo round($executionProgress);?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                            <div class='progress-info'><?php echo round($executionProgress);?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endif;?>
              </td>
              <?php if($index == 0):?>
              <td class='normal-release' rowspan=<?php echo $product->rowspan;?>>
                <div class="<?php echo $scroll;?>">
                  <?php foreach($product->releases as $releaseID => $release):?>
                  <div class='board-item'>
                    <div class='table-row'>
                      <div class='table-col'>
                        <?php $flag = $release->marker ? " <icon class='icon icon-flag red' title='{$lang->release->marker}'></icon> " : '';?>
                        <?php echo html::a($this->createLink('release', 'view', "releaseID=$release->id"), $release->name . $flag);?>
                      </div>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
              </td>
              <?php endif;?>
              <?php if($index != 0) echo "</tr>";?>
              <?php $index ++;?>
              <?php endforeach;?>
              <?php else:?>
              <td></td>
              <td></td>
              <td></td>
              <?php endif;?>
              <?php if($i != 0) echo '</tr>';?>
              <?php $i ++;?>
              <?php endforeach;?>
              <?php else:?>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <?php endif;?>
            </tr>
            <?php $colorIndex ++;?>
            <?php if($colorIndex > 9) $colorIndex = 0;?>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<script>
$(function()
{
    $('.table div.scroll').each(function()
    {
        var count           = $(this).closest('tr').find('td:first').attr('rowspan') >= 5 ? $(this).closest('tr').find('td:first').attr('rowspan') : 5;
        var projectTdHeight = $(this).closest('tr').find('td.project .board').outerHeight(true) + 20;
        var maxHeight       = count > 5 ? projectTdHeight * count :  projectTdHeight * count - 40;
        $(this).css('max-height', maxHeight);
    })
})
</script>
<?php include '../../common/view/footer.html.php';?>
