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
<form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID");?>">
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->bug->common . $lang->colon . $lang->bug->batchEdit;?></caption>
    <tr>
      <th class='w-30px'><?php echo $lang->idAB;?></th> 
      <th class='w-80px'><?php echo $lang->bug->type;?></th>
      <th class='w-50px'><?php echo $lang->bug->severityAB;?></th>
      <th class='w-50px'><?php echo $lang->bug->pri;?></th>
      <th class='red'> <?php echo $lang->bug->title;?></th>
      <th class='w-100px'><?php echo $lang->bug->assignedTo;?></th>
      <th class='w-70px'><?php echo $lang->bug->status;?></th>
      <th class='w-100px'><?php echo $lang->bug->resolvedByAB;?></th>
      <th class='w-150px'><?php echo $lang->bug->resolutionAB;?></th>
    </tr>
    <?php foreach($bugIDList as $bugID):?>
    <?php
    /**
     * Remove designchange, newfeature, trackings from the typeList, because should be tracked in story or task. 
     * These thress types if upgrade from bugfree2.x.
     */
    if($bugs[$bugID]->type != 'designchange') unset($this->lang->bug->typeList['designchange']);
    if($bugs[$bugID]->type != 'newfeature')   unset($this->lang->bug->typeList['newfeature']);
    if($bugs[$bugID]->type != 'trackthings')  unset($this->lang->bug->typeList['trackthings']);
    ?>
    <tr class='a-center'>
      <td><?php echo $bugID . html::hidden("bugIDList[$bugID]", $bugID);?></td>
      <td><?php echo html::select("types[$bugID]",         $lang->bug->typeList, $bugs[$bugID]->type, 'class=select-1');?></td>
      <td><?php echo html::select("severities[$bugID]",   (array)$lang->bug->severityList, $bugs[$bugID]->severity, 'class=select-1');?></td>
      <td><?php echo html::select("pris[$bugID]",         (array)$lang->bug->priList, $bugs[$bugID]->pri, 'class=select-1');?></td>
      <td><?php echo html::input("titles[$bugID]",         $bugs[$bugID]->title, 'class=text-1');?></td>
      <td><?php echo html::select("assignedTos[$bugID]",   $users, $bugs[$bugID]->assignedTo, 'class=select-1');?></td>
      <td><?php echo html::select("statuses[$bugID]",     (array)$lang->bug->statusList, $bugs[$bugID]->status, 'class=select-1');?></td>
      <td><?php echo html::select("resolvedBys[$bugID]",   $users, $bugs[$bugID]->resolvedBy, 'class=select-1');?></td>
      <td>
        <div class='f-left'><?php echo html::select("resolutions[$bugID]",   $this->lang->bug->resolutionList, $bugs[$bugID]->resolution, "class=w-80px onchange=setDuplicate(this.value,$bugID)");?></div>
        <div class='f-left' id='<?php echo 'duplicateBugBox' . $bugID;?>' <?php if($bugs[$bugID]->resolution != 'duplicate') echo "style='display:none'";?>><?php echo html::input("duplicateBugs[$bugID]", '', "class=w-40px placeholder='{$lang->bug->duplicateBug}'");?></div>
      </td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='<?php echo $this->config->bug->batchEdit->columns;?>'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='<?php echo $this->config->bug->batchEdit->columns;?>' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
