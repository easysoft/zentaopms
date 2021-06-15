<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->gitlab->bindUser;?></h2>
  </div>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr>
            <th class='col-id'><?php echo $lang->gitlab->gitlabAccount;?></th>
            <th class='col-id'><?php echo $lang->gitlab->zentaoAccount;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($gitlabUsers as $gitlabUser):?>
          <?php 
          ?>
          <tr class="template">
            <td>$idPlus</td>
            <td class='text-left<?php echo zget($visibleFields, $product->type, ' hidden')?>'><?php echo html::select('branch[$id]', $branches, $branch, "class='form-control chosen' onchange='setModuleAndPlan(this.value, $productID, \$id)'");?></td>
         </tr>
         <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 3?>" class="text-center form-actions">
              <?php echo html::submitButton($lang->save);?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<script>
$(function()
{
    var imageTitles = <?php echo empty($titles) ? '""' : json_encode($titles);?>;
    var storyTitles = <?php echo empty($titles) ? '""' : json_encode(array_keys($titles));?>;

    $('#batchCreateForm').batchActionForm(
    {
        idEnd: <?php echo max((empty($titles) ? 0 : count($titles)), 9)?>,
        rowCreator: function($row, index)
        {
            $row.find('select.chosen,select.picker-select').each(function()
            {
                var $select = $(this);
                if($select.hasClass('picker-select')) $select.parent().find('.picker').remove();
                if(index == 0) $select.find("option[value='ditto']").remove();
                if(index > 0) $select.val('ditto');
                if($select.attr('id').indexOf('branch') >= 0) $select.val('<?php echo $branch;?>')
                $select.chosen();
                setTimeout(function()
                {
                    $select.next('.chosen-container').find('.chosen-drop').width($select.closest('td').width() + 50);
                }, 200);
              });
              var storyTitle = storyTitles && storyTitles[index];
              if (storyTitle !== undefined && storyTitle !== null)
              {
                  $row.find('.input-story-title').val(storyTitle).after('<input type="hidden" name="uploadImage[' + index + ']" id="uploadImage[' + index + ']" value="' + imageTitles[storyTitle] + '">');
              }
        }
    });

    $(document).on('change', "#mainContent select[name^=needReview]", function()
    {
        select = $(this).parent('td').next('td').children("select[name^=reviewer]");
        $(select).removeAttr('disabled');
        if($(this).val() == 0) $(select).attr('disabled', 'disabled');
        $(select).trigger("chosen:updated");
    })
});
</script>
<?php if(isset($execution)) js::set('execution', $execution);?>
<?php js::set('storyType', $type);?>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
