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
#customFieldSetting {width:160px;}
</style>
<div class="btn-group">
  <button type="button" class="btn btn-link" data-toggle="dropdown"><i class="icon icon-cog"></i></button>
  <div class="dropdown-menu pull-right" id='customFieldSetting'>
    <form class='with-padding load-indicator' method='post' target='hiddenwin' action='<?php echo $customLink?>'>
      <div><?php echo $lang->customConfig?></div>
      <?php foreach($customFields as $field => $fieldName):?>
      <div class="checkbox"><label><input type="checkbox" name="fields[]" value="<?php echo $field?>" <?php if(strpos(",$showFields,", ",$field,") !== false) echo 'checked';?> /> <?php echo $fieldName;?></label></div>
      <?php endforeach;?>
      <div>
        <?php echo html::submitButton();?>
        <?php echo html::commonButton($lang->cancel, '', "btn btn-gray close-dropdown");?>
      </div>
    </form>
  </div>
</div>
<script>
$('#customFieldSetting').on('click', '.close-dropdown', function()
{
    $('#customFieldSetting').parent().removeClass('open');
}).on('click', function(e){e.stopPropagation()});
</script>
