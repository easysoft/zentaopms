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
  <?php $colorIndex = 0;?>
  <div class="cell">
    <div class='detail'>
      <div class='detail-title'><?php echo $lang->program->kanban->typeList[$type];?></div>
      <div class='detail-content'>
        <table class="table no-margin table-grouped text-center" style='background: #f5f5f5;'>
          <thead>
            <tr>
              <th rowspan='2' class='w-20px' style='background: #32C5FF; border-bottom: none'></th>
              <th rowspan='2'><?php echo $lang->program->kanban->activeProducts;?></th>
              <th rowspan='2'><?php echo $lang->program->kanban->activePlans;?></th>
              <th rowspan='2'><?php echo $lang->program->kanban->waitProjects;?></th>
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
              <td style='background: <?php echo $lang->program->kanban->laneColorList[$colorIndex];?>; color: #fff; border-right: none;' rowspan='<?php echo count($program->products);?>'><?php echo $program->name;?></td>
              <?php $i = 0;?>
              <?php if(!empty($program->products)):?>
              <?php foreach($program->products as $productID => $product):?>
              <?php if($i != 0) echo '<tr>';?>
              <td><?php echo $product->name;?></td>
              <td>
                <?php foreach($product->plans as $planID => $plan):?>
                <div class='board-item'>
                  <div class='table-row'>
                    <div class='table-col'>
                      <?php echo html::a($this->createLink('productplan', 'view', "planID=$plan->id"), $plan->title);?>
                    </div>
                  </div>
                </div>
                <?php endforeach;?>
              </td>
              <td>
                <?php if(isset($product->projects['wait'])):?>
                <?php foreach($product->projects['wait'] as $projectID => $project):?>
                <div class='board-item'>
                  <div class='table-row'>
                    <div class='table-col'>
                      <?php echo html::a($this->createLink('project', 'view', "projectID=$projectID"), $project->name);?>
                    </div>
                  </div>
                </div>
                <?php endforeach;?>
                <?php endif;?>
              </td>
              <td class='doing-project'>
                <?php if(isset($product->projects['doing'])):?>
                <?php foreach($product->projects['doing'] as $projectID => $project):?>
                <div class='board-item'>
                  <div class='table-row'>
                    <div class='table-col'>
                      <?php echo html::a($this->createLink('project', 'view', "projectID=$projectID"), $project->name);?>
                    </div>
                  </div>
                </div>
                <?php endforeach;?>
                <?php endif;?>
              </td>
              <td class='doing-execution'>
                <?php if(isset($product->projects['doing'])):?>
                <?php foreach($product->projects['doing'] as $projectID => $project):?>
                <div class='board-item'>
                  <div class='table-row'>
                    <div class='table-col'>
                      <?php if(!empty($project->execution)):?>
                      <?php echo html::a($this->createLink('execution', 'view', "executionID={$project->execution->id}"), $project->execution->name);?>
                      <?php endif;?>
                    </div>
                  </div>
                </div>
                <?php endforeach;?>
                <?php endif;?>
              </td>
              <td>
                <?php foreach($product->releases as $releaseID => $release):?>
                <div class='board-item'>
                  <div class='table-row'>
                    <div class='table-col'>
                      <?php echo html::a($this->createLink('release', 'view', "releaseID=$release->id"), $release->name);?>
                    </div>
                  </div>
                </div>
                <?php endforeach;?>
              </td>
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
<?php include '../../common/view/footer.html.php';?>
