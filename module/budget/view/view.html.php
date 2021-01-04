<?php
/**
 * The view file of budget module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     budget
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink = inlink('browse');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php common::printBack($browseLink, 'btn btn-secondary');?>
    <div class='divider'></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $budget->id?></span>
      <span class="text" title="<?php echo $budget->name;?>"><?php echo $budget->name;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="col-8 main-col">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->budget->desc;?></div>
        <div class="detail-content article-content">
          <?php echo $budget->desc;?>
        </div>
      </div>
    </div>
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->budget->basicInfo;?></div>
        <table class="table table-data">
          <tbody>
            <tr>
              <th><?php echo $lang->budget->stage;?></th>
              <td><?php echo zget($plans, $budget->stage);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->budget->subject;?></th>
              <td><?php echo zget($subjects, $budget->subject);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->budget->amount;?></th>
              <td><?php echo $budget->amount;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->budget->createdBy;?></th>
              <td><?php echo zget($users, $budget->createdBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->budget->createdDate;?></th>
              <td><?php echo $budget->createdDate;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->budget->lastEditedBy;?></th>
              <td><?php echo zget($users, $budget->lastEditedBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->budget->lastEditedDate;?></th>
              <td><?php echo $budget->lastEditedDate;?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
