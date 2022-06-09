<?php
/** * The browsebylist view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     plan
 * @version     $Id: browsebylist.html.php 4707 2013-05-02 06:57:41Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<style> .c-actions {width: 240px;} </style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <?php $label   = "<span class='text'>{$menuItem->text}</span>";?>
    <?php $label  .= $menuItem->name == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';?>
    <?php $active  = $menuItem->name == $browseType ? 'btn-active-text' : '';?>
    <?php echo html::a($this->inlink('browse', "productID=$productID&branch=&browseType={$menuItem->name}"), $label, '', "class='btn btn-link $active' id='{$menuItem->name}'");?>
    <?php endforeach;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i><?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class="btn-group panel-actions">
      <?php echo html::a('#',"<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon text-primary switchButton' title='{$lang->productplan->list}' data-type='list'");?>
      <?php echo html::a('#',"<i class='icon-kanban'></i> &nbsp;", '', "class='btn btn-icon switchButton' title='{$lang->productplan->kanban}' data-type='kanban'");?>
    </div>
    <?php if(common::canModify('product', $product)):?>
    <?php common::printLink('productplan', 'create', "productID=$product->id&branch=$branch", "<i class='icon icon-plus'></i> {$lang->productplan->create}", '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module='productplan'></div>
<div id="mainContent">
  <?php if(empty($plans)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->productplan->noPlan;?></span>
      <?php if(common::canModify('product', $product) and common::hasPriv('productplan', 'create')):?>
      <?php echo html::a($this->createLink('productplan', 'create', "productID=$product->id&branch=$branch"), "<i class='icon icon-plus'></i> " . $lang->productplan->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table table-productplan' data-ride='table' method='post' id='productplanForm' action='<?php echo inlink('batchEdit', "productID=$product->id&branch=$branch")?>'>
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
        <th class='c-branch'><?php common::printOrderLink('branch',$orderBy, $vars, $lang->productplan->branch);?></th>
        <?php endif;?>
        <th class='c-date'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->productplan->begin);?></th>
        <th class='c-date'><?php common::printOrderLink('end',   $orderBy, $vars, $lang->productplan->end);?></th>
        <th class='c-story text-center'><?php echo $lang->productplan->stories;?></th>
        <th class='c-bug text-center'><?php echo $lang->productplan->bugs;?></th>
        <th class='c-hour text-center'><?php echo $lang->productplan->hour;?></th>
        <th class='c-execution text-center'><?php echo $lang->productplan->execution;?></th>
        <th><?php echo $lang->productplan->desc;?></th>
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
      }
      if($plan->parent == 0) $parent = 0;
      if(!empty($parent) and $plan->parent > 0 and $plan->parent != $parent) $parent = 0;
      if($plan->parent <= 0) $i = 0;

      $class = '';
      if(!empty($parent) and $plan->parent == $parent)
      {
          $class  = "table-children parent-{$parent}";
          $class .= $i == 0 ? ' table-child-top' : '';
          $class .= ($i + 1 == $children) ? ' table-child-bottom' : '';
          $i++;
      }
      ?>
      <tr class='<?php echo $class;?>'>
        <td class='cell-id'>
          <?php if(common::hasPriv('productplan', 'batchEdit') or common::hasPriv('productplan', 'batchChangeStatus')):?>
          <?php echo html::checkbox('planIDList', array($plan->id => ''), '', $attribute) . html::a(helper::createLink('productplan', 'view', "planID=$plan->id"), sprintf('%03d', $plan->id));?>
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
          echo html::a(inlink('view', "id=$plan->id"), $plan->title);
          if(!empty($expired)) echo $expired;
          if(isset($plan->children)) echo '<a class="task-toggle" data-id="' . $plan->id . '"><i class="icon icon-angle-double-right"></i></a>';
          echo '</div>';
          ?>
        </td>
        <?php if($browseType == 'all'):?>
        <td><?php echo zget($statusList, $plan->status)?></td>
        <?php endif;?>
        <?php if($this->session->currentProductType != 'normal'):?>
        <td class='c-branch' title='<?php echo $branchOption[$plan->branch];?>'><?php if($plan->parent != '-1') echo $branchOption[$plan->branch];?></td>
        <?php endif;?>
        <td><?php echo $plan->begin == $config->productplan->future ? $lang->productplan->future : $plan->begin;?></td>
        <td><?php echo $plan->end == $config->productplan->future ? $lang->productplan->future : $plan->end;?></td>
        <td class='text-center'><?php echo $plan->stories;?></td>
        <td class='text-center'><?php echo $plan->bugs;?></td>
        <td class='text-center'><?php echo $plan->hour;?></td>
        <td class='text-center'><?php if(!empty($plan->projectID)) echo html::a(helper::createLink('execution', 'task', 'projectID=' . $plan->projectID), '<i class="icon-search"></i>');?></td>
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
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
