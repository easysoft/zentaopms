<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Yanyi Cao
 * @package     repo
 * @version     $Id: ajaxgetcommitrelation.html.php $
 */
?>
<?php
include '../../common/view/header.lite.html.php';
js::set('objectType', $objectType);
js::set('objectID', zget($object, 'id', ''));
?>
<div class="main-col linkContent main">
  <div class="content pane">
    <div class="fade main-row split-row in col-md-12">
      <div class="left-content col-md-8">
        <table class="table table-borderless">
          <?php if($objectType == 'story'):?>
          <tr>
            <td class="text">
              <strong class='text-primary'><?php echo zget($object, 'title', '');?></strong>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="spec-content detail-content article-content">
                <?php echo zget($object, 'spec', ''); ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="detail-content article-content">
                <?php echo zget($object, 'verify', ''); ?>
              </div>
            </td>
          </tr>
          <?php elseif($objectType == 'task'):?>
          <tr>
            <td class="text">
              <strong class='text-primary'><?php echo zget($object, 'name', '');?></strong>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="spec-content detail-content article-content">
                <?php echo zget($object, 'desc', ''); ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="text">
              <div class="detail-content article-content">
                <?php echo zget($object, 'storyTitle', ''); ?>
              </div>
            </td>
          </tr>
          <?php elseif($objectType == 'bug'):?>
          <tr>
            <td class="text">
              <strong class='text-primary' data-id='<?php echo zget($object, 'id', '');?>'><?php echo zget($object, 'title', '');?></strong>
            </td>
          </tr>
          <?php if(zget($object, 'steps', '')):?>
          <tr>
            <td class="text">
              <div class="spec-content detail-content article-content">
                <?php echo zget($object, 'steps', ''); ?>
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
