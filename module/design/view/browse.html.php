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
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    foreach($lang->design->featureBar as $key => $label)
    {
        $active = $key == $type ? 'btn-active-text' : '';
        $recTotalLabel = $key == $type ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        echo html::a(inlink('browse', "productID={$productID}&type=$key"),  "<span class='text'>$label</span>"  . $recTotalLabel, '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->design->byQuery;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('design', 'batchCreate', "productID=$productID", "<i class='icon icon-plus'></i>" . $lang->design->batchCreate, '', "class='btn btn-primary'");?>
    <?php if(common::hasPriv('design', 'create')) echo html::a($this->createLink('design', 'create', "productID=$productID&designID=0"), "<i class='icon icon-plus'></i> {$lang->design->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div class="cell<?php if($type == 'bySearch') echo ' show';?>" id="queryBox" data-module='design'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($designs)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->design->noDesign;?></span></p>
  </div>
  <?php else:?>
  <form id='designFrom' method='post' class="main-table">
    <table class='table has-sort-head table-fixrd' id="designTable">
      <?php $vars = "productID=$productID&type=$type&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";?>
        <thead>
          <tr>
            <th class="text-left w-60px">   <?php common::printOrderLink('id',          $orderBy, $vars, $lang->idAB);?></th>
            <th class="text-left w-100px">  <?php common::printOrderLink('type',        $orderBy, $vars, $lang->design->type);?></th>
            <th class="text-left">          <?php common::printOrderLink('name',        $orderBy, $vars, $lang->design->name);?></th>
            <th class="text-left w-150px">  <?php common::printOrderLink('commit',      $orderBy, $vars, $lang->design->submission);?></th>
            <th class="text-left w-120px">  <?php common::printOrderLink('createdBy',   $orderBy, $vars, $lang->design->createdBy);?></th>
            <th class="text-left w-150px">  <?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->design->createdDate);?></th>
            <th class="text-left w-120px">  <?php common::printOrderLink('assignedTo',  $orderBy, $vars, $lang->design->assignedTo);?></th>
            <th class="text-center w-100px"><?php echo $lang->design->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($designs as $design):?>
          <tr>
            <td><?php printf('%03d', $design->id);?></td>
            <td><?php echo zget($lang->design->typeList, $design->type);?></td>
            <td title="<?php echo $design->name;?>" style="overflow:hidden"><?php echo html::a($this->createLink('design', 'view', "id={$design->id}"), $design->name);?></td>
            <td style="overflow:hidden"><?php echo $design->commit;?></td>
            <td><?php echo $design->createdBy;?></td>
            <td><?php echo substr($design->createdDate, 0, 11);?></td>
            <td><?php echo $design->assignedTo;?></td>
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
