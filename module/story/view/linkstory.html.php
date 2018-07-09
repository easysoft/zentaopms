<?php
/**
 * The link story view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     story
 * @version     $Id: linkstory.html.php 4129 2016-03-09 08:58:13Z chenfei $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php js::set('linkType', $type);?>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $story->id;?></span>
      <?php echo isonlybody() ? ("<span title='$story->title'>" . $story->title . '</span>') : html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->story->linkStory;?></small>
      <?php endif;?>
    </h2>
  </div>
  <div id='queryBox' class='show divider'></div>
  <form method='post' target='hiddenwin' id='linkStoryForm' class='main-table table-story'>
    <?php if($stories2Link):?>
    <table class='table' id='storyList'>
      <thead>
      <tr>
        <th class='c-id'>
          <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
            <label></label>
          </div>
          <?php echo $lang->idAB;?>
        </th>
        <th class='c-pri'><?php echo $lang->priAB;?></th>
        <th><?php echo $lang->story->product;?></th>
        <th><?php echo $lang->story->title;?></th>
        <th><?php echo $lang->story->plan;?></th>
        <th class='w-user'><?php echo $lang->openedByAB;?></th>
        <th class='w-80px'><?php echo $lang->story->estimateAB;?></th>
      </tr>
      </thead>
      <tbody>
      <?php $storyCount = 0;?>
      <?php foreach($stories2Link as $story2Link):?>
      <?php $storyLink = $this->createLink('story', 'view', "storyID=$story2Link->id");?>
      <tr>
        <td class='c-id'>
          <div class="checkbox-primary">
            <input type='checkbox' name='stories[]'  value='<?php echo $story2Link->id;?>'/> 
            <label></label>
          </div>
          <?php echo html::a($storyLink, sprintf('%03d', $story2Link->id));?>
        </td>
        <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . $story2Link->pri?>' title='<?php echo zget($lang->story->priList, $story2Link->pri, $story2Link->pri);?>'><?php echo zget($lang->story->priList, $story2Link->pri, $story2Link->pri);?></span></td>
        <td><?php echo html::a($this->createLink('product', 'browse', "productID=$story2Link->product&branch=$story2Link->branch"), $products[$story2Link->product], '_blank');?></td>
        <td class='text-left nobr' title="<?php echo $story2Link->title?>"><?php echo html::a($storyLink, $story2Link->title, '_blank');?></td>
        <td><?php echo $story2Link->planTitle;?></td>
        <td><?php echo zget($users, $story2Link->openedBy);?></td>
        <td><?php echo $story2Link->estimate;?></td>
      </tr>
      <?php $storyCount ++;?>
      <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php if($storyCount) echo html::submitButton('', '', 'btn btn-default');?>
      </div>
      <?php echo html::hidden('story', $story->id);?>
    </div>
    <?php endif;?>
  </form>
</div>
<script>
$(function()
{
    <?php if($stories2Link):?>
    $('#linkStoryForm').table();
    setTimeout(function(){$('#linkStoryForm .table-footer').removeClass('fixed-footer');}, 100);
    <?php endif;?>

    $('#submit').click(function(){
        var output = '';
        $('#linkStoryForm').find('tr.checked').each(function(){
            var storyID   = $(this).find('td.c-id').find('div.checkbox-primary input').attr('value');
            var storyName = "#" + storyID + ' ' + $(this).find('td').eq(3).attr('title');
            var checkbox  = "<li><div class='checkbox-primary'><input type='checkbox' checked='checked' name='" + linkType + "[]' " + "value=" + storyID + " /><label>" + storyName + "</label></div></li>";

            output += checkbox;
        });
        $.closeModal();
        parent.$('#' + linkType + 'Box').html(output);
        return false;
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
