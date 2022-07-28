<?php
/**
 * The edit view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
          <td colspan='2'><?php echo nl2br(html::radio('fluidBoard', $lang->kanbancolumn->fluidBoardList, $execution->fluidBoard));?></td>
        </tr>
        <?php if($laneCount > 1):?>
        <tr>
          <th id='c-name'><?php echo $lang->kanban->laneHeight;?></th>
          <td class='laneHeightBox' colspan='2'><?php echo nl2br(html::radio('heightType', $lang->kanbanlane->heightTypeList, $heightType, "onclick='setCardCount(this.value);'"));?></td>
        </tr>
        <tr class="hidden" id='cardBox'>
          <th class='c-count'><?php echo $lang->kanban->cardCount;?></th>
          <td><?php echo html::input('displayCards', $displayCards, "class='form-control' required placeholder='{$lang->kanban->cardCountTip}'  autocomplete='off'");?></td>
        </tr>
        <?php endif;?>
        <tr><td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton();?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
