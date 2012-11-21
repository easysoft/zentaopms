<?php
/**
 * The batch close view of story module of ZenTaoPMS.
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
<?php include '../../common/view/colorize.html.php';?>
<form method='post' target='hiddenwin' action="<?php echo $this->inLink('batchClose', "from=storyBatchClose")?>">
  <table class='table-1 fixed colored'> 
    <caption><?php echo $lang->story->common . $lang->colon . $lang->story->batchClose;?></caption>
    <tr>
      <th class='w-30px'> <?php echo $lang->idAB;?></th> 
      <th>                <?php echo $lang->story->title;?></th>
      <th class='w-80px'> <?php echo $lang->story->status;?></th>
      <th class='w-120px'><?php echo $lang->story->closedReason;?></th>
      <th class='w-p30 '> <?php echo $lang->story->comment;?></th>
    </tr>
    <?php foreach($editedStories as $story):?>
    <tr class='a-center'>
      <td><?php echo $story->id . html::hidden("storyIDList[$story->id]", $story->id);?></td>
      <td class='a-left'><?php echo $story->title;?></td>
      <td><?php echo $lang->story->statusList[$story->status];?></td>

      <?php if($story->status != 'closed'):?>
      <td>
        <div class='f-left'>
        <?php
        if($story->status == 'draft') unset($this->lang->story->reasonList['cancel']);
        echo html::select("closedReasons[$story->id]", $lang->story->reasonList, 'done', "class=w-70px onchange=setDuplicateAndChild(this.value,$story->id)");
        ?>
        </div>
        <div class='f-left' id='<?php echo 'duplicateStoryBox' . $story->id;?>' <?php if($story->closedReason != 'duplicate') echo "style='display:none'";?>>
        <?php echo html::input("duplicateStoryIDList[$story->id]", '', "class=w-30px placeholder='{$lang->idAB}'");?>
        </div>

        <div class='f-left' id='<?php echo 'childStoryBox' . $story->id;?>' <?php if($story->closedReason != 'subdivided') echo "style='display:none'";?>>
        <?php echo html::input("childStoriesIDList[$story->id]", '', "class=w-30px placeholder='{$lang->idAB}'");?>
        </div>
      </td>
      <?php else:?>  
      <td>
        <div class='f-left'>
          <?php echo html::select("closedReasons[$story->id]", $lang->story->reasonList, $story->closedReason, 'class="w-60px" disabled="disabled"');?>
        </div>
      </td>
      <?php endif;?>

      <td><?php echo html::textarea("comments[$story->id]", '', "rows='1' class='area-1'");?></td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='5'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='5' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
