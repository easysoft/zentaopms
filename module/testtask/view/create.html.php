<?php
/**
 * The create view of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('projectID', $projectID);?>
<?php js::set('multiple', isset($noMultipleExecutionID) ? false : true);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->testtask->create;?></h2>
    </div>
    <form method='post' class="main-form form-ajax" enctype="multipart/form-data" id='dataform'>
      <table class='table table-form'>
        <?php if(isset($executionID)):?>
        <tr <?php if(!empty($product->shadow)) echo "class='hide'";?>>
          <th class='w-100px'><?php echo $lang->testtask->product;?></th>
          <td class='w-p35-f'><?php echo html::select('product', $products, $product->id, "class='form-control chosen' onchange='loadProductRelated()'");?></td><td></td>
        </tr>
        <?php else:?>
        <tr class='hide'>
          <th class='w-100px'><?php echo $lang->testtask->product;?></th>
          <td class='w-p35-f'><?php echo html::input('product', $product->id, "class='form-control' onchange='loadTestReports(this.value)'");?></td><td></td>
        </tr>
        <?php endif;?>

        <?php if(isset($noMultipleExecutionID)):?>
        <?php echo html::hidden('execution', $noMultipleExecutionID);?>
        <?php else:?>
        <tr class='<?php echo ($app->tab == 'execution' and $executionID) ? 'hide' : '';?>'>
          <th class='w-100px'><?php echo $lang->testtask->execution;?></th>
          <td class='w-p35-f'><?php echo html::select('execution', $executions, $executionID, "class='form-control chosen' onchange='loadExecutionRelated(this.value)'");?></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->testtask->build;?></th>
          <td>
            <div class='input-group' id='buildBox'>
              <?php echo html::select('build', empty($builds) ? '' : $builds, $build, "class='form-control chosen'");?>

              <?php if(isset($executionID) and $executionID and empty($builds)):?>
              <span class='input-group-addon'><?php echo html::a(helper::createLink('build', 'create', "executionID=$executionID&productID={$product->id}&projectID={$projectID}", '', true), $lang->build->create, '', "data-toggle='modal' data-type='iframe' data-width='95%'")?> </span>
              <div class='hidden'><?php echo '&nbsp; ' .  html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadExecutionBuilds($executionID)'");?></div>
              <?php endif;?>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->type;?></th>
          <td><?php echo html::select('type[]', $lang->testtask->typeList, '', "class='form-control picker-select' multiple");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->owner;?></th>
          <td>
            <div id='ownerAndPriBox' class='input-group'>
              <?php echo html::select('owner', $users, '', "class='form-control chosen'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->testtask->pri;?></span>
              <?php echo html::select('pri', $lang->testtask->priList, 3, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->begin;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', '', "class='form-control form-date' onchange='suitEndDate()'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->testtask->end;?></span>
              <?php echo html::input('end', '', "class='form-control form-date'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->status;?></th>
          <td><?php echo html::select('status', $lang->testtask->statusList, '',  "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->testreport;?></th>
          <td><?php echo html::select('testreport', $testreports, '',  "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->name;?></th>
          <td colspan='2'><?php echo html::input('name', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->desc;?></th>
          <td colspan='2'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=testtask&link=desc');?>
            <?php echo html::textarea('desc', '', "rows=10 class='form-control'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->files;?></th>
          <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->mailto;?></th>
          <td colspan='2'>
            <div id='mailtoGroup' class='input-group'>
              <?php
              echo html::select('mailto[]', $users, '', "multiple class='form-control picker-select'");
              echo $this->fetch('my', 'buildContactLists');
              ?>
            </div>
          </td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <tr>
          <td class='text-center form-actions' colspan='3'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
