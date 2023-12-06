<?php
/**
 * The view view of stakeholder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(QingDao Nature Easy Soft Network Technology C
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     company
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class="main-row" id="mainContent">
  <div class="center-block">
    <div class="main-header">
      <h2>
        <span><?php echo $lang->stakeholder->userIssue;?></span>
      </h2>
      <div class="pull-right">
        <?php common::printLink('issue', 'create', "projectID=$projectID&from=stakeholder&owner=" . $stakeholder->user, "<i class='icon icon-plus'></i> " . $lang->issue->create, '', "class='btn btn-primary'");?>
      </div>
    </div>
  </div>
  <div class="main-col col-12">
    <?php if(empty($issueList)):?>
      <div class="table-empty-tip"><p><span class="text-muted"><?php echo $lang->stakeholder->emptyTip;?></span></p></div>
    <?php else:?>
    <div class="main-table">
      <table class="table table-bordered" style="margin-bottom: 10px;">
        <thead>
          <tr>
            <th class="w-120px"><?php echo $lang->issue->title;?></th>
            <th class="w-40px"><?php echo $lang->issue->type;?></th>
            <th class="w-50px"><?php echo $lang->issue->severity;?></th>
            <th class="w-50px"><?php echo $lang->issue->pri;?></th>
            <th class="w-40px"><?php echo $lang->issue->status;?></th>
            <th class="w-80px"><?php echo $lang->issue->createdDate;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($issueList as $issue):?>
            <tr>
              <td><?php echo $issue->title;?></td>
              <td><?php echo zget($lang->issue->typeList, $issue->type);?></td>
              <td><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
              <td><?php echo $issue->pri;?></td>
              <td><?php echo zget($lang->issue->statusList, $issue->status);?></td>
              <td><?php echo $issue->createdDate;?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
