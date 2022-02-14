<?php
/**
 * The html template file of importNotice method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: execute.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->convert->jira->mapJira2Zentao;?>
    </h2>
  </div>
  <form class='main-form form-ajax' method='post'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->convert->jira->jiraObject;?></th>
          <th><?php echo $lang->convert->jira->zentaoObject;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($issueTypeList as $id => $issueType):?>
        <?php $value = $method == 'db' ? $issueType->pname : $issueType['name'];?>
        <tr>
          <td><?php echo html::select('jiraObject[]', array($id => $value), $id, "class='form-control chosen'");?></td>
          <td><?php echo html::select('zentaoObject[]', $lang->convert->jira->zentaoObjectList, '', "class='form-control chosen'");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <hr />
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->convert->jira->jiraLinkType;?></th>
          <th><?php echo $lang->convert->jira->zentaoLinkType;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($linkTypeList as $id => $linkType):?>
        <?php $value = $method == 'db' ? $linkType->linkname : $linkType['linkname'];?>
        <tr>
          <td><?php echo html::select('jiraLinkType[]', array($id => $value), $id, "class='form-control chosen'");?></td>
          <td><?php echo html::select('zentaoLinkType[]', $lang->convert->jira->zentaoLinkTypeList, '', "class='form-control chosen'");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <hr />
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->convert->jira->jiraResolution;?></th>
          <th><?php echo $lang->convert->jira->zentaoResolution;?></th>
          <th><?php echo $lang->convert->jira->zentaoReason;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($resolutionList as $id => $resolution):?>
        <?php $value = $method == 'db' ? $resolution->pname : $resolution['name'];?>
        <tr>
          <td><?php echo html::select('jiraResolution[]', array($id => $value), $id, "class='form-control chosen'");?></td>
          <td><?php echo html::select('zentaoResolution[]', $lang->bug->resolutionList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select('zentaoReason[]', $lang->story->reasonList, '', "class='form-control chosen'");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <hr />
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->convert->jira->jiraStatus;?></th>
          <th><?php echo $lang->convert->jira->storyStatus;?></th>
          <th><?php echo $lang->convert->jira->storyStage;?></th>
          <th><?php echo $lang->convert->jira->taskStatus;?></th>
          <th><?php echo $lang->convert->jira->bugStatus;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($statusList as $id => $status):?>
        <?php $value = $method == 'db' ? $status->pname : $status['name'];?>
        <tr>
          <td><?php echo html::select('jiraStatus[]', array($id => $value), $id, "class='form-control chosen'");?></td>
          <td><?php echo html::select('storyStatus[]', $lang->story->statusList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select('storyStage[]', $lang->story->stageList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select('taskStatus[]', $lang->task->statusList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select('bugStatus[]', $lang->bug->statusList, '', "class='form-control chosen'");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5" class="text-center form-actions"><?php echo html::submitButton($lang->convert->jira->next);?></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
