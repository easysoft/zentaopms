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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['bug']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->bug->common . $lang->colon . $lang->bug->batchEdit;?></strong>
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
</div>
<?php if(isset($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo;?></div>
<?php else:?>
<?php
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$visibleFields[$field] = '';
}
$columns = count($visibleFields) + 2;
?>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID");?>">
  <table class='table table-form table-fixed with-border'>
    <thead>
      <tr>
        <th class='w-50px'><?php echo $lang->idAB;?></th>
        <th class='w-110px<?php echo zget($visibleFields, 'type', ' hidden')?>'><?php echo $lang->bug->type;?></th>
        <th class='w-70px<?php echo zget($visibleFields, 'severity', ' hidden')?>'><?php echo $lang->bug->severityAB;?></th>
        <th class='w-70px<?php echo zget($visibleFields, 'pri', ' hidden')?>'><?php echo $lang->bug->pri;?></th>
        <th <?php if(count($visibleFields) >= 10) echo "class='w-150px'"?>><?php echo $lang->bug->title;?> <span class='required'></span></th>
        <?php if($branchProduct):?>
        <th class='w-150px<?php echo zget($visibleFields, 'branch', ' hidden')?>'><?php echo $lang->bug->branch;?></th>
        <?php endif;?>
        <th class='w-150px<?php echo zget($visibleFields, 'productplan', ' hidden')?>'><?php echo $lang->bug->productplan;?></th>
        <th class='w-150px<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>'><?php echo $lang->bug->assignedTo;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'deadline', ' hidden')?>'><?php echo $lang->bug->deadline;?></th>
        <th class='w-90px<?php echo zget($visibleFields, 'status', ' hidden')?>'><?php echo $lang->bug->status;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'os', ' hidden')?>'><?php echo $lang->bug->os;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'browser', ' hidden')?>'><?php echo $lang->bug->browser;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'keywords', ' hidden')?>'><?php echo $lang->bug->keywords;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'resolvedBy', ' hidden')?>'><?php echo $lang->bug->resolvedByAB;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'resolution', ' hidden')?>'><?php echo $lang->bug->resolutionAB;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($bugIDList as $bugID):?>
      <?php
      if(!$productID)
      {
          $product = $this->product->getByID($bugs[$bugID]->product);

          $plans = $this->loadModel('productplan')->getPairs($bugs[$bugID]->product, $branch);
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
      if($bugs[$bugID]->type != 'designchange') unset($typeList['designchange']);
      if($bugs[$bugID]->type != 'newfeature')   unset($typeList['newfeature']);
      if($bugs[$bugID]->type != 'trackthings')  unset($typeList['trackthings']);
      ?>
      <tr class='text-center'>
        <td><?php echo $bugID . html::hidden("bugIDList[$bugID]", $bugID);?></td>
        <td <?php echo zget($visibleFields, 'type', "class='hidden'")?>><?php echo html::select("types[$bugID]",      $typeList, $bugs[$bugID]->type, 'class=form-control');?></td>
        <td <?php echo zget($visibleFields, 'severity', "class='hidden'")?>><?php echo html::select("severities[$bugID]", $severityList, $bugs[$bugID]->severity, 'class=form-control');?></td>
        <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$bugID]",       $priList, $bugs[$bugID]->pri, 'class=form-control');?></td>
        <td style='overflow:visible' title='<?php echo $bugs[$bugID]->title?>'>
          <div class='input-group'>
          <?php echo html::hidden("colors[$bugID]", $bugs[$bugID]->color, "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->bug->colorTag}' data-update-text='#titles\\[{$bugID}\\]'");?>
          <?php echo html::input("titles[$bugID]", $bugs[$bugID]->title, 'class=form-control');?>
          <div>
        </td>
        <?php if($branchProduct):?>
        <td class='text-left<?php echo zget($visibleFields, 'branch', ' hidden')?>' style='overflow:visible'>
          <?php $branchProductID = $productID ? $productID : $product->id;?>
          <?php $disabled        = (isset($product) and $product->type == 'normal') ? "disabled='disabled'" : '';?>
          <?php echo html::select("branches[$bugID]", $branches, $bugs[$bugID]->branch, "class='form-control chosen' $disabled");?>
        </td>
        <?php endif;?>
        <td class='text-left<?php echo zget($visibleFields, 'productplan', ' hidden')?>' style='overflow:visible'><?php echo html::select("plans[$bugID]", $plans, $bugs[$bugID]->plan, "class='form-control chosen'");?></td>
        <td class='text-left<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo html::select("assignedTos[$bugID]", $users, $bugs[$bugID]->assignedTo, "class='form-control chosen'");?></td>
        <td class='<?php echo zget($visibleFields, 'deadline', ' hidden')?>' style='overflow:visible'><?php echo html::input("deadlines[$bugID]", $bugs[$bugID]->deadline, "class='form-control form-date'");?></td>
        <td <?php echo zget($visibleFields, 'status', "class='hidden'")?>><?php echo html::select("statuses[$bugID]", $statusList, $bugs[$bugID]->status, 'class=form-control');?></td>
        <td <?php echo zget($visibleFields, 'os', "class='hidden'")?>><?php echo html::select("os[$bugID]", $osList, $bugs[$bugID]->os, 'class=form-control');?></td>
        <td <?php echo zget($visibleFields, 'browser', "class='hidden'")?>><?php echo html::select("browsers[$bugID]", $browserList, $bugs[$bugID]->browser, 'class=form-control');?></td>
        <td <?php echo zget($visibleFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$bugID]", $bugs[$bugID]->keywords, 'class=form-control');?></td>
        <td class='text-left<?php echo zget($visibleFields, 'resolvedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("resolvedBys[$bugID]", $users, $bugs[$bugID]->resolvedBy, "class='form-control chosen'");?></td>
        <td <?php echo zget($visibleFields, 'resolution', "class='hidden'")?>>
          <table class='w-p100'>
            <tr>
              <td class='pd-0'>
                <?php echo html::select("resolutions[$bugID]", $resolutionList, $bugs[$bugID]->resolution, "class=form-control onchange=setDuplicate(this.value,$bugID)");?>
              </td>
              <td class='pd-0 w-p50' id='<?php echo 'duplicateBugBox' . $bugID;?>' <?php if($bugs[$bugID]->resolution != 'duplicate') echo "style='display:none'";?>>
                <?php echo html::input("duplicateBugs[$bugID]", '', "class=form-control placeholder='{$lang->bug->duplicateBug}'");?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo $branchProduct ? $columns : ($columns - 1);?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php endif;?>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=bug&section=custom&key=batchEditFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
