<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.chosen-container .chosen-drop.chosen-auto-max-width {max-width: 100%;}
</style>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->gitlab->importIssue;?></h2>
    <?php if(!isonlybody() and empty($importable)):?>
    <div class='pull-right'>
      <?php echo html::backButton($lang->goback, "data-app='{$app->tab}'", 'btn btn-primary');?>
    </div>
    <?php endif;?>
  </div>
  <?php if($importable):?>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-form with-border">
        <thead>
          <tr>
            <th class='c-issue'><?php echo $lang->gitlab->gitlabIssue;?></th>
            <th class='c-type'><?php echo $lang->gitlab->objectType;?></th>
            <th class='c-product'><?php echo $lang->productCommon;?></th>
            <th class='c-execution'><?php echo $lang->execution->common;?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($gitlabIssues as $issue):?>
          <tr>
            <td><?php echo "<a href='{$issue->web_url}' target='_blank'>$issue->title</a>";?></td>
            <td><?php echo html::select("objectTypeList[{$issue->iid}]", $objectTypes, '', "class='form-control select chosen'" );?></td>
            <td><?php echo html::select("productList[{$issue->iid}]", $products, '', "class='form-control select chosen'" );?></td>
            <td><?php echo html::select("executionList[{$issue->iid}]", '', '', "class='form-control select chosen'" );?></td>
            <input type='hidden' name='gitlabID'   value='<?php echo $gitlabID;?>' />
            <input type='hidden' name='gitlabProjectID' value='<?php echo $gitlabProjectID;?>'/>
         </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td class='text-center form-actions' colspan='4'>
              <?php echo html::submitButton($lang->save);?>
              <?php if(!isonlybody()) echo html::backButton($lang->goback, "data-app='{$app->tab}'", 'btn btn-wide');?>
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
