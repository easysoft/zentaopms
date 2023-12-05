<?php
/**
 * The edit view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label-id'><?php echo $build->id;?></span>
        <?php echo html::a($this->createLink('build', 'view', 'build=' . $build->id), $build->name, '', "title='$build->name'");?>
        <small><?php echo $lang->arrow . $lang->build->edit;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' id='dataform' enctype='multipart/form-data'>
      <table class='table table-form'>
        <tr class="<?php echo $hidden;?>">
          <th class='w-120px'><?php echo $lang->build->product;?></th>
          <td>
            <?php
            $disabled = '';
            if($build->stories or $build->bugs or $testtaskID) $disabled = 'disabled';
            ?>
            <div class='input-group'>
              <?php echo html::select('product', $products, $build->product, "onchange='loadBranches(this.value);' class='form-control chosen' $disabled required");?>
            </div>
          </td>
          <td><?php if($disabled) echo $lang->build->notice->changeProduct;?></td>
        </tr>
        <tr class='<?php if((!empty($product) and $product->type == 'normal') or empty($product) or empty($build->execution)) echo 'hidden'?>'>
          <?php
          if(empty($product)) $product = new stdclass();
          $productType     = zget($product, 'type', 'normal');
          $productBranches = zget($product, 'branches', array());
          ?>
          <th class='w-120px'><?php echo $product->type == 'normal' ? '' : $lang->product->branchName[$product->type]?></th>
          <td>
            <div class='input-group' id='branchBox'>
              <?php echo html::select('branch[]', $branchTagOption, $build->branch, "class='form-control chosen' multiple required"); ?>
            </div>
          </td>
        </tr>
        <?php $disabled = $testtaskID ? 'disabled' : '';?>
        <?php if(!$build->execution):?>
        <tr>
          <th class='w-120px'><?php echo $lang->build->builds;?></th>
          <td id='buildBox'><?php echo html::select('builds[]', $builds, $build->builds, "class='form-control chosen' multiple $disabled data-placeholder='{$lang->build->placeholder->multipleSelect}'");?></td>
          <td>
            <?php if($disabled):?>
            <?php echo $lang->build->notice->changeBuilds;?>
            <?php else:?>
            <?php echo $lang->build->notice->autoRelation;?>
            <?php endif;?>
          </td>
        </tr>
        <?php elseif(!empty($multipleProject)):?>
        <th class='w-120px'><?php echo $executionType ? $lang->build->executionAB : $lang->build->execution;?></th>
        <td id='executionsBox'><?php echo html::select('execution', $executions, $build->execution, "class='form-control chosen' required $disabled");?></td>
        <td><?php if($disabled) echo $lang->build->notice->changeExecution;?></td>
        <?php endif;?>
        <tr>
          <th class='w-120px'><?php echo $lang->build->name;?></th>
          <td><?php echo html::input('name', $build->name, "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->builder;?></th>
          <td><?php echo html::select('builder', $users, $build->builder, 'class="form-control chosen" required');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->date;?></th>
          <td><?php echo html::input('date', $build->date, "class='form-control form-date' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->scmPath;?></th>
          <td colspan='2'><?php echo html::input('scmPath', $build->scmPath, "class='form-control' placeholder='{$lang->build->placeholder->scmPath}'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->filePath;?></th>
          <td colspan='2'><?php echo html::input('filePath', $build->filePath, "class='form-control' placeholder='{$lang->build->placeholder->filePath}'");?></td>
        </tr>
        <?php $this->printExtendFields($build, 'table', 'columns=2');?>
        <tr>
          <th><?php echo $lang->build->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildForm');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', htmlSpecialString($build->desc), "rows='10' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td colspan="3" class="text-center form-actions">
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php js::set('productGroups', $productGroups)?>
<?php js::set('projectID', $build->project)?>
<?php js::set('oldBranch', $oldBranch)?>
<?php js::set('builds', $build->builds)?>
<?php js::set('buildID', $build->id)?>
<?php js::set('executionID', $build->execution)?>
<?php js::set('currentTab', $this->app->tab);?>
<?php js::set('multipleSelect', $lang->build->placeholder->multipleSelect);?>
<?php include '../../common/view/footer.html.php';?>
