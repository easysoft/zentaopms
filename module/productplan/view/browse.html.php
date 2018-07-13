<?php
/**
 * The browse view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     plan
 * @version     $Id: browse.html.php 4707 2013-05-02 06:57:41Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->productplan->confirmDelete)?>
<?php js::set('browseType', $browseType);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <?php $label   = "<span class='text'>{$menuItem->text}</span>";?>
    <?php $label  .= $menuItem->name == $browseType ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';?>
    <?php $active  = $menuItem->name == $browseType ? 'btn-active-text' : '';?>
    <?php echo html::a($this->inlink('browse', "productID=$productID&branch=$branch&browseType={$menuItem->name}"), $label, '', "class='btn btn-link $active' id='{$menuItem->name}'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('productplan', 'create', "productID=$product->id&branch=$branch", "<i class='icon icon-plus'></i> {$lang->productplan->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($plans)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->productplan->noPlan;?></span>
      <?php if(common::hasPriv('productplan', 'create')):?>
      <span class="text-muted"><?php echo $lang->youCould;?></span>
      <?php echo html::a($this->createLink('productplan', 'create', "productID=$product->id&branch=$branch"), "<i class='icon icon-plus'></i> " . $lang->productplan->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table table-productplan' data-ride='table' method='post' id='productplanForm' action='<?php echo inlink('batchEdit', "productID=$product->id&branch=$branch")?>'>
    <table class='table has-sort-head' id="productplanList">
      <thead>
      <?php $vars = "productID=$productID&branch=$branch&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <tr>
        <th class='c-id'>
          <?php if(common::hasPriv('productplan', 'batchEdit')):?>
          <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
            <label></label>
          </div>
          <?php endif;?>
          <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
        </th>
        <th class='w-140px'><?php common::printOrderLink('title', $orderBy, $vars, $lang->productplan->title);?></th>
        <?php if($this->session->currentProductType != 'normal'):?>
        <th class='w-100px'><?php common::printOrderLink('branch',$orderBy, $vars, $lang->product->branch);?></th>
        <?php endif;?>
        <th class='w-100px'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->productplan->begin);?></th>
        <th class='w-100px'><?php common::printOrderLink('end',   $orderBy, $vars, $lang->productplan->end);?></th>
        <th class='w-70px'> <?php echo $lang->productplan->stories;?></th>
        <th class='w-60px'> <?php echo $lang->productplan->bugs;?></th>
        <th class='w-60px'> <?php echo $lang->productplan->hour;?></th>
        <th class='w-60px'> <?php echo $lang->productplan->project;?></th>
        <th>                <?php echo $lang->productplan->desc;?></th>
        <th class='c-actions-5'><?php echo $lang->actions;?></th>
      </tr>
      </thead>
      <tbody>
      <?php $this->loadModel('file');?>
      <?php foreach($plans as $plan):?>
      <?php $plan = $this->file->replaceImgURL($plan, 'desc');?>
      <tr>
        <td class='cell-id'>
          <?php if(common::hasPriv('productplan', 'batchEdit')):?>
          <?php echo html::checkbox('planIDList', array($plan->id => sprintf('%03d', $plan->id)));?>
          <?php else:?>
          <?php echo sprintf('%03d', $plan->id);?>
          <?php endif;?>
        </td>
        <td class='text-left' title="<?php echo $plan->title?>"><?php echo html::a(inlink('view', "id=$plan->id"), $plan->title);?></td>
        <?php if($this->session->currentProductType != 'normal'):?>
        <td><?php echo $branches[$plan->branch];?></td>
        <?php endif;?>
        <td><?php echo $plan->begin == '2030-01-01' ? $lang->productplan->future : $plan->begin;?></td>
        <td><?php echo $plan->end == '2030-01-01' ? $lang->productplan->future : $plan->end;?></td>
        <td class='text-center'><?php echo $plan->stories;?></td>
        <td class='text-center'><?php echo $plan->bugs;?></td>
        <td class='text-center'><?php echo $plan->hour;?></td>
        <td class='text-center'><?php if(!empty($plan->projectID)) echo html::a(helper::createLink('project', 'task', 'projectID=' . $plan->projectID), '<i class="icon-search"></i>');?></td>
        <td title='<?php echo strip_tags($plan->desc)?>' class='text-left content'><?php echo nl2br(strip_tags($plan->desc));?></td>
        <td class='c-actions'>
          <?php
          if(common::hasPriv('project', 'create')) echo html::a(helper::createLink('project', 'create', "projectID=&copyProjectID=&planID=$plan->id"), '<i class="icon-plus"></i>', '', "class='btn' title='{$lang->project->create}'");
          if(common::hasPriv('productplan', 'linkStory')) echo html::a(inlink('view', "planID=$plan->id&type=story&orderBy=id_desc&link=true"), '<i class="icon-link"></i>', '', "class='btn' title='{$lang->productplan->linkStory}'");
          if(common::hasPriv('productplan', 'linkBug') and $config->global->flow != 'onlyStory') echo html::a(inlink('view', "planID=$plan->id&type=bug&orderBy=id_desc&link=true"), '<i class="icon-bug"></i>', '', "class='btn' title='{$lang->productplan->linkBug}'");
          common::printIcon('productplan', 'edit', "planID=$plan->id", $plan, 'list');

          if(common::hasPriv('productplan', 'delete', $plan))
          {
              $deleteURL = $this->createLink('productplan', 'delete', "planID=$plan->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"productplanList\",confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn btn-icon' title='{$lang->productplan->delete}'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if(common::hasPriv('productplan', 'batchEdit')):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->edit, '', 'btn');?>
      </div>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
