<?php
/**
 * The html template file of putinto method of personnel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.main-table tbody>tr:hover { background-color: #fff; }
.main-table tbody>tr:nth-child(odd):hover { background-color: #f5f5f5; }
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
      foreach($lang->personnel->inputLabel as $label => $labelName)
      {
          $active = $browseType == $label ? 'btn-active-text' : '';
          echo html::a($this->createLink('personnel', 'putInto', "programID=$programID&browseType=$label"), '<span class="text">' . $labelName . '</span>', '', "class='btn btn-link $active'");
      }
    ?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <?php if(isset($inputPersonnel['projects'])):?>
  <div class="main-col">
    <form class="main-table table-personnel" action="" data-ride="table">
      <?php $vars = "programID=$programID&browseType=$browseType&orderBy=%s";?>
      <table id="accessibleList" class="table table-bordered has-sort-head">
        <thead>
          <tr>
            <th class="w-160px"><?php echo $lang->personnel->program;?></th>
            <th class="w-100px"><?php echo common::printOrderLink('id', $orderBy, $vars, $lang->personnel->project);?></th>
            <th class="w-80px"><?php echo $lang->personnel->sprint;?></th>
            <th class="w-60px"><?php echo $lang->personnel->user;?></th>
            <th class="w-60px"><?php echo $lang->personnel->role;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($inputPersonnel['projects'] as $project):?>
          <?php
              $sprintRow     = isset($inputPersonnel['sprints'][$project->id]) ? count($inputPersonnel['sprints'][$project->id]) : 0;
              $teamMemberRow = 0;

              if($browseType == 'exist' && $sprintRow == 0) continue; // If the stage or sprint is empty, skip it.
              if($sprintRow > 0)
              {
                foreach($inputPersonnel['sprints'][$project->id] as $sprint)
                {
                  $teamMemberRow += isset($inputPersonnel['teams'][$sprint->id]) ? count($inputPersonnel['teams'][$sprint->id]) : 0;
                }
              }
              $projectRow = $sprintRow + $teamMemberRow;
          ?>
          <tr>
            <td class="text-ellipsis" rowspan="<?php echo $projectRow + 1;?>" title="<?php echo $project->programName;?>"><?php echo $project->programName;?></td>
            <td class="text-ellipsis" rowspan="<?php echo $projectRow + 1;?>" title="<?php echo $project->name;?>"><?php echo $project->name;?></td>
            <?php if($sprintRow == 0):?>
              <td></td>
              <td></td>
              <td></td>
            <?php endif;?>
          </tr>
          <?php if($sprintRow == 0) continue;?>
          <?php foreach($inputPersonnel['sprints'][$project->id] as $sprint):?>
          <tr>
            <td class="text-ellipsis" rowspan="<?php echo count($inputPersonnel['teams'][$sprint->id]) + 1;?>" title="<?php echo $sprint->name;?>"><?php echo $sprint->name;?></td>
            <?php if(!isset($inputPersonnel['teams'][$sprint->id])):?>
              <td></td>
              <td></td>
            <?php endif;?>
          </tr>
          <?php if(!isset($inputPersonnel['teams'][$sprint->id])) continue;?>
          <?php foreach($inputPersonnel['teams'][$sprint->id] as $team):?>
          <tr>
            <td><?php echo $team->account;?></td>
            <td><?php echo $team->role;?></td>
          </tr>
          <?php endforeach;?>
          <?php endforeach;?>
          <?php endforeach;?>
        </tbody>
      </table>
    </form>
  </div>
  <?php else:?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->personnel->emptyTip;?></span>
      </p>
    </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
