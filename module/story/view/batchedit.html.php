<?php
/**
 * The batch edit view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' action="<?php echo $this->inLink('batchEdit', "from=storyBatchEdit&productID=$productID")?>">
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->story->common . $lang->colon . $lang->story->batchEdit;?></caption>
    <tr>
      <th class='w-30px'> <?php echo $lang->idAB;?></th> 
      <th>  <?php echo $lang->story->title;?></th>
      <th class='w-30px'> <?php echo $lang->story->estimateAB;?></th>
      <th class='w-50px'> <?php echo $lang->priAB;?></th>
      <th class='w-100px'><?php echo $lang->story->module;?></th>
      <th class='w-100px'><?php echo $lang->story->planAB;?></th>
      <th class='w-80px'> <?php echo $lang->story->source;?></th>
      <th class='w-60px'> <?php echo $lang->story->status;?></th>
      <th class='w-80px'> <?php echo $lang->story->stageAB;?></th>
      <th class='w-100px'><?php echo $lang->story->closedBy;?></th>
      <th class='w-120px'><?php echo $lang->story->closedReason;?></th>
    </tr>
    <?php foreach($editedStories as $story):?>
    <tr class='a-center'>
      <td><?php echo $story->id . html::hidden("storyIDList[$story->id]", $story->id);?></td>
      <td><?php echo html::input("titles[$story->id]",         $story->title, 'class=text-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::input("estimates[$story->id]",      $story->estimate, 'class=text-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::select("pris[$story->id]",          (array)$lang->story->priList, $story->pri, 'class=select-1');?></td>
      <td><?php echo html::select("modules[$story->id]",       $moduleOptionMenu, $story->module, 'class=select-1');?></td>
      <td><?php echo html::select("plans[$story->id]",         $plans, $story->plan, 'class=select-1');?></td>
      <td><?php echo html::select("sources[$story->id]",       $lang->story->sourceList, $story->source, 'class=select-1');?></td>
      <td><?php echo $lang->story->statusList[$story->status];?></td>

      <?php if($story->status != 'draft'):?> 
      <td><?php echo html::select("stages[$story->id]",        $lang->story->stageList, $story->stage, 'class=select-1');?></td>
      <?php else:?>  
      <td><?php echo html::select("stages[$story->id]",        $lang->story->stageList, $story->stage, 'class="select-1" disabled="disabled"');?></td>
      <?php endif;?>

      <?php if($story->status == 'closed'):?> 
      <td><?php echo html::select("closedBys[$story->id]",     $users, $story->closedBy, 'class="select-1"');?></td>
      <?php else:?>  
      <td><?php echo html::select("closedBys[$story->id]",     $users, $story->closedBy, 'class="select-1" disabled="disabled"');?></td>
      <?php endif;?>

      <?php if($story->status == 'closed'):?>  
      <td>
        <div class='f-left'><?php echo html::select("closedReasons[$story->id]", $lang->story->reasonList, $story->closedReason, "class=w-60px onchange=setDuplicateAndChild(this.value,$story->id)");?></div>

        <div class='f-left' id='<?php echo 'duplicateStoryBox' . $story->id;?>' <?php if($story->closedReason != 'duplicate') echo "style='display:none'";?>>
        <?php echo html::input("duplicateStoryIDList[$story->id]", '', "class=w-30px placeholder='{$lang->idAB}'");?>
        </div>

        <div class='f-left' id='<?php echo 'childStoryBox' . $story->id;?>' <?php if($story->closedReason != 'subdivided') echo "style='display:none'";?>>
        <?php echo html::input("childStoriesIDList[$story->id]", '', "class=w-30px placeholder='{$lang->idAB}'");?>
        </div>
      </td>
      <?php else:?>  
      <td class='f-left'><?php echo html::select("closedReasons[$story->id]", $lang->story->reasonList, $story->closedReason, 'class="w-60px" disabled="disabled"');?></td>
      <?php endif;?>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='11'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='11' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
