<?php
/**
 * The custom field view file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
#customModal .checkbox-inline{width:90px}
#customModal .checkbox-inline+.checkbox-inline{margin-left:0px;}
</style>
<div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-800px">
    <div class="modal-content">
      <form class='form-condensed' method='post' target='hiddenwin' action='<?php echo $customLink?>'>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">
            <i class="icon-cog"></i> <?php echo $lang->customConfig?>
            <div class='pull-right' style='margin-right:15px;'><?php echo html::submitButton()?></div>
          </h4>
        </div>
        <div class="modal-body">
          <p><?php echo html::checkbox('fields', $customFields, $showFields);?></p>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
$("button[data-toggle='customModal']").click(function(){$('#customModal').modal('show')});
$(function()
{
    $table = $('.outer form table:first');
    $form = $table.closest('form');
    if($table.width() > $form.width())$form.css('overflow-x', 'auto')
})
</script>
