<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: bug.html.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    echo html::a(inlink('bug', "type=assignedTo"), "<span class='text'>{$lang->bug->assignedTo}</span>" . ($type == 'assignedTo' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'assignedTo' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('bug', "type=openedBy"),   "<span class='text'>{$lang->bug->openedByMe}</span>" . ($type == 'openedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'openedBy'   ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('bug', "type=resolvedBy"), "<span class='text'>{$lang->bug->resolvedByMe}</span>" . ($type == 'resolvedBy' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'resolvedBy' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('bug', "type=closedBy"),   "<span class='text'>{$lang->bug->closedByMe}</span>" . ($type == 'closedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'closedBy'   ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($bugs)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->bug->noBug;?></span></p>
  </div>
  <?php else:?>
  <form id='myBugForm' class="main-table table-bug" data-ride="table" method="post" action='<?php echo $this->createLink('bug', 'batchEdit', "productID=0");?>'>
    <?php $canBatchEdit  = common::hasPriv('bug', 'batchEdit');?>
    <table class="table has-sort-head table-fixed" id='bugList'>
      <?php $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"; ?>
      <thead>
        <tr>
          <th class="w-100px">
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-50px' title='<?php echo $lang->bug->severity;?>'> <?php common::printOrderLink('severity',   $orderBy, $vars, $lang->bug->severityAB);?></th>
          <th class='w-50px' title='<?php echo $lang->bug->pri;?>'> <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
          <th class='w-type'>      <?php common::printOrderLink('type',       $orderBy, $vars, $lang->typeAB);?></th>
          <th>                     <?php common::printOrderLink('title',      $orderBy, $vars, $lang->bug->title);?></th>
          <th class='w-90px'>      <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-110px'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->bug->assignedTo);?></th>
          <th class='w-100px'>      <?php common::printOrderLink('resolvedBy', $orderBy, $vars, $lang->bug->resolvedByAB);?></th>
          <th class='w-100px'><?php common::printOrderLink('resolution', $orderBy, $vars, $lang->bug->resolutionAB);?></th>
          <th class='c-actions-5'> <?php echo $lang->actions;?></th>
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
        <tr>
          <td class="c-id">
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='bugIDList[]' value='<?php echo $bug->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $bug->id);?>
          </td>
          <td>
            <?php if($hasCustomSeverity):?>
            <span class='<?php echo 'label-severity-custom';?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>' data-severity='<?php echo $bug->severity;?>'><?php echo zget($lang->bug->severityList, $bug->severity, $bug->severity);?></span>
            <?php else:?>
            <span class='<?php echo 'label-severity';?>' data-severity='<?php echo $bug->severity;?>' title='<?php echo zget($lang->bug->severityList, $bug->severity);?>'></span>
            <?php endif;?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $bug->pri?>' title='<?php echo zget($lang->bug->priList, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri)?></span></td>
          <td title="<?php echo zget($lang->bug->typeList, $bug->type, '');?>"><?php echo zget($lang->bug->typeList, $bug->type, '');?></td>
          <td class='text-left nobr'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title, null, "style='color: $bug->color' title={$bug->title}");?></td>
          <td><?php echo zget($users, $bug->openedBy);?></td>
          <td class='c-assignedTo has-btn'><?php $this->bug->printAssignedHtml($bug, $users);?></td>
          <td><?php echo zget($users, $bug->resolvedBy);?></td>
          <td><?php echo zget($lang->bug->resolutionList, $bug->resolution);?></td>
          <td class='c-actions'>
            <?php
            $params = "bugID=$bug->id";
            common::printIcon('bug', 'confirmBug', $params, $bug, 'list', 'confirm', '', 'iframe', true);
            common::printIcon('bug', 'resolve',    $params, $bug, 'list', 'checked', '', 'iframe', true);
            common::printIcon('bug', 'close',      $params, $bug, 'list', '', '', 'iframe', true);
            common::printIcon('bug', 'edit',       $params, $bug, 'list');
            common::printIcon('bug', 'create',     "product=$bug->product&branch=$bug->branch&extra=$params", $bug, 'list', 'copy');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        $actionLink = $this->createLink('bug', 'batchEdit');
        $misc       = common::hasPriv('bug', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
        echo html::commonButton($lang->edit, $misc);
        ?>
        <?php
        if(common::hasPriv('bug', 'batchConfirm'))
        {
          $actionLink = $this->createLink('bug', 'batchConfirm');
          $misc = "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"";
          echo html::commonButton($lang->bug->confirmBug, $misc);
        }
        if(common::hasPriv('bug', 'batchClose'))
        {
          $actionLink = $this->createLink('bug', 'batchClose');
          $misc = "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"";
          echo html::commonButton($lang->bug->close, $misc);
        }
        ?>
        <?php
        $canBatchAssignTo = common::hasPriv('bug', 'batchAssignTo');
        if($canBatchAssignTo && count($bugs)):?>
        <div class="btn-group dropup">
          <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->bug->assignedTo?> <span class="caret"></span></button>
          <?php
          $withSearch = count($memberPairs) > 10;
          $actionLink = $this->createLink('bug', 'batchAssignTo', "productID=0&type=my");
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
