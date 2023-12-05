<?php
/**
 * The import card view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@cnezsoft.com>
 * @package     kanban
 * @version     $Id: importcard.html.php 5090 2022-01-19 14:19:24Z xieqiyu@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('kanbanID', $kanbanID);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('columnID', $columnID);?>
<?php js::set('methodName', $this->app->rawMethod);?>
<div id='mainContent' class='main-content importModal'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importCard;?></h2>
    </div>
  </div>
  <div class='input-group space'>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedKanban;?></span>
    <?php echo html::select('kanban', $kanbanPairs, $selectedKanbanID, "onchange='reloadObjectList(this.value)' class='form-control chosen' data-drop_direction='down'");?>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedLane;?></span>
    <?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen' data-drop_direction='down'");?>
  </div>
  <?php if($cards2Imported):?>
  <form class='main-table' method='post' data-ride='table' target='hiddenwin' id='importCardForm'>
    <table class='table table-fixed' id='cardList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->kanban->name;?></th>
          <th class='c-pri' title=<?php echo $lang->pri;?>><?php echo $lang->priAB;?></th>
          <th class='c-name'><?php echo $lang->kanbancard->name;?></th>
          <th class='c-user'><?php echo $lang->kanbancard->assignedTo;?></th>
          <th class='c-date'><?php echo $lang->kanbancard->end;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($cards2Imported as $card):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='cards[]' value='<?php echo $card->id;?>'/>
              <label></label>
            </div>
            <?php printf('%03d', $card->id);?>
          </td>
          <td title='<?php echo zget($kanbanPairs, $card->kanban);?>'><?php echo zget($kanbanPairs, $card->kanban);?></td>
          <td><span class='label-pri <?php echo 'label-pri-' . $card->pri?>' title='<?php echo zget($lang->kanbancard->priList, $card->pri, $card->pri);?>'><?php echo zget($lang->kanbancard->priList, $card->pri, $card->pri);?></span></td>
          <?php if(common::hasPriv('kanban', 'viewCard')):?>
          <td title='<?php echo $card->name;?>'><?php common::printLink('kanban', 'viewCard', "cardID=$card->id", $card->name, '', "class='iframe'", true, true);?></td>
          <?php else:?>
          <td title='<?php echo $card->name;?>'><?php echo $card->name;?></td>
          <?php endif;?>
          <?php
          $assignedToName = '';
          $assignedToList = explode(',', $card->assignedTo);
          foreach($assignedToList as $assignedTo) $assignedToName .= zget($users, $assignedTo) . ' ';
          ?>
          <td class='c-name' title='<?php echo $assignedToName;?>'><?php echo $assignedToName;?></td>
          <td title='<?php echo helper::isZeroDate($card->end) ? '' : $card->end;?>'><?php echo helper::isZeroDate($card->end) ? '' : $card->end;?></td>
        </tr>
        <?php endforeach;?>
        <tr><?php echo html::hidden('targetLane', key($lanePairs));?></tr>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importCard, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php else:?>
  <div class='table-empty-tip'><?php echo $lang->noData;?></div>
  <?php endif;?>
</div>
<style>#kanban_chosen {width: 45% !important}</style>
<?php include '../../common/view/footer.lite.html.php';?>
