<?php
/**
 * The batch edit view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('dittoNotice', $this->lang->bug->dittoNotice);?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2><?php echo $lang->bug->common . $lang->colon . $lang->bug->batchEdit;?></h2>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=bug&section=custom&key=batchEditFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>

  <?php if(isset($suhosinInfo)):?>
  <div class='alert alert-info'><?php echo $suhosinInfo;?></div>
  <?php else:?>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field)$visibleFields[$field] = '';
  }
  foreach(explode(',', $config->bug->edit->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->bug->list->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $columns = count($visibleFields) + 2;
  ?>
  <form class='load-indicator main-form' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID")?>" id='batchEditForm'>
    <div class="table-responsive">
      <table class='table table-form'>
        <thead>
          <tr>
            <th class='w-50px'><?php echo $lang->idAB;?></th>
            <th class='w-110px<?php echo zget($visibleFields, 'type', ' hidden') . zget($requiredFields, 'type', '', ' required');?>'><?php echo $lang->bug->type;?></th>
            <th class='w-70px<?php echo zget($visibleFields, 'severity', ' hidden') . zget($requiredFields, 'severity', '', ' required');?>'><?php echo $lang->bug->severityAB;?></th>
            <th class='w-70px<?php echo zget($visibleFields, 'pri', ' hidden') . zget($requiredFields, 'pri', '', ' required');?>'><?php echo $lang->bug->pri;?></th>
            <th class="required <?php if(count($visibleFields) >= 10) echo ' w-150px';?>"><?php echo $lang->bug->title;?></th>
            <?php if($branchProduct):?>
            <th class='w-150px<?php echo zget($visibleFields, 'branch', ' hidden')?>'><?php echo $lang->bug->branch;?></th>
            <?php endif;?>
            <th class='w-150px<?php echo zget($visibleFields, 'productplan', ' hidden') . zget($requiredFields, 'productplan', '', ' required');?>'><?php echo $lang->bug->productplan;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'assignedTo', ' hidden') . zget($requiredFields, 'assignedTo', '', ' required');?>'><?php echo $lang->bug->assignedTo;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'deadline', ' hidden') . zget($requiredFields, 'deadline', '', ' required');?>'><?php echo $lang->bug->deadline;?></th>
            <th class='w-90px<?php echo zget($visibleFields, 'status', ' hidden')?>'><?php echo $lang->bug->status;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'os', ' hidden') . zget($requiredFields, 'os', '', ' required');?>'><?php echo $lang->bug->os;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'browser', ' hidden') . zget($requiredFields, 'browser', '', ' required');?>'><?php echo $lang->bug->browser;?></th>
            <th class='w-100px<?php echo zget($visibleFields, 'keywords', ' hidden') . zget($requiredFields, 'keywords', '', ' required');?>'><?php echo $lang->bug->keywords;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'resolvedBy', ' hidden')?>'><?php echo $lang->bug->resolvedByAB;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'resolution', ' hidden')?>'><?php echo $lang->bug->resolutionAB;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($bugs as $bugID => $bug):?>
          <?php
          if(!$productID)
          {
              $product = $this->product->getByID($bug->product);
    
              $plans = $this->loadModel('productplan')->getPairs($bug->product, $branch);
              $plans = array('' => '', 'ditto' => $this->lang->bug->ditto) + $plans;
    
              $branches = $product->type == 'normal' ? array('' => '') : $this->loadModel('branch')->getPairs($product->id);
              if($product->type != 'normal')
              {
                  foreach($branches as $branchID => $branchName) $branches[$branchID] = '/' . $product->name . '/' . $branchName;
                  $branches = array('ditto' => $this->lang->story->ditto) + $branches;
              }
          }
          /**
           * Remove designchange, newfeature, trackings from the typeList, because should be tracked in story or task.
           * These thress types if upgrade from bugfree2.x.
           */
          if($bug->type != 'designchange') unset($typeList['designchange']);
          if($bug->type != 'newfeature')   unset($typeList['newfeature']);
          if($bug->type != 'trackthings')  unset($typeList['trackthings']);
          ?>
          <tr>
            <td><?php echo $bugID . html::hidden("bugIDList[$bugID]", $bugID);?></td>
            <td <?php echo zget($visibleFields, 'type', "class='hidden'")?>><?php echo html::select("types[$bugID]", $typeList, $bug->type, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'severity', "class='hidden'")?>><?php echo html::select("severities[$bugID]", $severityList, $bug->severity, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$bugID]", $priList, $bug->pri, 'class=form-control');?></td>
            <td style='overflow:visible' title='<?php echo $bug->title?>'>
              <div class='input-group'>
                <div class="input-control has-icon-right">
                  <?php echo html::input("titles[$bugID]", $bug->title, "class='form-control' autocomplete='off' style='color:{$bug->color}'");?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar" style="background:<?php echo $bug->color;?>"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <?php echo html::hidden("colors[$bugID]", $bug->color, "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#titles\\[{$bugID}\\]'");?>
                  </div>
                </div>
              <div>
            </td>
            <?php if($branchProduct):?>
            <td class='<?php echo zget($visibleFields, 'branch', ' hidden')?>' style='overflow:visible'>
              <?php $branchProductID = $productID ? $productID : $product->id;?>
              <?php $disabled        = (isset($product) and $product->type == 'normal') ? "disabled='disabled'" : '';?>
              <?php echo html::select("branches[$bugID]", $branches, $bug->branch, "class='form-control chosen' $disabled");?>
            </td>
            <?php endif;?>
            <td class='<?php echo zget($visibleFields, 'productplan', ' hidden')?>' style='overflow:visible'><?php echo html::select("plans[$bugID]", $plans, $bug->plan, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo html::select("assignedTos[$bugID]", $users, $bug->assignedTo, "class='form-control chosen'");?></td>
            <td class='<?php echo zget($visibleFields, 'deadline', ' hidden')?>' style='overflow:visible'><?php echo html::input("deadlines[$bugID]", $bug->deadline, "class='form-control form-date'");?></td>
            <td <?php echo zget($visibleFields, 'status', "class='hidden'")?>><?php echo html::select("statuses[$bugID]", $statusList, $bug->status, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'os', "class='hidden'")?>><?php echo html::select("os[$bugID]", $osList, $bug->os, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'browser', "class='hidden'")?>><?php echo html::select("browsers[$bugID]", $browserList, $bug->browser, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$bugID]", $bug->keywords, 'class=form-control');?></td>
            <td class='<?php echo zget($visibleFields, 'resolvedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("resolvedBys[$bugID]", $users, $bug->resolvedBy, "class='form-control chosen'");?></td>
            <td <?php echo zget($visibleFields, 'resolution', "class='hidden'")?>>
              <table class='table-borderless table no-margin'>
                <tr>
                  <td class='pd-0'><?php echo html::select("resolutions[$bugID]", $resolutionList, $bug->resolution, "class='form-control' onchange=setDuplicate(this.value,$bugID)");?></td>
                  <td class='pd-0 w-p50' id='<?php echo 'duplicateBugBox' . $bugID;?>' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
                    <?php echo html::input("duplicateBugs[$bugID]", '', "class='form-control duplicate-input' placeholder='{$lang->bug->duplicateBug}'");?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo $branchProduct ? $columns : ($columns - 1);?>' class='text-center form-actions'>
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
