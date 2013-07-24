<?php
/**
 * The batch edit view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "from=storyBatchEdit")?>">
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->story->common . $lang->colon . $lang->story->batchEdit;?></caption>
    <tr>
      <th class='w-30px'> <?php echo $lang->idAB;?></th> 
      <th class='w-100px'><?php echo $lang->story->module;?></th>
      <th class='w-100px'><?php echo $lang->story->planAB;?></th>
      <th class='red'>    <?php echo $lang->story->title;?></th>
      <th class='w-30px'> <?php echo $lang->story->estimateAB;?></th>
      <th class='w-50px'> <?php echo $lang->priAB;?></th>
      <th class='w-80px'> <?php echo $lang->story->source;?></th>
      <th class='w-60px'> <?php echo $lang->story->status;?></th>
      <th class='w-80px'> <?php echo $lang->story->stageAB;?></th>
      <th class='w-100px'><?php echo $lang->story->closedBy;?></th>
      <th class='w-120px a-left'><?php echo $lang->story->closedReason;?></th>
    </tr>
    <?php foreach($storyIDList as $storyID):?>
    <tr class='a-center'>
      <td><?php echo $storyID . html::hidden("storyIDList[$storyID]", $storyID);?></td>
      <td><?php echo html::select("modules[$storyID]",       $moduleOptionMenus[$stories[$storyID]->product], $stories[$storyID]->module, 'class=select-1');?></td>
      <td><?php echo html::select("plans[$storyID]",         $productPlans[$stories[$storyID]->product], $stories[$storyID]->plan, 'class=select-1');?></td>
      <td><?php echo html::input("titles[$storyID]",         $stories[$storyID]->title, 'class=text-1'); ?></td>
      <td><?php echo html::input("estimates[$storyID]",      $stories[$storyID]->estimate, 'class=text-1'); ?></td>
      <td><?php echo html::select("pris[$storyID]",          (array)$lang->story->priList, $stories[$storyID]->pri, 'class=select-1');?></td>
      <td><?php echo html::select("sources[$storyID]",       $lang->story->sourceList, $stories[$storyID]->source, 'class=select-1');?></td>
      <td><?php echo $lang->story->statusList[$stories[$storyID]->status];?></td>

      <?php if($stories[$storyID]->status != 'draft'):?> 
      <td><?php echo html::select("stages[$storyID]",        $lang->story->stageList, $stories[$storyID]->stage, 'class=select-1');?></td>
      <?php else:?>  
      <td><?php echo html::select("stages[$storyID]",        $lang->story->stageList, $stories[$storyID]->stage, 'class="select-1" disabled="disabled"');?></td>
      <?php endif;?>

      <?php if($stories[$storyID]->status == 'closed'):?> 
      <td><?php echo html::select("closedBys[$storyID]",     $users, $stories[$storyID]->closedBy, 'class="select-1"');?></td>
      <?php else:?>  
      <td><?php echo html::select("closedBys[$storyID]",     $users, $stories[$storyID]->closedBy, 'class="select-1" disabled="disabled"');?></td>
      <?php endif;?>

      <?php if($stories[$storyID]->status == 'closed'):?>  
      <td>
        <div class='f-left'><?php echo html::select("closedReasons[$storyID]", $lang->story->reasonList, $stories[$storyID]->closedReason, "class=w-60px onchange=setDuplicateAndChild(this.value,$storyID)");?></div>

        <div class='f-left' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'duplicate') echo "style='display:none'";?>>
        <?php echo html::input("duplicateStoryIDList[$storyID]", '', "class=w-30px placeholder='{$lang->idAB}'");?>
        </div>

        <div class='f-left' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'subdivided') echo "style='display:none'";?>>
        <?php echo html::input("childStoriesIDList[$storyID]", '', "class=w-30px placeholder='{$lang->idAB}'");?>
        </div>
      </td>
      <?php else:?>  
      <td><div class='f-left'><?php echo html::select("closedReasons[$storyID]", $lang->story->reasonList, $stories[$storyID]->closedReason, 'class="w-60px" disabled="disabled"');?><div></td>
      <?php endif;?>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='<?php echo $this->config->story->batchEdit->columns;?>'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='<?php echo $this->config->story->batchEdit->columns;?>' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
