<?php
/**
 * The create file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: create.html.php 935 2021-12-09 13:48:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('spaceID', $spaceID);?>
<?php js::set('spaceType', $type);?>
<?php js::set('enableImport', $enableImport);?>
<?php js::set('vision', $this->config->vision);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->kanban->create;?></h2>
    <div class="pull-right btn-toolbar">
      <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyKanbanModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->kanban->copy . $lang->kanban->common;?></button>
    </div>
  </div>
  <form class='form-indicator main-form form-ajax no-stash' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->kanbanspace->type;?></th>
        <td><?php echo html::radio('type', $typeList, $type, "onchange='changeValue({$spaceID}, this.value)'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->WIPCount;?></th>
        <td><?php echo html::radio('showWIP', $lang->kanban->showWIPList, isset($copyKanban->showWIP) ? $copyKanban->showWIP : 1);?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->space;?></th>
        <td><?php echo html::select('space', $spacePairs, isset($copyKanban->space) ? $copyKanban->space : $spaceID, "onchange='loadUsers(this.value)' class='form-control chosen'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->name;?></th>
        <td><?php echo html::input('name', isset($copyKanban->name) ? $copyKanban->name : '', "class='form-control'");?></td>
      </tr>
      <?php if($type != 'private'):?>
      <tr>
        <th><?php echo $lang->kanban->owner;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('owner', $ownerPairs, isset($copyKanban->owner) ? $copyKanban->owner : '', "class='form-control chosen' data-drop_direction='down'");?>
            <span class='input-group-btn'><?php echo html::commonButton($lang->kanban->allUsers, "class='btn btn-default' onclick='loadAllUsers()' data-toggle='tooltip'");?></span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->team;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('team[]', $users, isset($copyKanban->team) ? $copyKanban->team : '', "class='form-control picker-select' multiple data-dropDirection='bottom'");?>
            <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=team");?>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <th class='columnWidth'><?php echo $lang->kanban->columnWidth;?></th>
        <td colspan='2'>
            <div class="width-radio-row">
                <?php echo html::radio('fluidBoard', array(0 => $lang->kanbancolumn->fluidBoardList['0']), isset($copyKanban->fluidBoard) ? $copyKanban->fluidBoard : 0);?>
                <?php echo html::input('colWidth', !empty($copyKanban->colWidth) ? $copyKanban->colWidth : $this->config->colWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->colWidth}' autocomplete='off'");?>px
                <div class='fixedTip'><?php echo $lang->kanbancolumn->fixedTip;?></div>
            </div>
            <div class="width-radio-row mt10">
                <?php echo html::radio('fluidBoard', array(1 => $lang->kanbancolumn->fluidBoardList['1']), isset($copyKanban->fluidBoard) ? $copyKanban->fluidBoard : 0);?>
                <?php echo html::input('minColWidth', !empty($copyKanban->minColWidth) ? $copyKanban->minColWidth: $this->config->minColWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->minColWidth}' autocomplete='off'");?>px
                <span class="input-divider">~</span>
                <?php echo html::input('maxColWidth', !empty($copyKanban->maxColWidth) ? $copyKanban->maxColWidth: $this->config->maxColWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->maxColWidth}' autocomplete='off'");?>px
                <div class='autoTip'><?php echo $lang->kanbancolumn->autoTip;?></div>
            </div>
        </td>
      </tr>
      <tr>
        <th rowspan='2'><?php echo $lang->kanban->import?></th>
        <td colspan='2' class='importBox'><?php echo nl2br(html::radio('import', $lang->kanban->importList, $enableImport));?></td>
      </tr>
      <tr>
        <td colspan='2' class='objectBox'><?php echo html::checkbox('importObjectList', $lang->kanban->importObjectList, $importObjects);?></td>
      </tr>
      <tr id='emptyTip' class='hidden'><th></th><td colspan='2' style='color: red;'><?php echo $lang->kanban->error->importObjNotEmpty;?></td></tr>
      <tr>
        <th class='w-90px'><?php echo $lang->kanban->archive;?></th>
        <td><?php echo nl2br(html::radio('archived', $lang->kanban->archiveList, isset($copyKanban->archived) ? $copyKanban->archived : '1'));?></td>
      </tr>
      <tr>
        <th id='c-title'><?php echo $lang->kanban->manageProgress;?></th>
        <td><?php echo nl2br(html::radio('performable', $lang->kanban->enableList, isset($copyKanban->performable) ? $copyKanban->performable : '1'));?></td>
      </tr>
      <tr>
        <th id='c-title'><?php echo $lang->kanban->alignment;?></th>
        <td><?php echo nl2br(html::radio('alignment', $lang->kanban->alignmentList, isset($copyKanban->alignment) ? $copyKanban->alignment : 'center'));?></td>
      </tr>
      <tr>
        <th><?php echo $lang->kanban->desc;?></th>
        <td colspan='2'>
          <?php echo html::textarea('desc', isset($copyKanban->desc) ? $copyKanban->desc : '', "rows='10' class='form-control'");?>
        </td>
      </tr>
      <?php if($type == 'private'):?>
      <tr id="whitelistBox">
        <th><?php echo $lang->whitelist;?></th>
        <td colspan='2'>
          <div class="input-group">
            <?php echo html::select('whitelist[]', $users, isset($copyKanban->whitelist) ? $copyKanban->whitelist : '', 'class="form-control picker-select" multiple');?>
            <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist&attr=data-drop_direction='up'");?>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::hidden('copyKanbanID', $copyKanbanID)?>
          <?php echo html::hidden('copyRegion', $copyRegion)?>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<div class='modal fade modal-scroll-inside' id='copyKanbanModal'>
  <div class='modal-dialog mw-900px'>
    <div class='modal-header'>
      <button type='button' class='close' data-dismiss='modal'><i class="icon icon-close"></i></button>
      <div class='titleBox'><h4 class='modal-title' id='myModalLabel'><?php echo $lang->kanban->copyTitle;?></h4></div>
    </div>
    <div class='modal-body'>
      <?php if(count($kanbans) == 1):?>
      <div class='alert with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $lang->kanban->copyNoKanban;?></div>
      </div>
      <?php else:?>
      <?php $copyContent = array('basicInfo');?>
      <?php if($copyRegion) $copyContent[] = 'region';?>
      <div class='copyContentBox'><?php echo $lang->kanban->copyContent . html::checkbox('copyContent', $lang->kanban->copyContentList, $copyContent);?></div>
      <div id='copyKanbans' class='row'>
      <?php foreach ($kanbans as $id => $name):?>
      <?php if(empty($id)):?>
      <?php if($copyKanbanID != 0):?>
        <div class='col-md-4 col-sm-4'><a href='javascript:;' data-id='' class='cancel'><?php echo html::icon($lang->icons['cancel']) . ' ' . $lang->kanban->cancelCopy;?></a></div>
      <?php endif;?>
      <?php else: ?>
        <div class='col-md-4 col-sm-4'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo ($copyKanbanID == $id) ? ' active' : '';?>'><?php echo html::icon($lang->icons['kanban'], 'text-muted') . ' ' . $name;?></a></div>
      <?php endif; ?>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
