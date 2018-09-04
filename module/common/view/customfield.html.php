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
<div class="dropdown">
  <style>
  #formSetting {min-width: 300px;}
  #formSettingForm .checkboxes {padding: 10px 3px;}
  #formSettingForm .checkbox-primary {width: 50%; float: left; margin: 3px 0;}
  #formSetting .btn {margin-right: 8px;}
  </style>
  <button type="button" title="<?php echo $lang->customField;?>" class="btn btn-link" data-toggle="dropdown"><i class="icon icon-cog"></i></button>
  <div class="dropdown-menu pull-right" id="formSetting">
    <form class='with-padding load-indicator' id='formSettingForm' method='post' target='hiddenwin' action='<?php echo $customLink?>'>
      <div><?php echo $lang->customField;?></div>
      <div class="clearfix checkboxes">
        <?php echo html::checkbox('fields', $customFields, $showFields);?>
      </div>
      <div>
        <button type="submit" class="btn btn-primary" data-loading="<?php echo $lang->submitting;?>"><?php echo $lang->save;?></button>
        <?php echo html::commonButton($lang->cancel, '', "btn close-dropdown");?>
        <?php echo html::a($customLink, $lang->restore, 'hiddenwin', "class='btn'");?>
      </div>
    </form>
  </div>
  <script>
  var $formSetting = $('#formSetting');
  $formSetting.on('click', '.close-dropdown', function()
  {
      $formSetting.parent().removeClass('open');
  }).on('click', function(e){e.stopPropagation()});
  </script>
</div>
