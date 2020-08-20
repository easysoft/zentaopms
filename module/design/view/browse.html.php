<?php
/**
 * The browse view file of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     design
 * @version     $Id: browse.html.php 5102 2013-07-12 00:59:54Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('productID', $productID);?>
<?php
$showSubHeader = $program->category == 'single' ? 'hidden' : 'show';
js::set('showSubHeader', $showSubHeader);
?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    echo html::a(inlink('browse', "productID={$productID}&type=all"),  "<span class='text'>{$lang->design->typeList['all']}</span>"  . ($type == 'all'  ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'all'  ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('browse', "productID={$productID}&type=HLDS"), "<span class='text'>{$lang->design->typeList['HLDS']}</span>" . ($type == 'HLDS' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'HLDS' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('browse', "productID={$productID}&type=DDS"),  "<span class='text'>{$lang->design->typeList['DDS']}</span>"  . ($type == 'DDS'  ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'DDS'  ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('browse', "productID={$productID}&type=DBDS"), "<span class='text'>{$lang->design->typeList['DBDS']}</span>" . ($type == 'DBDS' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'DBDS' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('browse', "productID={$productID}&type=ADS"),  "<span class='text'>{$lang->design->typeList['ADS']}</span>"  . ($type == 'ADS'  ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'ADS'  ? ' btn-active-text' : '') . "'");
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('design', 'create')) echo html::a($this->createLink('design', 'create', "productID=$productID&designID=0"), "<i class='icon icon-plus'></i> {$lang->design->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade in">
  <?php if(empty($designs)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->design->noDesign;?></span></p>
  </div>
  <?php else:?>
  <form id='designFrom' method='post' class="main-table">
    <table class='table has-sort-head table-fixrd' id="designTable">
      <?php $vars = "productID=$productID&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";?>
        <thead>
          <tr>
            <th class="text-left w-120px">  <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
            <th class="text-left w-120px">  <?php common::printOrderLink('type',        $orderBy, $vars, $lang->design->type);?></th>
            <th class="text-left">          <?php common::printOrderLink('name',        $orderBy, $vars, $lang->design->name);?></th>
            <th class="text-left w-130px">  <?php common::printOrderLink('commit',      $orderBy, $vars, $lang->design->submission);?></th>
            <th class="text-left w-70px">   <?php common::printOrderLink('version',     $orderBy, $vars, $lang->design->version);?></th>
            <th class="text-left w-120px">  <?php common::printOrderLink('createdBy',   $orderBy, $vars, $lang->design->createdBy);?></th>
            <th class="text-left w-150px">  <?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->design->createdDate);?></th>
            <th class="text-left w-120px">  <?php common::printOrderLink('assignedTo',  $orderBy, $vars, $lang->design->assignedTo);?></th>
            <th class="text-center w-100px"><?php echo $lang->design->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($designs as $design):?>
          <tr>
            <td class='text-left'><?php printf('%03d', $design->id);?></td>
            <td class='text-left'><?php echo zget($lang->design->typeList, $design->type);?></td>
            <td class='text-left' title="<?php echo $design->name;?>"><?php echo html::a($this->createLink('design', 'view', "id={$design->id}"), $design->name);?></td>
            <td class='text-left'><?php echo zget($lang->design->submission, $design->commit);?></td>
            <td class='text-left'><?php echo zget($lang->design->version, $design->version);?></td>
            <td class='text-left'><?php echo $design->createdBy;?></td>
            <td class='text-left'><?php echo $design->createdDate;?></td>
            <td class='text-left'><?php echo $design->assignedTo;?></td>
            <td class='c-actions text-center'>
              <?php
              $vars = "design={$design->id}";
              common::printIcon('design', 'edit',   $vars, $design, 'list', 'fork', '', '', '', '', '', $design->program);
              common::printIcon('design', 'commit', $vars, $design, 'list', 'link', '', 'iframe showinonlybody', true);
              common::printIcon('design', 'delete', $vars, $design, 'list', 'trash', 'hiddenwin', '', '', '', '', $design->program);
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
   </form>
   <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
