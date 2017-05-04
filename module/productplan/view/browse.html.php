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
<div id='featurebar'>
  <ul class='nav'>
    <li>
      <?php if($product->type !== 'normal'):?>
      <div class='label-angle<?php if($branch) echo ' with-close';?>'>
      <?php
      echo $branches[$branch];
      if($branch)
      {
          $removeLink = inlink('browse', "productID=$productID&branch=0&browseType=$browseType&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}");
          echo html::a($removeLink, "<i class='icon icon-remove'></i>", '', "class='text-muted'");
      }
      ?>
      </div>
      <?php endif;?>
    </li>
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <li id='<?php echo $menuItem->name?>Tab'><?php echo html::a($this->inlink('browse', "productID=$productID&branch=$branch&browseType={$menuItem->name}"), $menuItem->text);?></li>
    <?php endforeach;?>
  </ul>
  <div class='actions'>
    <?php common::printIcon('productplan', 'create', "productID=$product->id&branch=$branch", '', 'button', 'plus');?>
  </div>
</div>
<form method='post' id='productplanForm' action='<?php echo inlink('batchEdit', "productID=$product->id&branch=$branch")?>'>
<table class='table table-bordered table-selectable' id="productplan">
  <thead>
  <?php $vars = "productID=$productID&branch=$branch&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <tr class='colhead'>
    <th class='w-id'>    <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
    <th>                 <?php common::printOrderLink('title', $orderBy, $vars, $lang->productplan->title);?></th>
    <?php if($this->session->currentProductType != 'normal'):?>
    <th class='w-100px'> <?php common::printOrderLink('branch',$orderBy, $vars, $lang->product->branch);?></th>
    <?php endif;?>
    <th class='w-p50'>   <?php echo $lang->productplan->desc;?></th>
    <th class='w-100px'> <?php common::printOrderLink('begin', $orderBy, $vars, $lang->productplan->begin);?></th>
    <th class='w-100px'> <?php common::printOrderLink('end',   $orderBy, $vars, $lang->productplan->end);?></th>
    <th class="w-110px {sorter: false}"><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($plans as $plan):?>
  <tr class='text-center'>
    <td class='cell-id'>
      <input type='checkbox' name='planIDList[<?php echo $plan->id;?>]' value='<?php echo $plan->id;?>' /> 
      <?php echo $plan->id;?>
    </td>
    <td class='text-left' title="<?php echo $plan->title?>"><?php echo html::a(inlink('view', "id=$plan->id"), $plan->title);?></td>
    <?php if($this->session->currentProductType != 'normal'):?>
    <td><?php echo $branches[$plan->branch];?></td>
    <?php endif;?>
    <td class='text-left content'><div class='article-content'><?php echo $plan->desc;?></div></td>
    <td><?php echo $plan->begin;?></td>
    <td><?php echo $plan->end;?></td>
    <td class='text-center'>
      <?php
      if(common::hasPriv('productplan', 'linkStory')) echo html::a(inlink('view', "planID=$plan->id&type=story&orderBy=id_desc&link=true"), '<i class="icon-link"></i>', '', "class='btn-icon' title='{$lang->productplan->linkStory}'");
      if(common::hasPriv('productplan', 'linkBug') and $config->global->flow != 'onlyStory') echo html::a(inlink('view', "planID=$plan->id&type=bug&orderBy=id_desc&link=true"), '<i class="icon-bug"></i>', '', "class='btn-icon' title='{$lang->productplan->linkBug}'");
      common::printIcon('productplan', 'edit', "planID=$plan->id", '', 'list');

      if(common::hasPriv('productplan', 'delete'))
      {
          $deleteURL = $this->createLink('productplan', 'delete', "planID=$plan->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"productplan\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->productplan->delete}'");
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='<?php echo $this->session->currentProductType == 'normal' ? '6' : '7';?>'>
        <div class='table-actions clearfix'>
          <?php echo html::selectButton();?>
          <?php if(common::hasPriv('productplan', 'batchEdit')) echo html::submitButton($lang->edit);?>
        </div>
        <?php $pager->show();?>
      </td>
    </tr>
  </tfoot>
</table>
</form>
<script>
$('#' + browseType + 'Tab').addClass('active');
$(function(){fixedTfootAction('#productplanForm')});
$(function(){fixedTheadOfList('#productplan')});
</script>
<?php include '../../common/view/footer.html.php';?>
