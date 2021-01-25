<?php
/**
 * The view of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     view
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->riskList != false ? $app->session->riskList : $this->createLink('risk', 'browse');?>
<?php js::set('sysurl', common::getSysUrl());?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $risk->id?></span>
      <span class="text" title='<?php echo $risk->name;?>'><?php echo $risk->name;?></span>
      <?php if($risk->deleted):?>
      <span class='label label-danger'><?php echo $lang->risk->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->prevention;?></div>
        <div class="detail-content article-content"><?php echo $risk->prevention;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->remedy;?></div>
        <div class="detail-content article-content"><?php echo $risk->remedy;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->resolution;?></div>
        <div class="detail-content article-content"><?php echo $risk->resolution;?></div>
      </div>
    </div>
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$risk->deleted):?>
        <?php
        common::printIcon('risk', 'track', "riskID=$risk->id", $risk, "button", 'checked', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'assignTo', "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'cancel', "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'close',    "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'hangup',    "riskID=$risk->id", $risk, 'button', 'arrow-up', '', 'iframe showinonlybody', true);
        common::printIcon('risk', 'activate',    "riskID=$risk->id", $risk, 'button', '', '', 'iframe showinonlybody', true);
        echo "<div class='divider'></div>";
        common::printIcon('risk', 'edit', "riskID=$risk->id", $risk);
        common::printIcon('risk', 'delete', "riskID=$risk->id", $risk, 'button', 'trash', 'hiddenwin');
        ?>  
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->risk->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->id;?></th>
                  <td><?php echo $risk->id;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->source;?></th>
                  <td><?php echo zget($lang->risk->sourceList, $risk->source);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->category;?></th>
                  <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->strategy;?></th>
                  <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->status;?></th>
                  <td><span class='status-story status-<?php echo $risk->status?>'><span class="label label-dot"></span> <?php echo $this->processStatus('risk', $risk);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->impact;?></th>
                  <td><?php echo zget($lang->risk->impactList, $risk->impact);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->probability;?></th>
                  <td><?php echo zget($lang->risk->probabilityList, $risk->probability);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->rate;?></th>
                  <td><?php echo $risk->rate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->pri;?></th>
                  <?php $pri = $risk->pri == 'low' ? '3' : ($risk->pri == 'middle' ? '2' : '1');?>
                  <td><span class='label-pri <?php echo 'label-pri-' . $pri;?>' title='<?php echo zget($lang->risk->priList, $risk->pri)?>'><?php echo zget($lang->risk->priList, $risk->pri)?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->identifiedDate;?></th>
                  <td><?php echo $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->plannedClosedDate;?></th>
                  <td><?php echo $risk->plannedClosedDate == '0000-00-00' ? '' : $risk->plannedClosedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->actualClosedDate;?></th>
                  <td><?php echo $risk->actualClosedDate == '0000-00-00' ? '' : $risk->actualClosedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> 
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->risk->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->assignedTo;?></th>
                  <td><?php echo zget($users, $risk->assignedTo);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->trackedBy;?></th>
                  <td><?php echo zget($users, $risk->trackedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->trackedDate;?></th>
                  <td><?php echo $risk->trackedDate == '0000-00-00' ? '' : $risk->trackedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->createdBy;?></th>
                  <td><?php echo zget($users, $risk->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->createdDate;?></th>
                  <td><?php echo $risk->createdDate == '0000-00-00' ? '' : $risk->createdDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->editedBy;?></th>
                  <td><?php echo zget($users, $risk->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->editedDate;?></th>
                  <td><?php echo $risk->editedDate == '0000-00-00' ? '' : $risk->editedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->assignedDate;?></th>
                  <td><?php echo $risk->assignedDate == '0000-00-00' ? '' : $risk->assignedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->resolvedBy;?></th>
                  <td><?php echo zget($users, $risk->resolvedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->actualClosedDate;?></th>
                  <td><?php echo $risk->actualClosedDate == '0000-00-00' ? '' : $risk->actualClosedDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->cancelBy;?></th>
                  <td><?php echo zget($users, $risk->cancelBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->cancelDate;?></th>
                  <td><?php echo $risk->cancelDate == '0000-00-00' ? '' : $risk->cancelDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->cancelReason;?></th>
                  <td><?php echo zget($lang->risk->cancelReasonList, $risk->cancelReason);?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->hangupBy;?></th>
                  <td><?php echo zget($users, $risk->hangupBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->hangupDate;?></th>
                  <td><?php echo $risk->hangupDate == '0000-00-00' ? '' : $risk->hangupDate;?></td>
                </tr>
                <tr>
                  <th class='w-90px'><?php echo $lang->risk->activateBy;?></th>
                  <td><?php echo zget($users, $risk->activateBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->activateDate;?></th>
                  <td><?php echo $risk->activateDate == '0000-00-00' ? '' : $risk->activateDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
