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
<?php include '../../common/view/footer.html.php';?>
