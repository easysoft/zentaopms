<?php
/**
 * The link story view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@cnezsoft.com>
 * @package     story
 * @version     $Id: linkstories.html.php 4129 2022-08-01 14:57:12Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $story->id;?></span>
      <?php echo $story->title;?>
      <?php $title = $story->type == 'story' ? $lang->story->linkStoriesAB : $lang->story->linkRequirementsAB?>
      <small class='text-muted'> <?php echo $lang->arrow . $title;?></small>
    </h2>
  </div>
  <div id='queryBox' data-module='story' class='show divider'></div>
  <?php if($stories2Link):?>
  <form class='main-table' method='post' data-ride='table' target='hiddenwin' id='linkStoriesForm'>
    <table class='table tablesorter' id='storyList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri' title=<?php echo $lang->pri;?>><?php echo $lang->priAB;?></th>
          <?php if(!empty($product->shadow)):?>
          <th class='c-product'><?php echo $lang->story->product;?></th>
          <?php endif;?>
          <th class='c-title'><?php echo $lang->story->title;?></th>
          <th class='c-status'><?php echo $lang->story->statusAB;?></th>
          <th class='c-user'><?php echo $lang->story->openedByAB;?></th>
          <th class='c-user'><?php echo $lang->story->assignedTo;?></th>
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
            <?php printf('%03d', $story2Link->id);?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $story2Link->pri?>' title='<?php echo zget($lang->story->priList, $story2Link->pri, $story2Link->pri);?>'><?php echo zget($lang->story->priList, $story2Link->pri, $story2Link->pri);?></span></td>
          <?php if(!empty($product->shadow)):?>
          <td class='nobr' title="<?php echo $products[$story2Link->product]?>"><?php echo html::a($this->createLink('product', 'browse', "productID=$story2Link->product&branch=$story2Link->branch"), $products[$story2Link->product], '_blank');?></td>
          <?php endif;?>
          <td class='text-left nobr' title="<?php echo $story2Link->title?>"><?php echo $story2Link->title;?></td>
          <td><?php echo $this->processStatus('story', $story2Link);?></td>
          <td><?php echo zget($users, $story2Link->openedBy);?></td>
          <td><?php echo zget($users, $story2Link->assignedTo);?></td>
        </tr>
        <?php $storyCount ++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php if($storyCount) echo html::submitButton('', '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('storyType', $story->type);?>
<script>
$(function()
{
    <?php if($stories2Link):?>
    $('#linkStoriesForm').table();
    setTimeout(function(){$('#linkStoriesForm .table-footer').removeClass('fixed-footer');}, 100);
    <?php endif;?>

    $('#submit').click(function()
    {
        var output = '';
        $('#linkStoriesForm').find('tr.checked').each(function(){
            var storyID        = $(this).find('td.c-id').find('div.checkbox-primary input').attr('value');
            var storyTitle     = "#" + storyID + ' ' + $(this).find('td').eq(2).attr('title');
            var linkStoryField = storyType == 'story' ? 'linkStories' : 'linkRequirements';
            var checkbox       = "<li><div class='checkbox-primary' title='" + $(this).find('td').eq(2).attr('title') + "'><input type='checkbox' checked='checked' name='" + linkStoryField + "[]' " + "value=" + storyID + " /><label class='linkStoryTitle'>" + storyTitle + "</label></div></li>";

            output += checkbox;
        });

        $.closeModal();
        parent.$('#linkStoriesBox').append(output);
        return false;
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
