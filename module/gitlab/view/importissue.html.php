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
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-borderless">
        <thead>
          <tr>
            <th width='30px'><?php echo $lang->product->common;?></th>
            <th width='30px'><?php echo $lang->execution->common;?></th>
            <th width='60px'><?php echo $lang->gitlab->gitlabIssue;?></th>
            <th width='30px'><?php echo $lang->gitlab->objectType;?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $productName; ?></td>
            <input type='hidden' name='productID' value='<?php echo $productID;?>' />
            <input type='hidden' name='gitlabID'   value='<?php echo $gitlabID;?>' />
            <input type='hidden' name='gitlabProjectID' value='<?php echo $gitlabProjectID;?>' />
            <td><?php echo html::select("executionName", $executions, '', "class='form-control select chosen'" );?></td>
            <td><?php echo html::select("gitlabIssueID", $gitlabIssues, '', "class='form-control select chosen'" );?></td>
            <td><?php echo html::select("objectType", $objectTypes, '', "class='form-control select chosen'" );?></td>
         </tr>
        </tbody>
        <tfoot>
          <tr>
            <td class="text-center form-actions">
              <?php echo html::submitButton($lang->save);?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
