<?php
/**
 * The batch edit view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' action="<?php echo $this->inLink('batchEdit', "from=bugBatchEdit&productID=$productID");?>">
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->bug->common . $lang->colon . $lang->bug->batchEdit;?></caption>
    <tr>
      <th class='w-30px'><?php echo $lang->idAB;?></th> 
      <th class='w-80px'><?php echo $lang->bug->type;?></th>
      <th class='w-50px'><?php echo $lang->bug->severityAB;?></th>
      <th class='w-50px'><?php echo $lang->bug->pri;?></th>
      <th> <?php echo $lang->bug->title;?></th>
      <th class='w-80px'><?php echo $lang->bug->assignedTo;?></th>
      <th class='w-70px'><?php echo $lang->bug->status;?></th>
      <th class='w-80px'><?php echo $lang->bug->resolvedByAB;?></th>
      <th class='w-150px'><?php echo $lang->bug->resolutionAB;?></th>
    </tr>
    <?php foreach($editedBugs as $bug):?>
    <?
    /**
     * Remove designchange, newfeature, trackings from the typeList, because should be tracked in story or task. 
     * These thress types if upgrade from bugfree2.x.
     */
    if($bug->type != 'designchange') unset($this->lang->bug->typeList['designchange']);
    if($bug->type != 'newfeature')   unset($this->lang->bug->typeList['newfeature']);
    if($bug->type != 'trackthings')  unset($this->lang->bug->typeList['trackthings']);
    ?>
    <tr class='a-center'>
      <td><?php echo $bug->id . html::hidden("bugIDList[$bug->id]", $bug->id);?></td>
      <td><?php echo html::select("types[$bug->id]",         $lang->bug->typeList, $bug->type, 'class=select-1');?></td>
      <td><?php echo html::select("severities[$bug->id]",   (array)$lang->bug->severityList, $bug->severity, 'class=select-1');?></td>
      <td><?php echo html::select("pris[$bug->id]",         (array)$lang->bug->priList, $bug->pri, 'class=select-1');?></td>
      <td><?php echo html::input("titles[$bug->id]",         $bug->title, 'class=text-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::select("assignedTos[$bug->id]",   $users, $bug->assignedTo, 'class=select-1');?></td>
      <td><?php echo html::select("statuses[$bug->id]",     (array)$lang->bug->statusList, $bug->status, 'class=select-1');?></td>
      <td><?php echo html::select("resolvedBys[$bug->id]",   $users, $bug->resolvedBy, 'class=select-1');?></td>
      <td>
        <div class='f-left'><?php echo html::select("resolutions[$bug->id]",   $this->lang->bug->resolutionList, $bug->resolution, "class=w-80px onchange=setDuplicate(this.value,$bug->id)");?></div>
        <div class='f-left' id='<?php echo 'duplicateBugBox' . $bug->id;?>' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>><?php echo html::input("duplicateBugs[$bug->id]", '', "class=w-40px placeholder='{$lang->bug->duplicateBug}'");?></div>
      </td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='9'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='9' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
