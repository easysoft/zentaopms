<?php
/**
 * The space file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: space.html.php 935 2021-12-07 14:31:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->kanbanspace->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('space', "browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('kanban', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->kanban->create, '', 'class="btn btn-secondary iframe"', '', true);?>
    <?php common::printLink('kanban', 'createSpace', '', '<i class="icon icon-plus"></i> ' . $lang->kanban->createSpace, '', 'class="btn btn-primary iframe"', '', true);?>
  </div>
</div>
<div id='mainContent'>
  <?php if(empty($spaces)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->kanbanspace->empty;?></span></p>
  </div>
  <?php else:?>
  <?php foreach($spaces as $space):?>
  <div class='row cell' id='spaces'>
    <div class='space'>
      <div class='spaceTitle pull-left'>
        <h4>
          <?php echo $space->name;?>
          <?php echo isset($space->kanbans) ? count($space->kanbans) : '';?>
        </h4>
      </div>
      <div class='spaceActions pull-right'>
        <?php common::printLink('kanban', 'create', "spaceID={$space->id}", '<i class="icon icon-plus"></i> ' . $lang->kanban->create, '', "class='iframe'", '', true);?>
        <?php common::printLink('kanban', 'editSpace', "spaceID={$space->id}", '<i class="icon icon-cog-outline"></i> ' . $lang->kanban->setting, '', "class='iframe'", '', true);?>
        <?php common::printLink('kanban', 'closeSpace', "spaceID={$space->id}", '<i class="icon icon-off"></i> ' . $lang->close, '', "class='iframe'", '', true);?>
        <?php common::printLink('kanban', 'deleteSpace', "spaceID={$space->id}", '<i class="icon icon-trash"></i> ' . $lang->delete, 'hiddenwin', '', '', true);?>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  <?php endif;?>
</div>
<div id='spacesFooter' class='table-footer'>
  <?php $pager->show('right', 'pagerjs');?>
</div>
<?php include '../../common/view/footer.html.php';?>
