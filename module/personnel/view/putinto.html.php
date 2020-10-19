<?php
/**
 * The html template file of putInto method of personnel module of ZenTaoPMS.
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
  <?php if(!empty($inputPersonnel['projects'])):?>
  <div class="main-col">
    <form class="main-table table-personnel" action="" data-ride="table">
      <?php $vars = "programID=$programID&browseType=$browseType&orderBy=%s";?>
      <?php $existChildrenStage = empty($inputPersonnel['childrenStage']) ? false : true;?>
      <table id="accessibleList" class="table table-bordered has-sort-head">
        <thead>
          <tr>
            <th class="w-160px"><?php echo $lang->personnel->program;?></th>
            <th class="w-100px"><?php echo common::printOrderLink('id', $orderBy, $vars, $lang->personnel->project);?></th>
            <th class="w-80px"><?php echo $lang->personnel->sprint;?></th>
            <?php if($existChildrenStage):?>
            <th class="w-80px"><?php echo $lang->personnel->childrenStage;?></th>
            <?php endif;?>
            <th class="w-60px"><?php echo $lang->personnel->user;?></th>
            <th class="w-60px"><?php echo $lang->personnel->role;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($inputPersonnel['projects'] as $project):?>
          <tr>
            <?php $projectRow = $inputPersonnel['objectRows'][$project->id];?>
            <?php if($browseType == 'noempty' && $projectRow == 1) continue;?>
            <td class="text-ellipsis" rowspan="<?php echo $projectRow;?>" title="<?php echo $project->programName;?>"><?php echo $project->programName;?></td>
            <td class="text-ellipsis" rowspan="<?php echo $projectRow;?>" title="<?php echo $project->name;?>"><?php echo $project->name;?></td>
            <?php if($projectRow == 1):?>
              <?php if($existChildrenStage):?>
              <td></td>
              <?php endif;?>
              <td></td>
              <td></td>
              <td></td>
            <?php endif;?>
          </tr>

          <?php if(isset($inputPersonnel['sprintAndStage'][$project->id])):;?>
            <?php foreach($inputPersonnel['sprintAndStage'][$project->id] as $object):?>
              <?php $objectRow = $inputPersonnel['objectRows'][$object->id];?>
              <tr>
                <td class="text-ellipsis" id="<?php echo $object->id;?>" rowspan="<?php echo $objectRow;?>" title="<?php echo $object->name;?>"><?php echo $object->name;?></td>
                <?php if($objectRow == 1):?>
                  <?php if($existChildrenStage):?>
                  <td></td>
                  <?php endif;?>
                  <td></td>
                  <td></td>
                <?php endif;?>
              </tr>

              <?php if($object->type == 'sprint' && $objectRow > 1):?>
                <?php foreach($inputPersonnel['teams'][$object->id] as $team):?>
                <tr>
                  <?php if($existChildrenStage):?>
                  <td></td>
                  <?php endif;?>
                  <td><?php echo $team->realname;?></td>
                  <td><?php echo $team->role;?></td>
                </tr>
                <?php endforeach;?>
              <?php endif;?>

              <?php if($object->type == 'stage' && $object->grade == 1):?>
                <?php if(isset($inputPersonnel['childrenStage'][$object->id])):?>
                  <?php foreach($inputPersonnel['childrenStage'][$object->id] as $stage):?>
                  <tr>
                    <?php $teamRow = $inputPersonnel['objectRows'][$stage->id];?>
                    <td class="text-ellipsis" rowspan="<?php echo $teamRow;?>" title="<?php echo $stage->name;?>"><?php echo $stage->name;?></td>
                    <?php if($teamRow == 1):?>
                      <td></td>
                      <td></td>
                    <?php endif;?>
                  </tr>
                  <?php if($teamRow > 1):?>
                    <?php foreach($inputPersonnel['teams'][$stage->id] as $team):?>
                    <tr>
                      <td><?php echo $team->realname;?></td>
                      <td><?php echo $team->role;?></td>
                    </tr>
                    <?php endforeach;?>
                  <?php endif;?>
                  <?php endforeach;?>
                <?php else:?>
                  <?php if(isset($inputPersonnel['teams'][$object->id])):?>
                  <?php foreach($inputPersonnel['teams'][$object->id] as $team):?>
                  <tr>
                    <?php if($existChildrenStage):?>
                      <td></td>
                    <?php endif;?>
                    <td><?php echo $team->realname;?></td>
                    <td><?php echo $team->role;?></td>
                  </tr>
                  <?php endforeach;?>
                  <?php endif;?>
                <?php endif;?>
              <?php endif;?>
            <?php endforeach;?>
          <?php endif;?>
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
