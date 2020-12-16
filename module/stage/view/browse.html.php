<?php
/**
 * The browse view of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: browse.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-right">
    <?php common::printLink('stage', 'batchCreate', "", "<i class='icon icon-plus'></i>" . $lang->stage->batchCreate, '', "class='btn btn-primary'");?>
    <?php common::printLink('stage', 'create', "", "<i class='icon icon-plus'></i>" . $lang->stage->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class='main-row'>
  <?php if(empty($stages)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->stage->noStage;?></span>
      <?php if(common::hasPriv('stage', 'create')):?>
      <?php echo html::a($this->createLink('stage', 'create'), "<i class='icon icon-plus'></i> " . $lang->stage->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <div class='side-col'>
    <div class='panel'>
      <div class='panel-body'>
        <div class="list-group">
          <?php echo html::a(inlink('setType'), $lang->stage->setType);?>
          <?php echo html::a(inlink('browse'), $lang->stage->browse, '', "class='selected'");?>
        </div>
      </div>
    </div>
  </div>
  <div class='main-col main-table'>
    <table class="table has-sort-head" id='stageList'>
      <?php $vars = "orderBy=%s";?>
      <thead>
        <tr>
        <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->stage->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->stage->name);?></th>
          <th class='w-100px'><?php common::printOrderLink('percent', $orderBy, $vars, $lang->stage->percent);?></th>
          <th class='w-120px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->stage->type);?></th>
          <th class='w-120px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stages as $stage):?>
        <tr>
          <td><?php echo $stage->id;?></td>
          <td><?php echo $stage->name;?></td>
          <td class='text-center'><?php echo $stage->percent;?></td>
          <td><?php echo zget($lang->stage->typeList, $stage->type);?></td>
          <td class="c-actions">
            <?php
            common::printIcon('stage', 'edit', "stageID=$stage->id", "", "list");
            common::printIcon('stage', 'delete', "stageID=$stage->id", "", "list", '', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
