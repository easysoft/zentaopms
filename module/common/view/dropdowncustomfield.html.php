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
#customFieldSetting {min-width:300px;}
#customFieldSetting .checkboxes {padding: 10px 3px;}
#customFieldSetting .checkbox-primary {width: 50%; float: left; margin: 3px 0;}
</style>
<div class="btn-group">
  <button type="button" class="btn btn-link" data-toggle="dropdown"><i class="icon icon-cog"></i></button>
  <div class="dropdown-menu pull-right" id='customFieldSetting'>
    <form class='with-padding load-indicator' method='post' target='hiddenwin' action='<?php echo $customLink?>'>
      <div><?php echo $lang->customConfig?></div>
      <div class='clearfix checkboxes'>
        <?php foreach($customFields as $field => $fieldName):?>
        <div class="checkbox-primary">
          <input type="checkbox" name="fields[]" id='<?php echo "field{$field}"?>' value="<?php echo $field?>" <?php if(strpos(",$showFields,", ",$field,") !== false) echo 'checked';?> />
          <label for='<?php echo "field{$field}"?>'><?php echo $fieldName;?></label>
        </div>
        <?php endforeach;?>
      </div>
      <div>
        <?php echo html::submitButton();?>
        &nbsp;
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
