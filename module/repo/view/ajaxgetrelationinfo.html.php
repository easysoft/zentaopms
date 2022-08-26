<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Yanyi Cao
 * @package     repo
 * @version     $Id: ajaxgetcommitrelation.html.php $
 */
?>
<?php
include '../../common/view/header.lite.html.php';
?>
<div class="main-col linkContent main">
  <div class="content pane">
    <div class="fade main-row split-row in">
      <div class="side-col left-content">
        <table class="menu-title">
          <?php if($objectType == 'story'):?>
          <tr><th class='text-right'><?php echo $lang->release->storyTitle;?>: </th><td class="text"><?php echo $object->title; ?></td></tr>
          <tr><th class='text-right'><?php echo $lang->story->legendSpec;?>: </th><td class="text"><div class="detail-content article-content"><?php echo $object->spec; ?></div></td></tr>
          <tr><th class='text-right'><?php echo $lang->story->verify;?>: </th><td class="text"><div class="detail-content article-content"><?php echo $object->verify; ?></div></td></tr>
          <?php elseif($objectType == 'task'):?>
          <tr><th class='text-right'><?php echo $lang->task->name;?>: </th><td class="text"><?php echo $object->name; ?></td></tr>
          <tr><th class='text-right'><?php echo $lang->task->desc;?>: </th><td class="text"><div class="detail-content article-content"><?php echo $object->desc; ?></div></td></tr>
          <tr><th class='text-right'><?php echo $lang->task->story;?>: </th><td class="text"><div class="detail-content article-content"><?php echo $object->storyTitle; ?></div></td></tr>
          <?php elseif($objectType == 'bug'):?>
          <tr><th class='text-right'><?php echo $lang->bug->title;?>: </th><td class="text"><?php echo $object->title; ?></td></tr>
          <tr><th class='text-right'><?php echo $lang->bug->type;?>: </th><td class="text"><?php echo zget($lang->bug->typeList, $object->type); ?></td></tr>
          <tr><th class='text-right'><?php echo $lang->bug->steps;?>: </th><td class="text"><div class="detail-content article-content"><?php echo $object->steps; ?></div></td></tr>
          <?php endif;?>
        </table>
      </div>
      <div class="main-col right-content">
        <?php include '../../common/view/action.html.php';?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
<script>
$(function()
{
    $('a').attr('href', 'javascript:;').removeClass('iframe').css('color', '#3c4353');
    $('blockquote a').empty();

    if($('.linkContent .content').width() != 0) $.cookie('eidtorLinkedContentWidth', $('.linkContent .content').width());
    $('.right-content').css('width', $.cookie('eidtorLinkedContentWidth') / 5 * 2);
});

if(window.onblur)
{
    $('.right-content').css('width', $.cookie('eidtorLinkedContentWidth') / 5 * 2);
}
</script>
