<?php
/**
 * The browse view of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: browse.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class='main-row'>
  <?php if(empty($stages)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->stage->noStage;?></span>
      <?php if(common::hasPriv('stage', 'create')):?>
      <?php echo html::a($this->createLink('stage', 'create', "type={$type}"), "<i class='icon icon-plus'></i> " . $lang->stage->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <div class='main-col main-content main-table' style='padding: 0;padding-top: 20px;'>
    <div id="mainMenu" class="clearfix" style='padding:0px 10px'>
      <div class="pull-left" style='padding-top:5px'>
        <strong><?php echo $lang->stage->browse;?></strong>
      </div>
      <div class="btn-toolbar pull-right">
        <?php common::printLink('stage', 'batchCreate', "type={$type}", "<i class='icon icon-plus'></i>" . $lang->stage->batchCreate, '', "class='btn btn-primary'");?>
        <?php common::printLink('stage', 'create', "type={$type}", "<i class='icon icon-plus'></i>" . $lang->stage->create, '', "class='btn btn-primary'");?>
      </div>
    </div>
    <table class="table has-sort-head" id='stageList'>
      <?php $vars = "orderBy=%s&type={$type}";?>
      <thead>
        <tr>
        <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->stage->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->stage->name);?></th>
          <?php if(isset($config->setPercent) and $config->setPercent == 1):?>
          <th class='w-100px'><?php common::printOrderLink('percent', $orderBy, $vars, $lang->stage->percent);?></th>
          <?php endif;?>
          <th class='w-120px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->stage->type);?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stages as $stage):?>
        <tr>
          <td><?php echo $stage->id;?></td>
          <td><?php echo $stage->name;?></td>
          <?php if(isset($config->setPercent) and $config->setPercent == 1):?>
          <td class='text-center'><?php echo $stage->percent;?></td>
          <?php endif;?>
          <td><?php echo zget($lang->stage->typeList, $stage->type);?></td>
          <td class="c-actions">
            <?php
            common::printIcon('stage', 'edit', "stageID=$stage->id", "", "list");
            common::printIcon('stage', 'delete', "stageID=$stage->id", "", "list", 'trash', 'hiddenwin');
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
