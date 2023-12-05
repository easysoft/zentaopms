<?php
/**
 * The setting file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     kanban
 * @version     $Id: edit.html.php 935 2021-12-09 16:15:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('enableImport', $enableImport);?>
<?php js::set('vision', $this->config->vision);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->kanban->settingKanban;?></h2>
  </div>
  <form class='form-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->kanban->WIPCount;?></th>
        <td><?php echo html::radio('showWIP', $lang->kanban->showWIPList, $kanban->showWIP);?></td>
        <td></td>
      </tr>
      <tr>
        <th class='columnWidth'><?php echo $lang->kanban->columnWidth;?></th>
        <td colspan='2'>
            <div class="width-radio-row">
                <?php echo html::radio('fluidBoard', array(0 => $lang->kanbancolumn->fluidBoardList['0']), $kanban->fluidBoard);?>
                <?php echo html::input('colWidth', !empty($kanban->colWidth) ? $kanban->colWidth : $this->config->colWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->colWidth}' autocomplete='off'");?>px
                <div class='fixedTip'><?php echo $lang->kanbancolumn->fixedTip;?></div>
            </div>
            <div class="width-radio-row mt10">
                <?php echo html::radio('fluidBoard', array(1 => $lang->kanbancolumn->fluidBoardList['1']), $kanban->fluidBoard);?>
                <?php echo html::input('minColWidth', !empty($kanban->minColWidth) ? $kanban->minColWidth : $this->config->minColWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->minColWidth}' autocomplete='off'");?>px
                <span class="input-divider">~</span>
                <?php echo html::input('maxColWidth', !empty($kanban->maxColWidth) ? $kanban->maxColWidth : $this->config->maxColWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->maxColWidth}' autocomplete='off'");?>px
                <div class='autoTip'><?php echo $lang->kanbancolumn->autoTip;?></div>
            </div>
        </td>
      </tr>
      <?php if($laneCount > 1):?>
      <tr>
        <th id='c-name'><?php echo $lang->kanban->laneHeight;?></th>
        <td colspan='2' class='laneHeightBox'><?php echo nl2br(html::radio('heightType', $lang->kanbanlane->heightTypeList, $heightType, "onclick='setCardCount(this.value);'"));?></td>
      </tr>
      <tr class="hidden" id='cardBox'>
        <th class='c-count'><?php echo $lang->kanban->cardCount;?></th>
        <td colspan='2'><?php echo html::input('displayCards', $displayCards, "class='form-control' required placeholder='{$lang->kanbanlane->error->mustBeInt}'  autocomplete='off'");?></td>
      </tr>
      <?php endif;?>
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
        <td><?php echo nl2br(html::radio('archived', $lang->kanban->archiveList, $kanban->archived));?></td>
      </tr>
      <tr>
        <th id='c-title'><?php echo $lang->kanban->manageProgress;?></th>
        <td><?php echo nl2br(html::radio('performable', $lang->kanban->enableList, $kanban->performable));?></td>
      </tr>
      <tr>
        <th id='c-title'><?php echo $lang->kanban->alignment;?></th>
        <td><?php echo nl2br(html::radio('alignment', $lang->kanban->alignmentList, $kanban->alignment));?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
