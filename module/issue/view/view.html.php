<?php
/**
 * The details view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology C
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php
$browseLink = $this->createLink('issue', 'browse');
$createLink = $this->createLink('issue', 'create');

$dateFiled  = array('deadline', 'resolvedDate', 'createdDate', 'editedDate', 'activateDate', 'closedDate', 'assignedDate');
foreach($issue as $field => $value)
{
    if(in_array($field, $dateFiled) && strpos($value, '0000') === 0) $issue->$field = '';
}
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $issue->id?></span>
      <span class="text" title="<?php echo $issue->title?>"><?php echo $issue->title?></span>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('issue', 'create')) echo html::a($createLink, "<i class='icon icon-plus'></i> {$lang->issue->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div class="main-row" id="mainContent">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->issue->desc;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($issue->desc) ? $issue->desc : '<div class="text-center text-muted">' . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php if($issue->files):?>
      <div class="detail"><?php echo $this->fetch('file', 'printFiles', array('files' => $issue->files, 'fieldset' => 'true'));?></div>
      <?php endif;?>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=issue&objectID=$issue->id");?>
    <div class="cell"><?php include '../../common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$issue->deleted):?>
        <?php
          $params = "issueID=$issue->id";
          common::printIcon('issue', 'resolve', $params, $issue, 'button', 'checked', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'assignTo', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'cancel', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'close', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          common::printIcon('issue', 'activate', $params, $issue, 'button', '', '', 'iframe showinonlybody', true);
          echo "<div class='divider'></div>";
          common::printIcon('issue', 'edit', $params, $issue);
          common::printIcon('issue', 'delete', $params, $issue, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <details class="detail" open="">
      <summary class="detail-title"><?php echo $lang->issue->basicInfo;?></summary>
      <div class="detail-content">
        <table class="table table-data">
          <tbody>
            <tr valign="middle">
              <th class="thWidth w-100px"><?php echo $lang->issue->id;?></th>
              <td><?php echo $issue->id;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->type;?></th>
              <td><?php echo zget($lang->issue->typeList, $issue->type);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->severity;?></th>
              <td><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->pri;?></th>
              <td><?php echo $issue->pri;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->deadline;?></th>
              <td><?php echo $issue->deadline;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->resolvedDate;?></th>
              <td><?php echo $issue->resolvedDate;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->owner;?></th>
              <td><?php echo zget($users, $issue->owner);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->assignedTo;?></th>
              <td><?php echo zget($users, $issue->assignedTo);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->createdBy;?></th>
              <td><?php echo zget($users, $issue->createdBy);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->createdDate;?></th>
              <td><?php echo $issue->createdDate;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->editedBy;?></th>
              <td><?php echo zget($users, $issue->editedBy);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->editedDate;?></th>
              <td><?php echo $issue->editedDate;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->assignedBy;?></th>
              <td><?php echo zget($users, $issue->assignedBy);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->assignedDate;?></th>
              <td><?php echo $issue->assignedDate;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->closedDate;?></th>
              <td><?php echo $issue->closedDate;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->activateBy;?></th>
              <td><?php echo zget($users, $issue->activateBy);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->activateDate;?></th>
              <td><?php echo $issue->activateDate;?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->closeBy;?></th>
              <td><?php echo zget($users, $issue->closeBy);?></td>
            </tr>
            <tr valign="middle">
              <th class="thWidth w-80px"><?php echo $lang->issue->status;?></th>
              <td><?php echo zget($lang->issue->statusList, $issue->status);?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
