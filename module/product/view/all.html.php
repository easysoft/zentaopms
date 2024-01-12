<?php
/**
 * The html productlist file of productlist method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('productLines', $productLines); ?>
<?php $canBatchEdit = common::hasPriv('product', 'batchEdit'); ?>
<?php $canSort      = common::hasPriv('product', 'updateOrder');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolBar pull-left">
    <?php common::sortFeatureMenu();?>
    <?php foreach($lang->product->featureBar['all'] as $key => $label):?>
    <?php $recTotalLabel = $browseType == $key ? " <span class='label label-light label-badge'>{$recTotal}</span>" : '';?>
    <?php echo html::a(inlink("all", "browseType=$key&orderBy=$orderBy"), "<span class='text'>{$label}</span>" . $recTotalLabel, '', "class='btn btn-link' id='{$key}Tab'");?>
    <?php endforeach;?>
    <?php if($canBatchEdit) echo html::checkbox('showEdit', array('1' => $lang->product->edit), $showBatchEdit);?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'export', "status=$browseType&orderBy=$orderBy", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'", true, true)?>
    <?php if(in_array($config->systemMode, array('ALM', 'PLM'))):?>
    <?php common::printLink('product', 'manageLine', '', "<i class='icon-edit'></i> &nbsp;" . $lang->product->line, '', 'class="btn btn-link iframe"', '', true);?>
    <?php endif;?>
    <?php common::printLink('product', 'create', '', '<i class="icon icon-plus"></i>' . $lang->product->create, '', 'class="btn btn-primary create-product-btn"');?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <?php if(empty($productStructure)):?>
  <div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module='product'></div>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->product->noProduct;?></span></p>
  </div>
  <?php else:?>
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module='product'></div>
    <form class="main-table table-product" data-ride="table" data-nested='true' id="productListForm" method="post" action='<?php echo inLink('batchEdit', '');?>' data-preserve-nested='true' data-expand-nest-child='true'>
      <table id="productList" class="table has-sort-head table-nested table-fixed">
        <?php $vars = "browseType=$browseType&orderBy=%s";?>
        <thead>
          <tr class="text-center">
            <?php if($canBatchEdit):?>
            <th class='text-left c-checkbox' rowspan="2">
              <?php echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";?>
            </th>
            <?php endif;?>
            <th class='table-nest-title text-left c-name' rowspan="2">
              <?php if(in_array($config->systemMode, array('ALM', 'PLM'))):?>
              <a class='table-nest-toggle table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse; ?>'></a>
              <?php endif;?>
              <?php common::printOrderLink('name', $orderBy, $vars, $lang->product->name);?>
            </th>
            <th class='c-PO' rowspan="2">
              <?php common::printOrderLink('PO', $orderBy, $vars, $lang->product->manager);?>
            </th>
            <th class="c-story" colspan="5"><?php echo $lang->story->story;?></th>
            <th class="c-bug" colspan="2"><?php echo $lang->bug->common;?></th>
            <th class="c-plan"  rowspan="2"><?php echo $lang->product->plan;?></th>
            <th class="c-release"  rowspan="2"><?php echo $lang->product->release;?></th>
            <?php
            $extendFields = $this->product->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th rowspan='2'>{$extendField->name}</th>";
            ?>
            <th class='c-actions' rowspan="2"><?php echo $lang->actions;?></th>
          </tr>
          <tr class="text-center">
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->story->draft;?></th>
            <th><?php echo $lang->story->activate;?></th>
            <th><?php echo $lang->story->change;?></th>
            <th><?php echo $lang->story->statusList['reviewing'];?></th>
            <th><div class='en-wrap-text'><?php echo $lang->story->completeRate;?></div></th>
            <th style="border-left: 1px solid #ddd;"><?php echo $lang->bug->activate;?></th>
            <th><?php echo $lang->bug->fixedRate;?></th>
          </tr>
        </thead>
        <tbody id="productTableList">
        <?php $lineNames = array();?>
        <?php foreach($productStructure as $programID => $program):?>
        <?php
        $trAttrs  = "data-id='program.$programID' data-parent='0' data-nested='true'";
        $trClass  = 'is-top-level table-nest-child text-center';
        $trAttrs .= " class='$trClass'";
        ?>
          <?php
          if(isset($programLines[$programID]))
          {
              foreach($programLines[$programID] as $lineID => $lineName)
              {
                  if(!isset($program[$lineID]))
                  {
                      $program[$lineID] = array();
                      $program[$lineID]['product']  = '';
                      $program[$lineID]['lineName'] = $lineName;
                  }
              }
          }
          ?>
          <?php if(isset($program['programName']) and in_array($config->systemMode, array('ALM', 'PLM'))):?>
          <tr class="row-program" <?php echo $trAttrs;?>>
            <?php if($canBatchEdit):?>
            <td class='c-checkbox'><div class='checkbox-primary program-checkbox'><label></label></div></td>
            <?php endif;?>
            <td class='text-left table-nest-title' title="<?php echo $program['programName']?>">
              <i class="table-nest-icon icon table-nest-toggle icon-plus"></i>
              <i class="icon icon-cards-view"></i>
              <span><?php echo $program['programName']?></span>
            </td>
            <td class='c-manager'>
              <?php
              if(!empty($program['programPM']))
              {
                  $programPM = $program['programPM'];
                  $userName  = zget($users, $programPM);
                  echo html::smallAvatar(array('avatar' => $usersAvatar[$programPM], 'account' => $programPM, 'name' => $userName), 'avatar-circle avatar-top avatar-' . zget($userIdPairs, $programPM));

                  $userID = isset($userIdPairs[$programPM]) ? $userIdPairs[$programPM] : '';
                  echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), $userName, '', "title='{$userName}' class='iframe' data-width='600'");
              }
              ?>
            </td>
            <td><?php echo $program['draftStories'];?></td>
            <td><?php echo $program['activeStories'];?></td>
            <td><?php echo $program['changingStories'];?></td>
            <td><?php echo $program['reviewingStories'];?></td>
            <?php $totalStories = ($program['totalStories'] - $program['closedStories']) + $program['finishClosedStories'];?>
            <td><?php echo $totalStories == 0 ? 0 : round($program['finishedStories'] / $totalStories, 3) * 100;?>%</td>
            <td><?php echo $program['unresolvedBugs'];?></td>
            <td><?php echo ($program['unresolvedBugs'] + $program['fixedBugs']) == 0 ? 0 : round($program['fixedBugs'] / ($program['unresolvedBugs'] + $program['fixedBugs']), 3) * 100;?>%</td>
            <td><?php echo $program['plans'];?></td>
            <td><?php echo $program['releases'];?></td>
            <?php foreach($extendFields as $extendField) echo "<td></td>";?>
            <td></td>
          </tr>
          <?php unset($program['programName']);?>
          <?php endif;?>

          <?php foreach($program as $lineID => $line):?>
          <?php if(isset($line['lineName']) and isset($line['products']) and is_array($line['products']) and in_array($config->systemMode, array('ALM', 'PLM'))):?>
          <?php $lineNames[] = $line['lineName'];?>
          <?php
          if(in_array($config->systemMode, array('ALM', 'PLM')))
          {
              $trAttrs  = "data-id='line.$lineID' data-parent='program.$programID'";
              $trAttrs .= " data-nest-parent='program.$programID' data-nest-path='program.$programID,line.$lineID'" . "class='text-center'";
          }
          else
          {
              $trAttrs  = "data-id='line.$lineID' data-parent='0' data-nested='true'";
              $trClass  = 'is-top-level table-nest-child text-center';
              $trAttrs .= " class='$trClass'";
          }
          ?>
          <tr class="row-line" <?php echo $trAttrs;?>>
            <?php if($canBatchEdit):?>
            <td class='c-checkbox'><div class='checkbox-primary program-checkbox'><label></label></div></td>
            <?php endif;?>
            <td class='text-left table-nest-title' title="<?php echo $line['lineName']?>">
              <span class="table-nest-icon icon table-nest-toggle"></span>
              <?php echo $line['lineName']?>
            </td>
            <td></td>
            <td><?php echo isset($line['draftStories']) ? $line['draftStories'] : 0;?></td>
            <td><?php echo isset($line['activeStories']) ? $line['activeStories'] : 0;?></td>
            <td><?php echo isset($line['changingStories']) ? $line['changingStories'] : 0;?></td>
            <td><?php echo isset($line['reviewingStories']) ? $line['reviewingStories'] : 0;?></td>
            <?php $totalStories = (isset($line['totalStories']) ? $line['totalStories'] : 0) - (isset($line['closedStories']) ? $line['closedStories'] : 0) + (isset($line['finishClosedStories']) ? $line['finishClosedStories'] : 0)?>
            <td><?php echo $totalStories == 0 ? 0 : round((isset($line['finishedStories']) ? $line['finishedStories'] : 0) / $totalStories, 3) * 100;?>%</td>
            <td><?php echo isset($line['unresolvedBugs']) ? $line['unresolvedBugs'] : 0;?></td>
            <td><?php echo (isset($line['fixedBugs']) and ($line['unresolvedBugs'] + $line['fixedBugs'] != 0)) ? round($line['fixedBugs'] / ($line['unresolvedBugs'] + $line['fixedBugs']), 3) * 100 : 0;?>%</td>
            <td><?php echo isset($line['plans']) ? $line['plans'] : 0;?></td>
            <td><?php echo isset($line['releases']) ? $line['releases'] : 0;?></td>
            <?php foreach($extendFields as $extendField) echo "<td></td>";?>
            <td></td>
          </tr>
          <?php unset($line['lineName']);?>
          <?php endif;?>

          <?php if(isset($line['products']) and is_array($line['products'])):?>
          <?php foreach($line['products'] as $productID => $product):?>
          <?php
          $totalStories = ($product->totalStories - $product->closedStories) + $product->finishClosedStories;

          $trClass = '';
          if($product->line and in_array($config->systemMode, array('ALM', 'PLM')))
          {
              $path     = "program.$product->program,line.$product->line,$product->id";
              $trAttrs  = "data-id='$product->id' data-parent='line.$product->line'";
              $trClass .= ' is-nest-child  table-nest';
              $trAttrs .= " data-nest-parent='line.$product->line' data-nest-path='$path'";
          }
          elseif($product->program and in_array($config->systemMode, array('ALM', 'PLM')))
          {
              $trAttrs  = "data-id='$product->id' data-parent='program.$product->program'";
              $trClass .= ' is-nest-child  table-nest';
              $trAttrs .= " data-nest-parent='program.$product->program' data-nest-path='program.$product->program,$product->id'";
          }
          else
          {
              $trAttrs  = "data-id='$product->id' data-parent='0'";
              $trClass .= ' no-nest';
          }
          $trAttrs .= " class='$trClass'";
          ?>
          <tr class="row-product" <?php echo $trAttrs;?>>
            <?php if($canBatchEdit):?>
            <td class='c-checkbox'><?php echo html::checkbox('productIDList', array($product->id => ''));?></td>
            <?php endif;?>
            <td class="c-name text-left <?php echo $canSort ? 'sort-handler' : '';?> table-nest-title" title='<?php echo $product->name?>'>
              <?php
              echo html::a($this->createLink('product', 'browse', 'productID=' . $product->id), $product->name);
              ?>
            </td>
            <td class='c-manager'>
              <?php
              if(!empty($product->PO))
              {
                  $userName  = zget($users, $product->PO);
                  echo html::smallAvatar(array('avatar' => $usersAvatar[$product->PO], 'account' => $product->PO, 'name' => $userName), 'avatar-circle avatar-' . zget($userIdPairs, $product->PO));

                  $userID = isset($userIdPairs[$product->PO]) ? $userIdPairs[$product->PO] : '';
                  echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), $userName, '', "title='{$userName}' class='iframe' data-width='600'");
              }
              ?>
            </td>
            <td><?php echo $product->draftStories;?></td>
            <td><?php echo $product->activeStories;?></td>
            <td><?php echo $product->changingStories;?></td>
            <td><?php echo $product->reviewingStories;?></td>
            <td><?php echo $totalStories == 0 ? 0 : round($product->finishedStories / $totalStories, 3) * 100;?>%</td>
            <td><?php echo $product->unresolvedBugs;?></td>
            <td><?php echo ($product->unresolvedBugs + $product->fixedBugs) == 0 ? 0 : round($product->fixedBugs / ($product->unresolvedBugs + $product->fixedBugs), 3) * 100;?>%</td>
            <td><?php echo $product->plans;?></td>
            <td><?php echo $product->releases;?></td>
            <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $product) . "</td>";?>
            <td class='c-actions'><?php echo $this->product->buildOperateMenu($product, 'browse');?></td>
          </tr>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php echo $pager->show('left', 'pagerjs');?>
        <?php if(!empty($product) and $canBatchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php
        $summary = (empty($productLines) or $this->config->systemMode == 'light') ? sprintf($lang->product->pageSummary, count($productStats)) : sprintf($lang->product->lineSummary, count($lineNames), count($productStats));
        echo "<div id='productsCount' class='statistic'>$summary</div>";
        ?>
        <div class="table-actions btn-toolbar">
          <?php
          $actionLink = $this->createLink('product', 'batchEdit');
          echo html::commonButton($lang->edit, "id='editBtn' data-form-action='$actionLink'");
          ?>
        </div>
        <?php endif;?>
      </div>
    </form>
  </div>
  <?php endif;?>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('browseType', $browseType)?>
<?php js::set('checkedProducts', $lang->product->checkedProducts);?>
<?php js::set('cilentLang', $this->app->getClientLang());?>
<?php if(commonModel::isTutorialMode()): ?>
<style>
#productListForm {overflow: hidden;}
#productList .table-nest-title {width: 200px;}
</style>
<?php endif; ?>
<?php include '../../common/view/footer.html.php';?>
