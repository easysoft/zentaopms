<?php
/**
 * The browse view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     plan
 * @version     $Id: browse.html.php 4707 2013-05-02 06:57:41Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->productplan->confirmDelete)?>
<?php js::set('browseType', $browseType);?>
<?php js::set('productID', $productID);?>
<?php js::set('noLinkedProject', $lang->productplan->noLinkedProject);?>
<?php js::set('enterProjectList', $lang->productplan->enterProjectList);?>
<?php js::set('projectNotEmpty', $lang->productplan->projectNotEmpty)?>
<?php js::set('viewType', $viewType);?>
<?php js::set('product', $product);?>
<?php js::set('branchStatusList', $branchStatusList);?>
<?php js::set('defaultMinColWidth', $this->config->minColWidth);?>
<?php js::set('defaultMaxColWidth', $this->config->maxColWidth);?>
<?php
if($viewType == 'kanban')
{
    include 'browsebykanban.html.php';
}
else
{
    include 'browsebylist.html.php';
}
?>
<div class="modal fade" id="projects">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $lang->productplan->selectProjects;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->productplan->project?></th>
            <td><?php echo html::select('project', $projects, '', "class='form-control chosen'");?></td>
          </tr>
          <tr class='tips hidden'>
            <th></th>
            <td><span class='text-red'><?php echo $lang->productplan->noLinkedProject;?></span></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              <?php echo html::hidden('planID', '');?>
              <?php echo html::commonButton($lang->productplan->nextStep, "id='createExecutionButton'", 'btn btn-primary btn-wide');?>
              <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-default btn-wide');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
