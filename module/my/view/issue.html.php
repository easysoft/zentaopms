<?php
/**
 * The issue view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    if($app->rawMethod == 'work') echo html::a(inlink($app->rawMethod, "mode=$mode&type=assignedTo"),  "<span class='text'>{$lang->my->taskMenu->assignedToMe}</span>" . ($type == 'assignedTo' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'assignedTo' ? ' btn-active-text' : '') . "'");
    if($app->rawMethod == 'contribute')
    {
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=createdBy"),    "<span class='text'>{$lang->my->taskMenu->openedByMe}</span>"   . ($type == 'createdBy' ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'createdBy' ? ' btn-active-text' : '') . "'");
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=closedBy"),    "<span class='text'>{$lang->my->taskMenu->closedByMe}</span>"   . ($type == 'closedBy'   ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'closedBy' ? ' btn-active-text' : '') . "'");
    }
    ?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($issues)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <form id='myTaskForm' class="main-table table-issue" data-ride="table" method="post">
    <table class="table has-sort-head table-fixed" id='issuetable'>
      <?php $vars = "mode=$mode&type=$type&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <thead>
        <tr>
          <th class="c-id w-60px"><?php echo common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th class="w-80px"><?php echo $lang->issue->type;?></th>
          <th style="width:auto"><?php echo $lang->issue->title;?></th>
          <th class="w-60px"><?php echo $lang->issue->severity;?></th>
          <th class="w-60px"><?php echo $lang->issue->pri;?></th>
          <th class="w-90px"><?php echo $lang->issue->assignedTo;?></th>
          <th class="w-80px"><?php echo $lang->issue->owner;?></th>
          <th class="w-100px"><?php echo $lang->issue->status;?></th>
          <th class="w-140px"><?php echo $lang->issue->createdDate;?></th>
          <th class="c-actions w-200px"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($issues as $issue):?>
		<tr>
          <td class="c-id"><?php printf('%03d', $issue->id);?></td>
          <td title="<?php echo zget($lang->issue->typeList, $issue->type);?>"><?php echo zget($lang->issue->typeList, $issue->type);?></td>
          <td class="text-ellipsis" title="<?php echo $issue->title;?>"><?php echo html::a($this->createLink('issue', 'view', "id=$issue->id", '', '', $issue->PRJ), $issue->title);?></td>
          <td title="<?php echo zget($lang->issue->severityList, $issue->severity);?>"><?php echo zget($lang->issue->severityList, $issue->severity);?></td>           
          <td title="<?php echo $issue->pri;?>"><?php echo $issue->pri;?></td>
          <td title="<?php echo zget($users, $issue->assignedTo);?>"><?php echo zget($users, $issue->assignedTo);?></td>
          <td title="<?php echo zget($users, $issue->owner);?>"><?php echo zget($users, $issue->owner);?></td>
          <td title="<?php echo zget($lang->issue->statusList, $issue->status);?>"><?php echo zget($lang->issue->statusList, $issue->status);?></td>
          <td title="<?php echo $issue->createdDate;?>"><?php echo $issue->createdDate;?></td>
          <td class="c-actions">
            <?php
              $params = "issueID=$issue->id";
              echo common::printIcon('issue', 'resolve', $params, $issue, 'list', 'checked', '', 'iframe', 'yes', '', $lang->issue->resolve);
              echo common::printIcon('issue', 'assignTo', $params, $issue, 'list', 'hand-right', '', 'iframe', 'yes', '', $lang->issue->assignTo);
              echo common::printIcon('issue', 'close', $params, $issue, 'list', 'off', '', 'iframe', 'yes');
              echo common::printIcon('issue', 'cancel', $params, $issue, 'list', 'ban-circle', '', 'iframe', 'yes');
              echo common::printIcon('issue', 'activate', $params, $issue, 'list', 'magic', '', 'iframe', 'yes', '', $lang->issue->activate);
              echo common::printIcon('issue', 'edit', $params, $issue, 'list', 'edit');
              $deleteClass = common::hasPriv('issue', 'delete') ? 'btn' : 'btn disabled';
              echo html::a($this->createLink('issue', 'delete', $params), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->issue->delete}' class='$deleteClass'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
