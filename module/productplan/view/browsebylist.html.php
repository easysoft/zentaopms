<?php
/** * The browsebylist view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     plan
 * @version     $Id: browsebylist.html.php 4707 2013-05-02 06:57:41Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php $unfoldPlans = isset($config->productplan->browse->unfoldPlans) ? json_decode($config->productplan->browse->unfoldPlans, true) : array();?>
<?php $unfoldPlans = zget($unfoldPlans, $productID, array());?>
<?php js::set('unfoldPlans', $unfoldPlans);?>
<style>
.c-actions {width: 250px;}
#productplanList .c-actions .btn+.btn {margin-left: -1px;}
#productplanList .c-actions .btn {display: block; float: left;}
</style>
<?php $isProjectplan = $app->rawModule == 'projectplan';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php common::sortFeatureMenu();?>
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <?php $label   = "<span class='text'>{$menuItem->text}</span>";?>
    <?php $label  .= $menuItem->name == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';?>
    <?php $active  = $menuItem->name == $browseType ? 'btn-active-text' : '';?>
    <?php $params  = "productID=$productID&branch=&browseType={$menuItem->name}";?>
    <?php $link    = $isProjectplan ? $this->createLink('projectplan', 'browse', $params) : $this->createLink('productplan', 'browse', $params);?>
    <?php echo html::a($link, $label, '', "class='btn btn-link $active' id='{$menuItem->name}'");?>
    <?php endforeach;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class="btn-group panel-actions">
      <?php echo html::a('#',"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon text-primary switchButton' title='{$lang->productplan->list}' data-type='list'");?>
      <?php echo html::a('#',"<i class='icon-kanban'></i> &nbsp;", '', "class='btn btn-icon switchButton' title='{$lang->productplan->kanban}' data-type='kanban'");?>
    </div>
    <?php if(common::canModify('product', $product)):?>
    <?php common::printLink($app->rawModule, 'create', "productID=$product->id&branch=$branch", "<i class='icon icon-plus'></i> {$lang->productplan->create}", '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module='productplan'></div>
<div id="mainContent">
  <?php $totalParent      = 0;?>
  <?php $totalChild       = 0;?>
  <?php $totalIndependent = 0;?>
  <?php if(empty($plans)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->productplan->noPlan;?></span>
      <?php if(common::canModify('product', $product) and empty($productPlansNum)):?>
      <?php
      if(common::hasPriv('projectplan', 'create') and $isProjectplan)
      {
          echo html::a($this->createLink('projectplan', 'create', "productID=$product->id&branch=$branch"), "<i class='icon icon-plus'></i> " . $lang->productplan->create, '', "class='btn btn-info'");
      }
      elseif(common::hasPriv('productplan', 'create'))
      {
          echo html::a($this->createLink('productplan', 'create', "productID=$product->id&branch=$branch"), "<i class='icon icon-plus'></i> " . $lang->productplan->create, '', "class='btn btn-info'");
      }
      ?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table table-productplan' method='post' id='productplanForm' action='<?php echo inlink('batchEdit', "productID=$product->id&branch=$branch")?>' data-preserve-nested='true'>
    <table class='table has-sort-head' id="productplanList">
      <thead>
      <?php $vars = "productID=$productID&branch=$branch&browseType=$browseType&queryID=$queryID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <tr>
        <th class='c-id'>
          <?php if(common::hasPriv('productplan', 'batchEdit') or common::hasPriv('productplan', 'batchChangeStatus')):?>
          <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
            <label></label>
          </div>
          <?php endif;?>
          <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
        </th>
        <th class='c-title'><?php common::printOrderLink('title', $orderBy, $vars, $lang->productplan->title);?></th>
        <?php if($browseType == 'all'):?>
        <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->productplan->status);?></th>
        <?php endif;?>
        <?php if($this->session->currentProductType != 'normal'):?>
        <th class='c-branch'><?php common::printOrderLink('branch',$orderBy, $vars, $lang->product->branchName[$product->type]);?></th>
        <?php endif;?>
        <th class='c-date'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->productplan->begin);?></th>
        <th class='c-date'><?php common::printOrderLink('end',   $orderBy, $vars, $lang->productplan->end);?></th>
        <th class='c-story text-center'><?php echo $lang->productplan->stories;?></th>
        <th class='c-bug text-center'><?php echo $lang->productplan->bugs;?></th>
        <th class='c-hour text-center'><?php echo $lang->productplan->hour;?></th>
        <th class='c-execution c-actions'><?php echo $lang->productplan->execution;?></th>
        <th class='c-desc'><?php echo $lang->productplan->desc;?></th>
        <?php
        $extendFields = $this->productplan->getFlowExtendFields();
        foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
        ?>
        <th class='c-actions text-center'><?php echo $lang->actions;?></th>
      </tr>
      </thead>
      <tbody>
      <?php $this->loadModel('file');?>
      <?php foreach($plans as $plan):?>
      <?php
      $canBeChanged = common::canBeChanged('plan', $plan);
      $attribute    = $canBeChanged ? '' : 'disabled';

      $plan = $this->file->replaceImgURL($plan, 'desc');
      if($plan->parent == '-1')
      {
          $parent   = $plan->id;
          $children = isset($plan->children) ? $plan->children : 0;

          $totalParent ++;
      }
      if($plan->parent == 0) $parent = 0;
      if(!empty($parent) and $plan->parent > 0 and $plan->parent != $parent) $parent = 0;
      if($plan->parent <= 0) $i = 0;
      if($plan->parent > 0) $totalChild ++;
      if($plan->parent == 0) $totalIndependent ++;

      $class = '';
      if(!empty($parent) and $plan->parent == $parent)
      {
          $class  = "table-children parent-{$parent}";
          $class .= $i == 0 ? ' table-child-top' : '';
          $class .= ($i + 1 == $children) ? ' table-child-bottom' : '';
          $i++;
      }
      ?>
      <tr class='<?php echo $class;?>' data-id="<?php echo $plan->id;?>" data-parent="<?php echo $plan->parent;?>">
        <td class='cell-id'>
          <?php if(common::hasPriv('productplan', 'batchEdit') or common::hasPriv('productplan', 'batchChangeStatus')):?>
          <?php echo html::checkbox('planIDList', array($plan->id => ''), '', $attribute);?>
          <?php echo $isProjectplan ? html::a(helper::createLink('projectplan', 'view', "planID=$plan->id"), sprintf('%03d', $plan->id)) : html::a(helper::createLink('productplan', 'view', "planID=$plan->id"), sprintf('%03d', $plan->id));?>
          <?php else:?>
          <?php echo sprintf('%03d', $plan->id);?>
          <?php endif;?>
        </td>
        <td class='c-title text-left<?php if(isset($plan->children)) echo ' has-child';?>' title="<?php echo $plan->title?>">
          <?php
          $class   = '';
          $expired = '';
          if($plan->expired and in_array($plan->status, array('wait', 'doing')))
          {
              $class  .= ' expired';
              $expired = "<span class='label label-danger label-badge'>{$this->lang->productplan->expired}</span>";
          }

          echo "<div class='plan-name has-prefix {$class}'>";
          if($plan->parent > 0) echo "<span class='label label-badge label-light' title='{$this->lang->productplan->children}'>{$this->lang->productplan->childrenAB}</span>";
          echo html::a($this->createLink($app->rawModule, 'view', "id=$plan->id"), $plan->title);
          if(!empty($expired)) echo $expired;
          if(isset($plan->children)) echo '<a class="task-toggle" data-id="' . $plan->id . '"><i class="icon icon-angle-right"></i></a>';
          echo '</div>';
          ?>
        </td>
        <?php if($browseType == 'all'):?>
        <td><?php echo zget($statusList, $plan->status)?></td>
        <?php endif;?>
        <?php if($this->session->currentProductType != 'normal'):?>
        <?php $planBranches = '';?>
        <?php foreach(explode(',', $plan->branch) as $branchID) $planBranches .= $branchOption[$branchID] . ',';?>
        <td class='c-branch' title='<?php echo trim($planBranches, ',');?>'><?php echo trim($planBranches, ',');?></td>
        <?php endif;?>
        <td><?php echo $plan->begin == $config->productplan->future ? $lang->productplan->future : $plan->begin;?></td>
        <td><?php echo $plan->end == $config->productplan->future ? $lang->productplan->future : $plan->end;?></td>
        <td class='text-center'><?php echo $plan->stories;?></td>
        <td class='text-center'><?php echo $plan->bugs;?></td>
        <td class='text-center'><?php echo $plan->hour;?></td>
        <td class='text-center c-actions execution-links'>
          <?php
          if(!empty($plan->projects))
          {
              if(count($plan->projects) === 1)
              {
                  $executionID = key($plan->projects);
                  echo html::a(helper::createLink('execution', 'task', "executionID=$executionID"), '<i class="icon-run text-primary"></i>', '', "title='{$plan->projects[$executionID]->name}' class='btn'");
              }
              else
              {
                  $executionHtml  = '<div class="popover right">';
                  $executionHtml .= '<div class="arrow"></div>';
                  $executionHtml .= '<div class="popover-content">';
                  $executionHtml .= '<ul class="execution-tip">';
                  foreach($plan->projects as $executionID => $execution) $executionHtml .=  '<li>' . html::a(helper::createLink('execution', 'task', "executionID=$executionID"), $execution->name, '', "class='execution-link' title='{$execution->name}'") . '</li>';
                  $executionHtml .= '</ul>';
                  $executionHtml .= '</div>';
                  $executionHtml .= '</div>';
                  echo "<a href='javascript:;' class='btn execution-popover'><i class='icon-run text-primary'></i></a>";
                  echo $executionHtml;
              }
          }
          ?>
        </td>
        <td class='text-left content'>
          <?php $desc = trim(strip_tags(str_replace(array('</p>', '<br />', '<br>', '<br/>'), "\n", str_replace(array("\n", "\r"), '', $plan->desc)), '<img>'));?>
          <div title='<?php echo $desc;?>'><?php echo nl2br($desc);?></div>
        </td>
        <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $plan) . "</td>";?>
        <td class='c-actions'><?php echo $this->productplan->buildOperateMenu($plan, 'browse'); ?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if(common::hasPriv('productplan', 'batchEdit') or common::hasPriv('productplan', 'batchChangeStatus')):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
      <?php if(common::hasPriv('productplan', 'batchEdit')):?>
      <?php $actionLink = $this->inlink('batchEdit', "productID=$product->id&branch=$branch");?>
      <?php echo html::commonButton($lang->edit, "data-form-action='$actionLink'");?>
      <?php endif;?>
      <?php if(common::hasPriv('productplan', 'batchChangeStatus')):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->productplan->planStatus;?> <span class="caret"></span></button>
          <div class="dropdown-menu search-list">
            <div class="list-group">
              <?php
              foreach($lang->productplan->statusList as $key => $status)
              {
                  $isHiddenwin = $key == 'closed' ? '' : 'hiddenwin';

                  $actionLink = $this->createLink('productplan', 'batchChangeStatus', "status=$key&productID=$product->id");
                  echo html::a('javascript:;', $status, '', "onclick=\"setFormAction('$actionLink', '$isHiddenwin')\"");
              }
              ?>
            </div>
          </div>
        </div>
      <?php endif;?>
      </div>
      <div class="table-statistic"><?php echo sprintf($lang->productplan->summary, count($plans), $totalParent, $totalChild, $totalIndependent);?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<script>
$(function()
{
    var pageSummary    = '<?php echo sprintf($lang->productplan->summary, count($plans), $totalParent, $totalChild, $totalIndependent);?>';
    var checkedSummary = '<?php echo $lang->productplan->checkedSummary?>';
    $('#productplanForm').table(
    {
        replaceId: 'productplanList',
        statisticCreator: function(table)
        {
            var $table        = table.getTable();
            var $checkedRows  = $table.find('tbody>tr.checked');
            var checkedTotal  = $checkedRows.length;
            var checkedParent = $checkedRows.filter("[data-parent=-1]").length;
            var checkedNormal = $checkedRows.filter("[data-parent=0]").length;
            var checkedChild  = checkedTotal - checkedParent - checkedNormal;
            var summary       = checkedSummary.replace('%total%', checkedTotal)
                .replace('%parent%', checkedParent)
                .replace('%child%', checkedChild)
                .replace('%independent%', checkedNormal);

            return checkedTotal ? summary : pageSummary;
        }
    });
});
</script>
