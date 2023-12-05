<?php
/**
 * The browsebykanban view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
.kanban-item:hover > .header > .actions {opacity: 1;}
.kanban-card .header .titleBox {display: flex; width: 90%; float: left;}
.kanban-card .header .titleBox > span {flex: none;}
.kanban-card .header .actions {float: right; opacity: 0;}
.kanban-card .header .actions > a:hover {background-color: rgba(0,0,0,.075); opacity: 1;}
.kanban-card:hover > .header > .actions {opacity: 1;}
.kanban-card .title {white-space: nowrap; overflow: hidden;}
.kanban-card .expired {margin-left: 2px;}
.kanban-card .desc {overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #838a9d; padding-top: 5px;}
.kanban-card .actions > a {display: block; float: left; width: 20px; height: 20px; line-height: 20px; text-align: center; border-radius: 4px; opacity: .7;}
.dropdown-menu > li > .disabled {pointer-events: none; color: #838a9d;}
</style>
<?php js::set('kanbanData', $kanbanData);?>
<?php js::set('rawModule', $app->rawModule);?>
<?php js::set('users', $users);?>
<?php js::set('noAssigned', $lang->productplan->noAssigned);?>
<?php js::set('productplanLang', $lang->productplan);?>
<?php js::set('priv', array('canAssignCard' => common::hasPriv('kanban', 'assigncard')));?>
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
    <div class='btn-group'>
      <?php echo html::a('javascript:;', $lang->productplan->orderList[$orderBy] . ' <span class="caret"></span>', '', "class='btn btn-link' data-toggle='dropdown'");?>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
      <?php foreach($lang->productplan->orderList as $order => $label):?>
      <?php $active = $orderBy == $order ? 'active' : '';?>
        <li class='<?php echo $active;?>'><?php echo html::a($this->createLink($app->rawModule, 'browse', "productID=$productID&branch=$branchID&browseType=$browseType&queryID=$queryID&orderBy=$order"), $label);?></li>
      <?php endforeach;?>
      </ul>
    </div>
    <div class="btn-group panel-actions">
      <?php echo html::a('javascript:;',"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon switchButton' title='{$lang->productplan->list}' data-type='list'");?>
      <?php echo html::a('javascript:;',"<i class='icon-kanban'></i> &nbsp;", '', "class='btn btn-icon text-primary switchButton' title='{$lang->productplan->kanban}' data-type='kanban'");?>
    </div>
    <?php if(common::canModify('product', $product)):?>
    <?php common::printLink($app->rawModule, 'create', "productID=$product->id&branch=$branch", "<i class='icon icon-plus'></i> {$lang->productplan->create}", '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
</div>
<div class='panel' id='kanbanContainer'>
  <div class='panel-body'>
    <div id='kanban'></div>
  </div>
</div>
