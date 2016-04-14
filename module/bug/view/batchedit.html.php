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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['bug']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->bug->common . $lang->colon . $lang->bug->batchEdit;?></strong>
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
</div>
<?php
$hasFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$hasFields[$field] = '';
}
$columns = count($hasFields) + 2;
?>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID");?>">
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-50px'><?php echo $lang->idAB;?></th>
        <th class='w-110px<?php echo zget($hasFields, 'type', ' hidden')?>'><?php echo $lang->bug->type;?></th>
        <th class='w-70px<?php echo zget($hasFields, 'severity', ' hidden')?>'><?php echo $lang->bug->severityAB;?></th>
        <th class='w-70px<?php echo zget($hasFields, 'pri', ' hidden')?>'><?php echo $lang->bug->pri;?></th>
        <th <?php if(count($hasFields) >= 10) echo "class='w-150px'"?>><?php echo $lang->bug->title;?> <span class='required'></span></th>
        <th class='w-150px<?php echo zget($hasFields, 'productplan', ' hidden')?>'><?php echo $lang->bug->productplan;?></th>
        <th class='w-150px<?php echo zget($hasFields, 'assignedTo', ' hidden')?>'><?php echo $lang->bug->assignedTo;?></th>
        <th class='w-90px<?php echo zget($hasFields, 'status', ' hidden')?>'><?php echo $lang->bug->status;?></th>
        <th class='w-150px<?php echo zget($hasFields, 'resolvedBy', ' hidden')?>'><?php echo $lang->bug->resolvedByAB;?></th>
        <th class='w-180px<?php echo zget($hasFields, 'resolution', ' hidden')?>'><?php echo $lang->bug->resolutionAB;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'os', ' hidden')?>'><?php echo $lang->bug->os;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'browser', ' hidden')?>'><?php echo $lang->bug->browser;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'keywords', ' hidden')?>'><?php echo $lang->bug->keywords;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($bugIDList as $bugID):?>
      <?php
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
        <td <?php echo zget($hasFields, 'type', "class='hidden'")?>><?php echo html::select("types[$bugID]",      $typeList, $bugs[$bugID]->type, 'class=form-control');?></td>
        <td <?php echo zget($hasFields, 'severity', "class='hidden'")?>><?php echo html::select("severities[$bugID]", $severityList, $bugs[$bugID]->severity, 'class=form-control');?></td>
        <td <?php echo zget($hasFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$bugID]",       $priList, $bugs[$bugID]->pri, 'class=form-control');?></td>
        <td title='<?php echo $bugs[$bugID]->title?>'> <?php echo html::input("titles[$bugID]", $bugs[$bugID]->title, 'class=form-control');?></td>
        <td class='text-left<?php echo zget($hasFields, 'productplan', ' hidden')?>' style='overflow:visible'><?php echo html::select("plans[$bugID]", $plans, $bugs[$bugID]->plan, "class='form-control chosen'");?></td>
        <td class='text-left<?php echo zget($hasFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo html::select("assignedTos[$bugID]", $users, $bugs[$bugID]->assignedTo, "class='form-control chosen'");?></td>
        <td <?php echo zget($hasFields, 'status', "class='hidden'")?>><?php echo html::select("statuses[$bugID]", $lang->bug->statusList, $bugs[$bugID]->status, 'class=form-control');?></td>
        <td class='text-left<?php echo zget($hasFields, 'resolvedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("resolvedBys[$bugID]", $users, $bugs[$bugID]->resolvedBy, "class='form-control chosen'");?></td>
        <td <?php echo zget($hasFields, 'resolution', "class='hidden'")?>>
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
        <td <?php echo zget($hasFields, 'os', "class='hidden'")?>><?php echo html::select("os[$bugID]", $lang->bug->osList, $bugs[$bugID]->os, 'class=form-control');?></td>
        <td <?php echo zget($hasFields, 'browser', "class='hidden'")?>><?php echo html::select("browsers[$bugID]", $lang->bug->browserList, $bugs[$bugID]->browser, 'class=form-control');?></td>
        <td <?php echo zget($hasFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$bugID]", $bugs[$bugID]->keywords, 'class=form-control');?></td>
      </tr>
      <?php endforeach;?>
      <?php if(isset($suhosinInfo)):?>
      <tr><td colspan='<?php echo $columns;?>'><div class='alert alert-info'><?php echo $suhosinInfo;?></div></td></tr>
      <?php endif;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo $columns;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustom', 'module=bug&section=custom&key=batchedit')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
