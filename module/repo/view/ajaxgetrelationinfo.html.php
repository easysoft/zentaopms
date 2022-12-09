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
js::set('objectType', $objectType);
js::set('objectID', $object->id);
?>
<div class="main-col linkContent main">
  <div class="content pane">
    <div class="fade main-row split-row in col-md-12">
      <div class="left-content col-md-8">
        <table class="table table-borderless">
          <?php if($objectType == 'story'):?>
          <tr>
            <td class="text">
              <strong class='text-primary'><?php echo $object->title;?></strong>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="spec-content detail-content article-content">
                <?php echo $object->spec; ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="detail-content article-content">
                <?php echo $object->verify; ?>
              </div>
            </td>
          </tr>
          <?php elseif($objectType == 'task'):?>
          <tr>
            <td class="text">
              <strong class='text-primary'><?php echo $object->name;?></strong>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="spec-content detail-content article-content">
                <?php echo $object->desc; ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="detail-content article-content">
                <?php echo $object->storyTitle; ?>
              </div>
            </td>
          </tr>
          <?php elseif($objectType == 'bug'):?>
          <tr>
            <td class="text">
              <strong class='text-primary' data-id='<?php echo $object->id;?>'><?php echo $object->title;?></strong>
            </td>
          </tr>
          <?php if($object->steps):?>
          <tr>
            <td class="text">
              <div class="spec-content detail-content article-content">
                <?php echo $object->steps; ?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <?php endif;?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
<script>
$(function()
{
    $('a').each(function()
    {
        var content = $(this).html();
        $(this).replaceWith(content);
    })
    $('blockquote a').empty();

    $('.linkContent strong.text-primary').on('click',function()
    {
        var link = createLink(objectType, 'view', objectType + 'ID=' + objectID);
        var app = objectType == 'bug' ? 'qa' : (objectType == 'task' ? 'execution' : 'product');
        parent.parent.$.apps.open(link, app);
    });
});
</script>
