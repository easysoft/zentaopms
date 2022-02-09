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
  <form class='main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->convert->jira->jiraObject;?></th>
          <th><?php echo $lang->convert->jira->zentaoObject;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($issueTypePairs as $id => $name):?>
        <tr>
          <td><?php echo html::select('jiraObjects[]', $issueTypePairs, $id, "class='form-control chosen'");?></td>
          <td><?php echo html::select('zentaoObjects[]', $lang->convert->jira->zentaoObjectList, '', "class='form-control chosen'");?></td>
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
        <?php foreach($linkTypePairs as $id => $name):?>
        <tr>
          <td><?php echo html::select('jiraObjects[]', $linkTypePairs, $id, "class='form-control chosen'");?></td>
          <td><?php echo html::select('zentaoObjects[]', $lang->convert->jira->zentaoLinkTypeList, '', "class='form-control chosen'");?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2" class="text-center form-actions"><?php echo html::submitButton();?></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
