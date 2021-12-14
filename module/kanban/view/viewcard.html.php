<?php
/**
 * The viewcard of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     kanban
 * @version     $Id: viewcard.html.php 4903 2021-12-13 16:25:59Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <div class='main-header'>
      <div class="page-title">
        <span class="label label-id"><?php echo $card->id?></span>
        <span class="text" title='<?php echo $card->name;?>'><?php echo $card->name;?></span>
      </div>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class='main-col col-8'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->kanbancard->desc;?></div>
        <div class="detail-content article-content"><?php echo $card->desc;?></div>
      </div>
    </div>
    <div class='cell'>
      <?php include '../../common/view/action.html.php';?>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php if(!$card->deleted):?>
        <?php
        common::printIcon('', '', "", '', 'button', 'hand-right', '', 'iframe', true, '', $lang->kanbancard->assigned);
        common::printIcon('', '', "", '', 'button', 'ban-circle', '', 'iframe', true, '', $lang->kanbancard->archived);

        echo "<div class='divider'></div>";

        common::printIcon('kanban', 'editcard', "cardID=$card->id", '', 'button', 'edit', '', 'iframe', true, '', ' ');
        common::printIcon('kanban', '', "", '', 'button', 'copy');
        common::printIcon('kanban', '', "", '', 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class='detail'>
        <div class='main-header'>
          <?php echo $lang->kanbancard->legendBasicInfo;?>
        </div>
        <table class="table table-data">
          <tr>
            <th><?php echo $lang->kanbancard->assignedTo;?></th>
            <td>
            <?php $assignedToPairs = array_filter(explode(',', $card->assignedTo));?>
            <?php if(!empty($assignedToPairs)):?>
              <div class='kanban-members pull-left'>
                <?php foreach($assignedToPairs as $member):?>
                <div title="<?php echo $users[$member];?>">
                  <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$member], 'account' => $member)); ?>
                </div>
                <?php endforeach;?>
              </div>
            <?php endif;?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->space;?></th>
            <td><?php echo $space->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->kanban;?></th>
            <td><?php echo $kanban->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->begin;?></th>
            <td><?php echo helper::isZeroDate($card->begin) ? '' : $card->begin;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->end;?></th>
            <td><?php echo helper::isZeroDate($card->end) ? '' : $card->end;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->pri;?></th>
            <td><span class='label-pri <?php echo 'label-pri-' . $card->pri;?>' title='<?php echo zget($lang->kanbancard->priList, $card->pri);?>'><?php echo zget($lang->kanbancard->priList, $card->pri);?></span></td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->estimate;?></th>
            <td><?php echo round($card->estimate, 2) . ' ' . $lang->kanbancard->lblHour;?></td>
          </tr>
        </table>
      </div>
    </div>
    <div class='cell'>
      <div class='detail'>
        <div class='main-header'>
          <?php echo $lang->kanbancard->legendLifeTime;?>
        </div>
        <table class="table table-data">
          <tr>
            <th><?php echo $lang->kanbancard->createdBy;?></th>
            <td><?php echo zget($users, $card->createdBy) . $lang->at . $card->createdDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->archivedBy;?></th>
            <td><?php echo $card->archivedBy ? zget($users, $card->archivedBy) . $lang->at . $card->archivedDate : '';?></td>
          </tr>
          <tr>
            <th><?php echo $lang->kanbancard->lastEditedBy;?></th>
            <td><?php echo $card->lastEditedBy ? zget($users, $card->lastEditedBy) . $lang->at . $card->lastEditedDate : '';?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
