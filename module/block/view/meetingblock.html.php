<?php if(empty($meetings)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-meetings .c-id {width: 50px;}
.block-meetings .c-date {width: 100px;}
.block-meetings .c-dept {width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.block-meetings .c-mode {width: 80px;}
.block-meetings .c-minutedBy {width: 80px;}
.block-meetings .c-host {width: 80px;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-fixed table-fixed-head table-hover tablesorter block-meetings <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <th class='c-date'><?php echo $lang->meeting->date;?></th>
        <th class='c-name'><?php echo $lang->meeting->name?></th>
        <?php if($longBlock):?>
        <th class='c-dept'><?php echo $lang->meeting->dept;?></th>
        <th class='c-mode'><?php echo $lang->meeting->mode;?></th>
        <th class='c-minutedBy'><?php echo $lang->meeting->minutedBy;?></th>
        <?php endif;?>
        <th class='c-host'><?php echo $lang->meeting->host;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($meetings as $meeting):?>
      <?php
      $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      ?>
      <tr>
        <td class='c-id-xs'><?php echo sprintf('%03d', $meeting->id);?></td>
        <td class='c-date'><?php echo $meeting->date == '0000-00-00' ? '' : $meeting->date;?></td>
        <td class='c-name' title='<?php echo $meeting->name?>'><?php echo html::a($this->createLink('meeting', 'view', "meetingID=$meeting->id"), $meeting->name, '', "data-app='my'")?></td>
        <?php if($longBlock):?>
        <td class='c-dept' title="<?php echo zget($depts, $meeting->dept)?>"><?php echo zget($depts, $meeting->dept)?></td>
        <td class='c-mode'><?php echo zget($lang->meeting->modeList, $meeting->mode)?></td>
        <td class='c-minutedBy'><?php echo zget($users, $meeting->minutedBy);?></td>
        <?php endif;?>
        <td class='c-host'><?php echo zget($users, $meeting->host);?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
