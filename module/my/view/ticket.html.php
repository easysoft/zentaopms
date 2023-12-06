<?php
/**
 * The ticket view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<div id='mainMenu' class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->my->featureBar[$app->rawMethod]['ticket'] as $key => $name)
    {
        $label  = "<span class='text'>{$name}</span>";
        $label .= $key == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        $active = $key == $browseType ? 'btn-active-text' : '';
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=$key"), $label, '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->user->search;?></a>
  </div>
</div>
<div id='mainContent' class="main-row fade">
  <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='workTicket'></div>
  <?php if(empty($tickets)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->ticket->noTicket;?></span>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='opportunityForm' method='post' data-ride="table">
    <table class="table has-sort-head" id='ticketList'>
    <?php $vars = "browseType=$browseType&param=0&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
    <?php $canView = common::hasPriv('ticket', 'view');?>
      <thead>
        <th class="c-id"><?php common::printOrderLink('id', $orderBy, $vars, $lang->ticket->idAB);?></th>
        <th class="c-product"><?php common::printOrderLink('product', $orderBy, $vars, $lang->ticket->product);?></th>
        <th class='c-title'><?php common::printOrderLink('title', $orderBy, $vars, $lang->ticket->title);?></th>
        <th class='c-pri' title='<?php echo $lang->pri;?>'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->ticket->priAB);?></th>
        <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->ticket->status);?></th>
        <th class="c-type"><?php common::printOrderLink('type', $orderBy, $vars, $lang->ticket->type);?></th>
        <th class='c-openedBy'><?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->ticket->createdBy);?></th>
        <th class='c-openedDate'><?php common::printOrderLink('openedDate', $orderBy, $vars, $lang->ticket->createdDate);?></th>
        <th class='c-assignedTo'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->ticket->assignedTo);?></th>
        <th class='c-actions'><?php echo $lang->actions;?></th>
      </thead>
      <tbody>
        <?php foreach($tickets as $ticket): ?>
        <tr>
          <td class='c-id'><?php echo $canView ? html::a($this->createLink('ticket', 'view', "id={$ticket->id}"), $ticket->id) : $ticket->id;?></td>
          <td class='no-wrap' title="<?php echo zget($products, $ticket->product);?>"><?php echo zget($products, $ticket->product);?></td>
          <td class='no-wrap' title="<?php echo $ticket->title;?>"><?php echo $canView ? html::a($this->createLink('ticket', 'view', "id={$ticket->id}"), $ticket->title) : $ticket->title;?></td>
          <td><span class='label-pri label-pri-<?php echo $ticket->pri;?>' title='<?php echo zget($lang->ticket->priList, $ticket->pri, $ticket->pri);?>'><?php echo zget($lang->ticket->priList, $ticket->pri); ?></span></td>
          <td><?php echo zget($lang->ticket->statusList, $ticket->status);?></td>
          <td><?php echo zget($lang->ticket->typeList, $ticket->type);?></td>
          <td class='no-wrap' title="<?php echo zget($users, $ticket->openedBy);?>"><?php echo zget($users, $ticket->openedBy);?></td>
          <td><?php echo $ticket->openedDate;?></td>
          <td><?php echo $this->ticket->printAssignedHtml($ticket, $users);?></td>
          <td class='c-actions'><?php echo $this->ticket->buildOperateBrowseMenu($ticket->id);?></td>
          </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  </form>
<?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
