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
<div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-800px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-cog"></i> <?php echo $lang->customConfig?></h4>
      </div>
      <div class="modal-body">
        <form method='post' target='hiddenwin' action='<?php echo $customLink?>'>
          <p><?php echo html::checkbox('fields', $customFields, $hiddenFields);?></p>
          <p><?php echo html::submitButton() . ' ' . $lang->checked2Hide?></p>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$("button[data-toggle='customModal']").click(function(){$('#customModal').modal('show')});
</script>
