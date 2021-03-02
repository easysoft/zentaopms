<?php
/**
 * The prjbatchedit view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     program
 * @version     $Id: prjbatchedit.html.php 4769 2021-02-020 11:13:21Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('linkedProjectsTip', $lang->program->linkedProjectsTip);?>
<?php js::set('changeProgram', $lang->program->changeProgram);?>
<?php $requiredFields = $config->program->PRJEdit->requiredFields;?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->program->PRJBatchEdit;?></h2>
  </div>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchEditForm">
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-40px'>  <?php echo $lang->idAB;?></th>
          <th class='w-200px'> <?php echo $lang->program->PGMParent;?></th>
          <th class='c-name required'><?php echo $lang->program->PRJName;?></th>
          <th class="w-150px <?php echo strpos($requiredFields, 'PM') !== false ?  'required' : '';?>"> <?php echo $lang->program->PRJPM;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($projectIdList as $projectID):?>
        <tr>
          <td><?php echo sprintf('%03d', $projectID) . html::hidden("projectIdList[$projectID]", $projectID);?></td>
          <td><?php echo html::select("parents[$projectID]", $programList, $projects[$projectID]->parent, "class='form-control chosen' data-id='$projectID' data-name='{$projects[$projectID]->name}' data-parent='{$projects[$projectID]->parent}'");?></td>
          <td title='<?php echo $projects[$projectID]->name;?>'><?php echo html::input("names[$projectID]", $projects[$projectID]->name, "class='form-control'");?></td>
          <td><?php echo html::select("PMs[$projectID]", $PMUsers, $projects[$projectID]->PM, "class='form-control chosen'");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="text-center form-actions">
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
              <th class='text-left'><?php echo $lang->program->multiLinkedProductsTip;?></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
