<?php
/**
 * The html product kanban file of kanban method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="kanban" class="main-table fade auto-fade-in" data-ride="table" data-checkable="false" data-group="true">
  <?php if(empty($productList)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <?php foreach($products as $type => $programs):?>
  <?php if(empty($programs)) continue;?>
  <div class="cell">
    <table class="table text-center" style="border-radius: 0; background: #efefef;">
      <caption><?php echo $type == 'myProducts' ? $lang->product->myProduct : $lang->product->otherProduct;?></caption>
      <thead>
        <tr>
          <th rowspan="2" class="w-20px" style="border-right: 0; border-left: 0; background: #32C5FF;"></th>
          <th rowspan="2" style="border-left: 0px;"><?php echo $lang->product->unclosedProduct;?></th>
          <th rowspan="2"><?php echo $lang->product->unexpiredPlan;?></th>
          <th colspan="2"><?php echo $lang->product->doing;?></th>
          <th rowspan="2"><?php echo $lang->product->normalRelease;?></th>
        </tr>
        <tr>
          <th><?php echo $lang->product->doingProject;?></th>
          <th><?php echo $lang->product->doingExecution;;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $colorIndex = 0;?>
        <?php foreach($programs as $programID => $programProduct):?>
        <?php $i = 0;?>
        <?php foreach($programProduct as $productID):?>
        <?php
        $scroll = '';
        $doingCounts  = isset($projectProduct[$productID]) ? count($projectProduct[$productID]) : 0;
        $planCounts   = isset($planList[$productID]) ? count($planList[$productID]) : 0;
        $releaseCount = isset($releaseList[$productID]) ? count($releaseList[$productID]) : 0;
        if(($doingCounts < $planCounts or $doingCounts < $releaseCount) and ($planCounts > 5 or $releaseCount > 5)) $scroll = 'scroll';
        ?>
        <tr>
          <?php if($i == 0):?>
          <td rowspan="<?php echo count($programs[$programID])?>" class="program text-ellipsis" style="background: <?php echo $this->lang->product->kanbanColorList[$colorIndex];?>"><?php echo zget($programList, $programID);?></td>
          <?php endif;?>
          <td class="product">
            <?php if(common::hasPriv('product', 'browse')):?>
            <?php echo "<span>" . html::a($this->createLink('product', 'browse', "productID=$productID"), $productList[$productID]->name, '', "title={$productList[$productID]->name}") . '</span>';?>
            <?php else:?>
            <?php echo "<span title={$productList[$productID]->name}>{$productList[$productID]->name}</span>";?>
            <?php endif;?>
          </td>
          <td class="plan">
            <div class="<?php echo $scroll;?>">
              <?php if(isset($planList[$productID])):?>
              <?php foreach($planList[$productID] as $planID => $plan):?>
              <div class="board-item text-ellipsis">
                <?php if(common::hasPriv('productplan', 'view')):?>
                <?php echo html::a($this->createLink('productplan', 'view', "planID=$planID"), $plan->title, '', "title={$plan->title}");?>
                <?php else:?>
                <?php echo "<span title={$plan->title}>{$plan->title}</span>"?>
                <?php endif;?>
              </div>
              <?php endforeach;?>
              <?php endif;?>
            </div>
          </td>
          <td class="project">
            <?php if(isset($projectProduct[$productID])):?>
            <?php foreach($projectProduct[$productID] as $projectID => $project):?>
            <div class="board">
              <?php $borderStyle = isset($project->delay) ? 'border-left: 3px solid red' : 'border-left: 3px solid #0bd986';?>
              <div class="board-item" style="<?php echo $borderStyle;?>">
                <div class="table-row">
                  <div class="table-col text-ellipsis">
                    <?php if(common::hasPriv('project', 'index')):?>
                    <?php echo html::a($this->createLink('project', 'index', "projectID=$projectID"), $projectList[$projectID]->name, '', "title={$projectList[$projectID]->name}");?>
                    <?php else:?>
                    <?php echo "<span title={$projectList[$projectID]->name}>{$projectList[$projectID]->name}</span>"?>
                    <?php endif;?>
                  </div>
                  <div class="table-col">
                    <div class="c-progress">
                      <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo round($projectList[$projectID]->hours->progress);?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                        <div class='progress-info'><?php echo round($projectList[$projectID]->hours->progress);?></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
          </td>
          <td class="execution">
            <?php if(isset($projectProduct[$productID])):?>
            <?php foreach($projectProduct[$productID] as $projectID => $project):?>
            <div class="board">
              <?php
              $borderStyle = '';
              if(isset($latestExecutions[$projectID]))
              {
                  $delay = helper::diffDate(helper::today(), $latestExecutions[$projectID]->end);
                  $borderStyle = $delay > 0 ? 'border-left: 3px solid red' : 'border-left: 3px solid #0bd986';
              }
              ?>
              <?php $boardStyle    = isset($latestExecutions[$projectID]) ? 'board-item' : 'emptyBoard';?>
              <?php $executionID   = isset($latestExecutions[$projectID]) ? $latestExecutions[$projectID]->id : 0;?>
              <?php $executionName = isset($latestExecutions[$projectID]) ? $latestExecutions[$projectID]->name : ''?>
              <div class="<?php echo $boardStyle;?> text-ellipsis" style="<?php echo $borderStyle;?>">
                <div class="table-row">
                  <div class="table-col text-ellipsis">
                    <?php if(isset($latestExecutions[$projectID])):?>
                    <?php if(common::hasPriv('execution', 'task')):?>
                    <?php echo html::a($this->createLink('execution', 'task', "executionID=$executionID"), $executionName, '', "title={$executionName}");?>
                    <?php else:?>
                    <?php echo "<span title=$executionName>$executionName</span>";?>
                    <?php endif;?>
                    <?php endif;?>
                  </div>
                  <div class="table-col">
                    <?php if(isset($latestExecutions[$projectID])):?>
                    <div class="c-progress">
                      <?php $hourList[$executionID] = isset($hourList[$executionID]) ? $hourList[$executionID] : $emptyHour; ?>
                      <div class='progress-pie' data-doughnut-size='90' data-color='#3CB371' data-value='<?php echo round($hourList[$executionID]->progress);?>' data-width='24' data-height='24' data-back-color='#e8edf3'>
                        <div class='progress-info'><?php echo round($hourList[$executionID]->progress);?></div>
                     </div>
                    </div>
                    <?php endif;?>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
          </td>
          <td class="release">
            <div class="<?php echo $scroll;?>">
              <?php if(isset($releaseList[$productID])):?>
              <?php foreach($releaseList[$productID] as $releaseID => $release):?>
              <div class="board-item">
                <?php if(common::hasPriv('release', 'view')):?>
                <?php echo html::a($this->createLink('release', 'view', "releaseID=$releaseID"), $release->name, '', "title={$release->name} class='text-ellipsis'");?>
                <?php else:?>
                <?php echo "<span title={$release->name}>{$release->name}</span>"?>
                <?php endif;?>
                <?php if($release->marker) echo "&nbsp;<span><i class='icon icon-flag red'></i></span>";?>
              </div>
              <?php endforeach;?>
              <?php endif;?>
            </div>
          </td>
          <?php $i ++;?>
        </tr>
        <?php endforeach;?>
        <?php $colorIndex = $colorIndex == 9 ? 0 : ++ $colorIndex;?>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <?php endforeach;?>
  <?php endif;?>
</div>
<script>
$(function()
{
    $('.board').height($('.project .board').height());

    $('.table div.scroll').each(function()
    {
        var count     = $(this).parent().siblings('td.project').children('.board').length >= 5 ? $(this).parent().siblings('td.project').children('.board').length : 5;
        var preHeight = $(this).parent().siblings('td.project').children('.board').length >= 5 ? $('.board').outerHeight(true) : $(this).find('.board-item').outerHeight(true);
        $(this).css('max-height', preHeight * count);
    })
})
</script>
<?php include '../../common/view/footer.html.php';?>
