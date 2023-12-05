<?php
/**
 * The edit view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <?php echo $lang->execution->setKanban;?>
      </h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->kanban->columnWidth;?></th>
          <td colspan='2'>
            <div class="width-radio-row">
                <?php echo html::radio('fluidBoard', array(0 => $lang->kanbancolumn->fluidBoardList['0']), $execution->fluidBoard, "class='inline-block'");?>
                <?php echo html::input('colWidth', !empty($execution->colWidth) ? $execution->colWidth : $this->config->colWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->colWidth}' autocomplete='off'");?>px
                <div class='fixedTip'><?php echo $lang->kanbancolumn->fixedTip;?></div>
            </div>
            <div class="width-radio-row mt10">
                <?php echo html::radio('fluidBoard', array(1 => $lang->kanbancolumn->fluidBoardList['1']), $execution->fluidBoard, "class='inline-block'");?>
                <?php echo html::input('minColWidth', !empty($execution->minColWidth) ? $execution->minColWidth: $this->config->minColWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->minColWidth}' autocomplete='off'");?>px
                <span class="input-divider">~</span>
                <?php echo html::input('maxColWidth', !empty($execution->maxColWidth) ? $execution->maxColWidth: $this->config->maxColWidth, "class='form-control inline-block setting-input' placeholder='{$this->config->maxColWidth}' autocomplete='off'");?>px
                <div class='autoTip'><?php echo $lang->kanbancolumn->autoTip;?></div>
            </div>
        </td>
        </tr>
        <?php if($laneCount > 1):?>
        <tr>
          <th id='c-name'><?php echo $lang->kanban->laneHeight;?></th>
          <td class='laneHeightBox' colspan='2'><?php echo nl2br(html::radio('heightType', $lang->kanbanlane->heightTypeList, $heightType, "onclick='setCardCount(this.value);'"));?></td>
        </tr>
        <tr class="hidden" id='cardBox'>
          <th class='c-count'><?php echo $lang->kanban->cardCount;?></th>
          <td colspan='2'><?php echo html::input('displayCards', $displayCards, "class='form-control' required placeholder='{$lang->kanbanlane->error->mustBeInt}' autocomplete='off'");?></td>
        </tr>
        <?php endif;?>
        <tr><td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton();?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
