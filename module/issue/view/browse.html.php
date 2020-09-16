<?php
/**
 * The browse view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php 
      foreach($lang->issue->labelList as $label => $labelName)
      {
          $active = $browseType == $label ? 'btn-active-text' : '';
          echo html::a($this->createLink('issue', 'browse', 'browseType=' . $label), '<span class="text">' . $labelName . '</span>', '', "class='btn btn-link $active'");
      }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->issue->search;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('issue', 'batchCreate', '', "<i class='icon icon-plus'></i>" . $lang->issue->batchCreate, '', "class='btn btn-secondary'");?>
    <?php common::printLink('issue', 'create', '', "<i class='icon icon-plus'></i>" . $lang->issue->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module="issue"></div>
    <?php if($issueList):?>
      <form class="main-table" data-ride="table" method="post" id="issueForm">
        <table id="issueList" class="table has-sort-head" id="issueTable">
          <thead>
            <tr>
              <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
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
            <?php foreach($issueList as $id => $issue):?>
            <tr>
              <td class="c-id"><?php printf('%03d', $issue->id);?></td>
              <td title="<?php echo zget($lang->issue->typeList, $issue->type);?>"><?php echo zget($lang->issue->typeList, $issue->type);?></td>
              <td class="text-ellipsis" title="<?php echo $issue->title;?>"><?php common::printLink('issue', 'view', "id=$issue->id", $issue->title);?></td>
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
      <?php if($issueList):?>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
      </form>
    <?php else:?>
      <div class="table-empty-tip">
        <?php echo $lang->noData;?>
        <?php echo html::a($this->createLink('issue', 'create'), '<i class="icon icon-plus"></i> ' . $lang->issue->create, '', 'class="btn btn-info"')?>
      </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

