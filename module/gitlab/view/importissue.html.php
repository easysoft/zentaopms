<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->gitlab->importIssue;?></h2>
  </div>
  <?php if($importable):?>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-form with-border">
        <thead>
          <tr>
            <th class='w-60px'><?php echo $lang->gitlab->gitlabIssue;?></th>
            <th class='w-30px'><?php echo $lang->gitlab->objectType;?></th>
            <th class='w-30px'><?php echo $lang->product->common;?></th>
            <th class='w-30px'><?php echo $lang->execution->common;?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($gitlabIssues as $issue):?>
          <tr>
            <td><?php echo "<a href='{$issue->web_url}' target='_blank'>$issue->title</a>";?></td>
            <td><?php echo html::select("objectTypeList[{$issue->iid}]", $objectTypes, '', "class='form-control'" );?></td>
            <td><?php echo html::select("productList[{$issue->iid}]", $products, '', "class='form-control'" );?></td>
            <td><?php echo html::select("executionList[{$issue->iid}]", '', '', "class='form-control'" );?></td>
            <input type='hidden' name='gitlabID'   value='<?php echo $gitlabID;?>' />
            <input type='hidden' name='gitlabProjectID' value='<?php echo $gitlabProjectID;?>'/>
         </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td class='text-center form-actions' colspan='4'>
              <?php echo html::submitButton($lang->save);?>
              <?php if(!isonlybody()) echo html::a($this->createLink('repo', 'maintain', ""), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
  <?php else:?>
  <?php echo $lang->gitlab->noImportableIssues;?>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
