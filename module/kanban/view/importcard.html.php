<?php
/**
 * The import card view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importCard;?></h2>
    </div>
  </div>
  <div class='table-row p-10px'>
    <div class='table-col w-150px text-center'><h4><?php echo $lang->kanban->selectedKanban;?></h4></div>
    <div class='table-col'><?php echo html::select('kanban', $kanbanPairs, $selectedKanbanID, "onchange='reloadObjectList(this.value)' class='form-control chosen' data-drop_direction='down'");?></div>
  </div>
  <div class='table-row p-10px'>
    <div class='table-col w-150px text-center'><h4><?php echo $lang->kanban->selectedLane;?></h4></div>
    <div class='table-col'><?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen' data-drop_direction='down'");?></div>
  </div>
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
          <th class='c-pri'><?php echo $lang->priAB;?></th>
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
          <td title='<?php echo zget($users, $card->assignedTo);?>'><?php echo zget($users, $card->assignedTo);?></td>
          <td title='<?php echo helper::isZeroDate($card->end) ? '' : $card->end;?>'><?php echo helper::isZeroDate($card->end) ? '' : $card->end;?></td>
        </tr>
        <?php endforeach;?>
        <tr><?php echo html::hidden('targetLane', key($lanePairs));?></tr>
      </tbody>
    </table>
    <?php if($cards2Imported):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importCard, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
