<?php
/**
 * The browsebykanban view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     plan
 * @version     $Id: browsebykanban.html.php 4707 2021-12-27 16:07:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/kanban.html.php';?>
<style>
#allPlans {display: block; height: 25px; margin: 0 auto; text-align: center;}
#allPlans > i, #allPlans > span {display: inline-block; vertical-align: middle; line-height: 25px;}
#allPlans > i {color: #999;}
#branchBox {width: 120px;}
#branch_chosen .icon-delay {padding-right: 10px; font-size: 15px;}
#kanbanContainer {padding-bottom: 0; margin-bottom: 0;}
</style>
<?php js::set('kanbanData', $kanbanData);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php if($product->type == 'normal'):?>
    <div id='allPlans'>
      <i class='icon icon-delay'></i>
      <span><?php echo $lang->productplan->all . ' ' . $planCount;?></span>
    </div>
  <?php else:?>
    <div id='branchBox'>
      <?php echo html::select('branch', $branches, $branchID, "class='form-control chosen control-branch'");?>
    </div>
  <?php endif;?>
  </div>
  <div class="btn-toolbar pull-right">
    <div class="btn-group panel-actions">
      <?php echo html::a('#',"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon switchButton' title='{$lang->productplan->list}' data-type='bylist'");?>
      <?php echo html::a('#',"<i class='icon-kanban'></i> &nbsp;", '', "class='btn btn-icon text-primary switchButton' title='{$lang->productplan->kanban}' data-type='bykanban'");?>
    </div>
    <?php if(common::canModify('product', $product)):?>
    <?php common::printLink('productplan', 'create', "productID=$product->id&branch=$branch", "<i class='icon icon-plus'></i> {$lang->productplan->create}", '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
</div>
<div class='panel' id='kanbanContainer'>
  <div class='panel-body'>
    <div id='kanban'></div>
  </div>
</div>
