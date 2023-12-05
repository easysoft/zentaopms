<?php
/**
 * The prjbatchedit view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     project
 * @version     $Id: prjbatchedit.html.php 4769 2021-02-020 11:13:21Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('linkedProjectsTip', $lang->project->linkedProjectsTip);?>
<?php js::set('changeProgram', $lang->project->changeProgram);?>
<?php js::set('beginLessThanParent', $lang->project->beginLessThanParent);?>
<?php js::set('endGreatThanParent', $lang->project->endGreatThanParent);?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<?php js::set('systemMode', $config->systemMode);?>
<?php js::set('longTime', $lang->project->longTime);?>
<?php js::set('ignore', $lang->project->ignore);?>
<?php $requiredFields = $config->project->edit->requiredFields;?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->project->batchEdit;?></h2>
  </div>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data' id="batchEditForm">
    <table class="table table-form">
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->idAB;?></th>
          <?php if(empty($globalDisableProgram)):?>
          <th class='c-parent'><?php echo $lang->project->parent;?></th>
          <?php endif;?>
          <th class='c-name required'><?php echo $lang->project->name;?></th>
          <?php if(isset($config->setCode) and $config->setCode == 1):?>
          <th class='c-name required'><?php echo $lang->project->code;?></th>
          <?php endif?>
          <th class="c-user-box <?php echo strpos($requiredFields, 'PM') !== false ?  'required' : '';?>"> <?php echo $lang->project->PM;?></th>
          <th class='c-date required'><?php echo $lang->project->begin;?></th>
          <th class='c-date required'><?php echo $lang->project->end;?></th>
          <th class='c-acl'><?php echo $lang->project->acl;?></th>
          <?php
          $extendFields = $this->project->getFlowExtendFields();
          foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
          ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($projects as $projectID => $project):?>
        <?php $aclList = (empty($globalDisableProgram) and $project->parent) ? $lang->program->subAcls : $lang->project->acls;?>
        <tr id="projects[<?php echo $projectID;?>]">
          <td><?php echo sprintf('%03d', $projectID) . html::hidden("projectIdList[$projectID]", $projectID);?></td>
          <?php if(empty($globalDisableProgram)):?>
          <?php if(isset($unauthorizedPrograms[$project->parent])):?>
          <td>
            <?php echo html::select("parents[$projectID]", $unauthorizedPrograms, $project->parent, "class='form-control chosen' onchange='outOfDateTip($projectID)' data-id='$projectID' data-name='{$project->name}' data-parent='{$project->parent}' disabled");?>
            <?php echo html::hidden("parents[$projectID]", $project->parent);?>
          </td>
          <?php else:?>
          <td><?php echo html::select("parents[$projectID]", $programs, $project->parent, "class='form-control chosen' onchange='outOfDateTip($projectID)' data-id='$projectID' data-name='{$project->name}' data-parent='{$project->parent}'");?></td>
          <?php endif;?>
          <?php endif;?>
          <td title='<?php echo $project->name;?>'><?php echo html::input("names[$projectID]", $project->name, "class='form-control'");?></td>
          <?php if(isset($config->setCode) and $config->setCode == 1):?>
          <td title='<?php echo $project->code;?>'><?php echo html::input("codes[$projectID]", $project->code, "class='form-control'");?></td>
          <?php endif;?>
          <td><?php echo html::select("PMs[$projectID]", $PMUsers, $project->PM, "class='form-control chosen'");?></td>
          <td>
            <?php echo html::input("begins[$projectID]", $project->begin, "class='form-control form-date' onchange='computeWorkDays(this.id);' placeholder='" . $lang->project->begin . "'");?>
          </td>
          <td>
            <?php
              $disabledEnd = $project->end == LONG_TIME ? 'disabled' : '';
              $end         = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;
              echo html::input("ends[$projectID]", $end, "class='form-control form-date' onchange='computeWorkDays(this.id);' placeholder='" . $lang->project->end . "'");
              echo html::hidden("dayses[$projectID]", $project->days);
            ?>
          </td>
          <td><?php echo html::select("acls[$projectID]", $aclList, $project->acl, "class='form-control'");?></td>
          <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $project, $extendField->field . "[{$projectID}]") . "</td>";?>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer text-center'>
      <?php echo html::submitButton();?>
      <?php echo html::backButton();?>
    </div>
</div>
<div class="modal fade" id="promptBox">
  <div class="modal-dialog mw-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form' id='promptTable'>
          <thead>
            <tr>
              <th class='text-left'><?php echo $lang->project->multiLinkedProductsTip;?></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
