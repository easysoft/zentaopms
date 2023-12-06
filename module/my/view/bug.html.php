<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: bug.html.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    foreach($lang->my->featureBar[$app->rawMethod]['bug'] as $typeKey => $name)
    {
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=$typeKey"), "<span class='text'>{$name}</span>" . ($type == $typeKey ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == $typeKey ? ' btn-active-text' : '') . "'");
    }
    ?>
  </div>
  <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->my->byQuery;?></a>
</div>
<div id="mainContent">
  <?php $dataModule = $app->rawMethod == 'work' ? 'workBug' : 'contributeBug';?>
  <div class="cell<?php if($type == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo $dataModule;?>></div>
  <?php if(empty($bugs)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->bug->notice->noBug;?></span></p>
  </div>
  <?php else:?>
  <form id='myBugForm' class="main-table table-bug" data-ride="table" method="post" action='<?php echo $this->createLink('bug', 'batchEdit', "productID=0");?>'>
    <?php
    $canBatchEdit     = (common::hasPriv('bug', 'batchEdit') and $type == 'assignedTo');
    $canBatchConfirm  = common::hasPriv('bug', 'batchConfirm');
    $canBatchClose    = (common::hasPriv('bug', 'batchClose') and strtolower($type) != 'closedby');
    $canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
    $canBatchAction   = ($canBatchEdit or $canBatchConfirm or $canBatchClose or $canBatchAssignTo);
    ?>
    <table class="table has-sort-head table-fixed" id='bugList'>
      <?php $vars = "mode=$mode&type=$type&param=myQueryID&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
      <?php $type = $type == 'bySearch' ? $this->session->myBugType : $type;?>
      <thead>
        <tr>
          <th class="c-id">
            <?php if($canBatchAction):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->bug->title);?></th>
          <th class='c-severity' title='<?php echo $lang->bug->severity;?>'><?php common::printOrderLink('severity', $orderBy, $vars, $lang->bug->abbr->severity);?></th>
          <th class='c-pri' title='<?php echo $lang->bug->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>

          <th class='c-type'><?php common::printOrderLink('type', $orderBy, $vars, $lang->bug->type);?></th>
          <th class='c-product'><?php common::printOrderLink('product', $orderBy, $vars, $lang->bug->product);?></th>
          <?php if($type != 'openedBy'): ?>
          <th class='c-user'><?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->bug->abbr->openedBy);?></th>
          <?php endif;?>
          <th class='c-confirm' title='<?php echo $lang->bug->abbr->confirmed;?>'><?php common::printOrderLink('confirmed', $orderBy, $vars, $lang->bug->abbr->confirmed);?></th>
          <?php if($app->rawMethod == 'work'):?>
          <th class='c-date text-center'><?php common::printOrderLink('deadline', $orderBy, $vars, $lang->bug->deadline);?></th>
          <?php endif;?>
          <?php if($type != 'assignedTo'): ?>
          <th class='c-user c-assignedTo'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->bug->assignedTo);?></th>
          <?php endif;?>
          <?php if($type != 'resolvedBy'): ?>
          <th class='c-user'><?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedBy);?></th>
          <?php endif;?>
          <th class='c-resolution'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->abbr->resolution);?></th>
          <th class='c-actions-5 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <?php
      $hasCustomSeverity = false;
      foreach($lang->bug->severityList as $severityKey => $severityValue)
      {
          if(!empty($severityKey) and (string)$severityKey != (string)$severityValue)
          {
              $hasCustomSeverity = true;
              break;
          }
      }
      ?>
      <tbody>
        <?php foreach($bugs as $bug):?>
        <?php $canBeChanged = common::canBeChanged('bug', $bug);?>
        <tr>
          <td class="c-id">
            <?php if($canBatchAction):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='bugIDList[]' value='<?php echo $bug->id;?>' <?php if(!$canBeChanged) echo 'disabled';?> />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $bug->id);?>
          </td>
          <td class='text-left nobr'>
            <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, null, "style='color: $bug->color' title='{$bug->title}'");?>
            <?php if($bug->case) echo html::a(helper::createLink('testcase', 'view', "caseID=$bug->case&version=$bug->caseVersion"), "[" . $this->lang->testcase->common . "#$bug->case]", '', "class='bug' title='$bug->case'");?>
          </td>
          <td class='c-severity'>
            <?php if($hasCustomSeverity):?>
            <span class='<?php echo 'label-severity-custom';?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>' data-severity='<?php echo $bug->severity;?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span>
            <?php else:?>
            <span class='<?php echo 'label-severity';?>' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>'></span>
            <?php endif;?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $bug->pri?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri)?></span></td>
          <td title="<?php echo zget($lang->bug->typeList, $bug->type, '');?>"><?php echo zget($lang->bug->typeList, $bug->type, '');?></td>
          <td class='text-left nobr'>
            <?php
            if(isset($bug->shadow) and !empty($bug->shadow))
            {
                echo html::a($this->createLink('project', 'browse'), $bug->productName, null, "title={$bug->productName}");
            }
            else
            {
                $productLink = explode('-', $config->productLink);
                $param       = $config->productLink == 'product-all' ? '' : "productID=$bug->product";
                echo html::a($this->createLink('product', $productLink[1], $param), $bug->productName, null, "title={$bug->productName}");
            }
            ?>
          </td>
          <?php if($type != 'openedBy'): ?>
          <td><?php echo zget($users, $bug->openedBy);?></td>
          <?php endif;?>
          <td class="text-center"><span class='<?php echo $bug->confirmed == '1' ? 'confirmed' : 'unconfirmed';?>' title='<?php echo zget($lang->bug->confirmedList, $bug->confirmed);?>'><?php echo zget($lang->bug->confirmedList, $bug->confirmed)?></span></td>
          <?php if($app->rawMethod == 'work'):?>
          <td class="text-center <?php echo (isset($bug->delay) and $bug->status == 'active') ? 'delayed' : '';?>"><?php if(substr($bug->deadline, 0, 4) > 0) echo '<span>' . substr($bug->deadline, 5, 6) . '</span>';?></td>
          <?php endif;?>
          <?php if($type != 'assignedTo'): ?>
          <td class='c-assignedTo has-btn'><?php $this->bug->printAssignedHtml($bug, $users);?></td>
          <?php endif;?>
          <?php if($type != 'resolvedBy'): ?>
          <td><?php echo zget($users, $bug->resolvedBy);?></td>
          <?php endif;?>
          <td><?php echo zget($lang->bug->resolutionList, $bug->resolution);?></td>
          <td class='c-actions'>
            <?php
            if($canBeChanged)
            {
                $params = "bugID=$bug->id";
                common::printIcon('bug', 'confirm', $params, $bug, 'list', 'ok',      '', 'iframe', true, '', '', $bug->project);
                common::printIcon('bug', 'resolve', $params, $bug, 'list', 'checked', '', 'iframe', true, '', '', $bug->project);
                common::printIcon('bug', 'close',   $params, $bug, 'list', '',        '', 'iframe', true, '', '', $bug->project);
                common::printIcon('bug', 'edit',    $params, $bug, 'list', '',        '', 'iframe', true, "data-width='95%'", '', $bug->project);
                common::printIcon('bug', 'create',  "product=$bug->product&branch=$bug->branch&extra=$params", $bug, 'list', 'copy', '', 'iframe', true, "data-width='95%'", '', $bug->project);
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchAction):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('bug', 'batchEdit');
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->edit, $misc);
        }
        if($canBatchConfirm)
        {
          $actionLink = $this->createLink('bug', 'batchConfirm', '', '', '', $bug->project);
          $misc = "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"";
          echo html::commonButton($lang->bug->confirm, $misc);
        }
        if($canBatchClose)
        {
          $actionLink = $this->createLink('bug', 'batchClose', '', '', '', $bug->project);
          $misc = "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"";
          echo html::commonButton($lang->bug->close, $misc);
        }
        ?>
        <?php
        if($canBatchAssignTo && count($bugs)):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->bug->assignedTo?> <span class="caret"></span></button>
          <?php
          $withSearch = count($memberPairs) > 10;
          $actionLink = $this->createLink('bug', 'batchAssignTo', "productID=0&type=my", '', '', $bug->project);
          echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
          if($withSearch)
          {
              echo "<div class='dropdown-menu search-list search-box-sink' data-ride='searchList'>";
              echo '<div class="input-control search-box has-icon-left has-icon-right search-example">';
              echo '<input id="userSearchBox" type="search" class="form-control search-input" autocomplete="off" />';
              echo '<label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>';
              echo '<a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>';
              echo '</div>';
              $membersPinYin = common::convert2Pinyin($memberPairs);
          }
          else
          {
              echo "<div class='dropdown-menu search-list'>";
          }
          echo '<div class="list-group">';
          foreach ($memberPairs as $key => $value)
          {
              if(empty($key)) continue;
              $searchKey = $withSearch ? ('data-key="' . zget($membersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
              echo html::a('javascript:$(".table-actions #assignedTo").val("' . $key . '");setFormAction("' . $actionLink . '")', '<i class="icon icon-person icon-sm"></i> ' . $value, '', $searchKey);
          }
          echo "</div>";
          echo "</div>";
          ?>
        </div>
        <?php endif;?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('listName', 'bugList')?>
<?php include '../../common/view/footer.html.php';?>
