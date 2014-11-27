<?php
/**
 * The batch edit view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
  </div>
</div>

<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID");?>">
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-110px'><?php echo $lang->bug->type;?></th>
        <th class='w-70px'><?php echo $lang->bug->severityAB;?></th>
        <th class='w-70px'><?php echo $lang->bug->pri;?></th>
        <th><?php echo $lang->bug->title;?> <span class='required'></span></th>
        <th class='w-150px'><?php echo $lang->bug->assignedTo;?></th>
        <th class='w-90px'><?php echo $lang->bug->status;?></th>
        <th class='w-150px'><?php echo $lang->bug->resolvedByAB;?></th>
        <th class='w-180px'><?php echo $lang->bug->resolutionAB;?></th>
      </tr>
    </thead>
    <?php foreach($bugIDList as $bugID):?>
    <?php
    /**
     * Remove designchange, newfeature, trackings from the typeList, because should be tracked in story or task. 
     * These thress types if upgrade from bugfree2.x.
     */
    $typeList = $lang->bug->typeList;
    if($bugs[$bugID]->type != 'designchange') unset($typeList['designchange']);
    if($bugs[$bugID]->type != 'newfeature')   unset($typeList['newfeature']);
    if($bugs[$bugID]->type != 'trackthings')  unset($typeList['trackthings']);
    ?>
    <tr class='text-center'>
      <td><?php echo $bugID . html::hidden("bugIDList[$bugID]", $bugID);?></td>
      <td><?php echo html::select("types[$bugID]",         $typeList, $bugs[$bugID]->type, 'class=form-control');?></td>
      <td><?php echo html::select("severities[$bugID]",   (array)$lang->bug->severityList, $bugs[$bugID]->severity, 'class=form-control');?></td>
      <td><?php echo html::select("pris[$bugID]",         (array)$lang->bug->priList, $bugs[$bugID]->pri, 'class=form-control');?></td>
      <td><?php echo html::input("titles[$bugID]",         $bugs[$bugID]->title, 'class=form-control');?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("assignedTos[$bugID]",   $users, $bugs[$bugID]->assignedTo, "class='form-control chosen'");?></td>
      <td><?php echo html::select("statuses[$bugID]",     (array)$lang->bug->statusList, $bugs[$bugID]->status, 'class=form-control');?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("resolvedBys[$bugID]",   $users, $bugs[$bugID]->resolvedBy, "class='form-control chosen'");?></td>
      <td>
        <table class='w-p100'>
          <tr>
            <td class='pd-0'>
              <?php echo html::select("resolutions[$bugID]",   $this->lang->bug->resolutionList, $bugs[$bugID]->resolution, "class=form-control onchange=setDuplicate(this.value,$bugID)");?>
            </td>
            <td class='pd-0 w-p50' id='<?php echo 'duplicateBugBox' . $bugID;?>' <?php if($bugs[$bugID]->resolution != 'duplicate') echo "style='display:none'";?>>
              <?php echo html::input("duplicateBugs[$bugID]", '', "class=form-control placeholder='{$lang->bug->duplicateBug}'");?>
            </td>
          </tr>
        </table>
      </td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='<?php echo $this->config->bug->batchEdit->columns;?>'><div class='alert alert-info'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='<?php echo $this->config->bug->batchEdit->columns;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
