<?php
/**
 * The create view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $pageTitle;?></h2>
      </div>
      <form id='branchForm' method='post' class='form-ajax' enctype="multipart/form-data">
        <?php if($branch) echo html::hidden('name', $branch);?>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->branch->name;?></th>
            <td><?php echo html::select('name', $branches, $branchPriv->name, "class='form-control chosen' data-placeholder='{$lang->gitlab->branch->placeholderSelect}' " . ($branch ? 'disabled' : ''));?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->branch->mergeAllowed;?></th>
            <td><?php echo html::select('merge_access_level', $lang->gitlab->branch->branchCreationLevelList, $branchPriv->merge_access_level, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->branch->pushAllowed;?></th>
            <td><?php echo html::select('push_access_level', $lang->gitlab->branch->branchCreationLevelList, $branchPriv->push_access_level, "class='form-control'");?></td>
          </tr>
          <tr>
            <th></th>
            <td class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browseBranchPriv', "gitlabID=$gitlabID&projectID=$projectID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
