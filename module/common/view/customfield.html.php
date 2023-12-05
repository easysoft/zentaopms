<?php
/**
 * The custom field view file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
  [lang^='de'] #formSetting {min-width: 360px;}
  [lang^='fr'] #formSetting {min-width: 320px;}
  </style>
  <button type="button" title="<?php echo $lang->customField;?>" class="btn btn-link" id="customField" data-toggle="dropdown"><i class="icon icon-cog"></i></button>
  <div class="dropdown-menu pull-right" id="formSetting">
    <form class='with-padding load-indicator not-watch' id='formSettingForm' method='post' target='hiddenwin' action='<?php echo $customLink?>'>
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
      if(typeof showFields != 'undefined')
      {
          var fieldList = ',' + showFields + ',';
          $('#formSettingForm > .checkboxes > .checkbox-primary > input:visible').each(function()
          {
              var field = ',' + $(this).val() + ',';
              $(this).prop('checked', fieldList.indexOf(field) >= 0);
          });
      }

      $formSetting.parent().removeClass('open');
  }).on('click', function(e){e.stopPropagation()});
  </script>
</div>
