<?php
/**
 * The viewcard of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     kanban
 * @version     $Id: viewcard.html.php 4903 2021-12-13 16:25:59Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <div class="page-title">
      <span class="label label-id"><?php echo $card->id?></span>
      <span class="text" title='<?php echo $card->name;?>'><?php echo $card->name;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class='detail-title'><?php echo $lang->kanbancard->desc;?></div>
        <div class="detail-content article-content"><?php echo $card->desc;?></div>
      </div>
    </div>
    <div class="cell">
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=kanbancard&objectID=$card->id");?>
      <?php include '../../common/view/action.html.php';?>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php if(!$card->deleted and !$card->archived and !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed')):?>
        <?php
        //common::printLink('kanban', 'assigntoCard', "cardID=$card->id", "<i class='icon icon-hand-right'></i><span class='text'>{$lang->kanbancard->assign}</span>", '', "class='btn btn-link iframe' title='{$lang->kanbancard->assign}'", true, true);
        if($kanban->archived)
        {
            common::printLink('kanban', 'archiveCard', "cardID=$card->id", "<i class='icon icon-ban-circle'></i><span class='text'>{$lang->kanbancard->archive}</span>", 'hiddenwin', "class='btn btn-link' title='{$lang->kanbancard->archive}'", true, true);

            echo "<div class='divider'></div>";
        }

        common::printLink('kanban', 'editCard',   "cardID=$card->id", '<i class="icon icon-edit"></i>',  '', "class='btn btn-link' data-width='80%' title='{$lang->kanbancard->edit}'",  true, true);

        if($kanban->performable)
        {
            if($card->status == 'done') echo html::a(helper::createLink('kanban', 'activateCard', "cardID={$card->id}&kanbanID={$kanban->id}"), '<i class="icon icon-magic"></i>', '', "class='btn btn-link' title='{$lang->kanban->activateCard}'");
            if($card->status == 'doing') echo html::a(helper::createLink('kanban', 'finishCard', "cardID={$card->id}&kanbanID={$kanban->id}"), '<i class="icon icon-checked"></i>', '', "class='btn btn-link iframe' title='{$lang->kanban->finishCard}'");
        }

        //common::printLink('kanban', 'copyCard', "cardID=$card->id", '<i class="icon icon-copy"></i>', '', "class='btn btn-link iframe' title='{$lang->kanbancard->copy}'", true, true);
        common::printLink('kanban', 'deleteCard', "cardID=$card->id", '<i class="icon icon-trash"></i>', 'hiddenwin', "class='btn btn-link' title='{$lang->kanbancard->delete}'",true, true);
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->kanbancard->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th><?php echo $lang->kanbancard->assignedTo;?></th>
                  <td>
                  <?php $assignedToPairs = array_filter(explode(',', $card->assignedTo));?>
                  <?php
                  foreach($assignedToPairs as $index => $assignedTo)
                  {
                      if(!isset($users[$assignedTo])) unset($assignedToPairs[$index]);
                  }
                  ?>
                  <?php if(!empty($assignedToPairs)):?>
                    <div class='kanban-members pull-left'>
                      <?php foreach($assignedToPairs as $member):?>
                      <div title="<?php echo zget($users, $member);?>">
                        <?php echo zget($users, $member) . '&nbsp;&nbsp;';?>
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
                <?php if($kanban->performable):?>
                <tr>
                  <th><?php echo $lang->kanbancard->progress;?></th>
                  <td><?php echo round($card->progress, 2) . ' %';?></td>
                </tr>
                <?php endif;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->kanbancard->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
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
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
