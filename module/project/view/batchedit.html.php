<?php
/**
 * The prjbatchedit view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<?php $requiredFields = $config->project->edit->requiredFields;?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->project->batchEdit;?></h2>
  </div>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchEditForm">
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-40px'><?php echo $lang->idAB;?></th>
          <th class='w-200px'><?php echo $lang->project->parent;?></th>
          <th class='c-name required'><?php echo $lang->project->name;?></th>
          <th class="w-150px <?php echo strpos($requiredFields, 'PM') !== false ?  'required' : '';?>"> <?php echo $lang->project->PM;?></th>
          <th class='w-120px required'><?php echo $lang->project->begin;?></th>
          <th class='w-120px required'><?php echo $lang->project->end;?></th>
          <th class='w-260px'><?php echo $lang->project->acl;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($projects as $projectID => $project):?>
        <?php $aclList = $project->parent ? $lang->program->subAcls : $lang->project->acls;?>
        <tr>
          <td><?php echo sprintf('%03d', $projectID) . html::hidden("projectIdList[$projectID]", $projectID);?></td>
          <td><?php echo html::select("parents[$projectID]", $programList, $project->parent, "class='form-control chosen' data-id='$projectID' data-name='{$project->name}' data-parent='{$project->parent}'");?></td>
          <td title='<?php echo $project->name;?>'><?php echo html::input("names[$projectID]", $project->name, "class='form-control'");?></td>
          <td><?php echo html::select("PMs[$projectID]", $PMUsers, $project->PM, "class='form-control chosen'");?></td>
          <td>
            <?php echo html::input("begins[$projectID]", $project->begin, "class='form-control form-date' onchange='computeWorkDays(this.id);' placeholder='" . $lang->project->begin . "'");?>
          </td>
          <td>
            <?php
              $disabledEnd = $project->end == LONG_TIME ? 'disabled' : '';
              $end         = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;
              echo html::input("ends[$projectID]", $end, "class='form-control form-date' $disabledEnd onchange='computeWorkDays(this.id);' placeholder='" . $lang->project->end . "'");
              echo html::hidden("dayses[$projectID]", $project->days);
            ?>
          </td>
          <td><?php echo nl2br(html::radio("acls[$projectID]", $aclList, $project->acl));?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="7" class="text-center form-actions">
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </tfoot>
    </table>
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
