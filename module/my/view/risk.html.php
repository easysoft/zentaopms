<?php
/**
 * The risk view file of my module of ZenTaoPMS.
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
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=createdBy"),  "<span class='text'>{$lang->my->taskMenu->openedByMe}</span>"   . ($type == 'createdBy' ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'createdBy' ? ' btn-active-text' : '') . "'");
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=closedBy"),  "<span class='text'>{$lang->my->taskMenu->closedByMe}</span>"   . ($type == 'closedBy' ? $recTotalLabel : ''),   '', "class='btn btn-link" . ($type == 'closedBy' ? ' btn-active-text' : '') . "'");
    }
    ?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($risks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <form id='myTaskForm' class="main-table table-risk" data-ride="table" method="post">
    <table class="table has-sort-head table-fixed" id='risktable'>
      <?php $vars = "mode=$mode&type=$type&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <thead>
        <tr>
		  <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->risk->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->risk->name);?></th>
          <th class='w-80px'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->risk->status);?></th>
          <th class='w-120px'><?php common::printOrderLink('identifiedDate', $orderBy, $vars, $lang->risk->identifiedDate);?></th>
          <th class='w-80px'><?php common::printOrderLink('rate', $orderBy, $vars, $lang->risk->rate);?></th>
          <th class='w-80px'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->risk->pri);?></th>
          <th class='w-120px'><?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->risk->assignedTo);?></th>
          <th class='w-120px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->risk->category);?></th>
          <th class='w-180px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($risks as $risk):?>
		<tr>
          <td><?php echo $risk->id;?></td>
          <td><?php echo html::a($this->createLink('risk', 'view', "riskID=$risk->id"), $risk->name);?></td>
          <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
          <td><?php echo zget($lang->risk->statusList, $risk->status);?></td>
          <td><?php echo $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate;?></td>
          <td><?php echo $risk->rate;?></td>
          <td><?php echo zget($lang->risk->priList, $risk->pri)?></td>
          <td><?php echo $this->risk->printAssignedHtml($risk, $users);;?></td>
          <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
          <td class='c-actions'>
            <?php
            $params = "riskID=$risk->id";
            common::printIcon('risk', 'track', $params, $risk, "list", 'checked');
            common::printIcon('risk', 'close', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('risk', 'cancel', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('risk', 'hangup', $params, $risk, "list", 'arrow-up', '', 'iframe', true);
            common::printIcon('risk', 'activate', $params, $risk, "list", '', '', 'iframe', true);
            common::printIcon('risk', 'edit', $params, $risk, "list");
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
