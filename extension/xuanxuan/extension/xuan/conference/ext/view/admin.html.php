<?php
/**
 * The admin view file of conference module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     conference
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('hasDetached', extCommonModel::ilMethod('conference', 'detachedConference'))?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class="nav list-group tab-menu">
        <?php foreach($this->config->conference->owtTabList as $tabIndex):?>
        <?php $active = ($type == $tabIndex || ($type == 'edit' && $tabIndex == 'server')) ? 'active' : '';?>
        <a href="<?php echo '#' . $tabIndex . 'Content'?>" data-toggle="tab" class='<?php echo $active?>'><?php echo $lang->conference->$tabIndex;?></a>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class="tab-content">
    <div class="tab-pane fade <?php echo $type == 'server' || $type == 'edit' ? 'active in' : '';?>" id="serverContent">
      <div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->conference->server?></strong>
        </div>
      </div>
      <form method='post' id='conference-admin-form' class='form-ajax<?php if($enabled) echo ' conference-enabled';?><?php if(!empty($backendType)) echo " $backendType-selected";?>'>
        <table class='table table-form'>
          <tr>
            <th class="w-150px"><?php echo $lang->conference->enabled;?></th>
            <td class="w-400px">
              <?php if($type != 'edit'): ?>
              <div class="checkbox-primary disabled <?php if($enabled) echo 'checked';?>">
                <label><?php echo $lang->conference->enabledTip;?></label>
              </div>
              <?php else: ?>
              <div class="checkbox-primary">
                <input type="checkbox" name="enabled" id='enabled' value="true" <?php if($enabled) echo 'checked';?> <?php if($type != 'edit') echo 'disabled';?>>
                <label for='enabled'><?php echo $lang->conference->enabledTip;?></label>
              </div>
              <?php endif; ?>
            </td>
            <td></td>
          </tr>
          <?php if($type == 'edit' || $enabled): ?>
          <tr class='edit-row common-row'>
            <th class="w-120px"><?php echo $lang->conference->backend->type;?></th>
            <td class="w-400px code">
              <?php if($type == 'edit'):?>
                <div class='required required-wrapper'></div>
                <?php echo html::radio('backendType', $lang->conference->backend->types, $backendType);?>
              <?php else: echo html::radio('backendType', $lang->conference->backend->types, $backendType, 'disabled'); endif; ?>
            </td>
            <td></td>
          </tr>
          <tr class='edit-row common-row'>
            <th class="w-120px"><?php echo $lang->conference->detachedConference;?></th>
            <td class="w-400px">
              <?php if($type != 'edit'): ?>
                <div class="checkbox-primary disabled <?php if($detachedConference && extCommonModel::ilMethod('conference', 'detachedConference')) echo 'checked';?>">
                  <label id="detachedConferenceTip"><?php echo $lang->conference->detachedConferenceTip;?></label>
                </div>
              <?php else: ?>
                <div class="checkbox-primary">
                  <input type='hidden' value='0' name='detachedConference'>
                  <input type="checkbox" name="detachedConference" id='detachedConference' value="true" <?php if($detachedConference && extCommonModel::ilMethod('conference', 'detachedConference')) echo 'checked';?> <?php if($type != 'edit' || !extCommonModel::ilMethod('conference', 'detachedConference') || $backendType == 'owt') echo 'disabled';?>>
                  <label id="detachedConferenceTip" for='detachedConference'><?php echo $lang->conference->detachedConferenceTip;?></label>
                </div>
              <?php endif; ?>
            </td>
          </tr>
          <tr class='edit-row common-row'>
            <th class="w-120px"><?php echo $lang->conference->serverAddr;?></th>
            <td class="w-400px code">
              <?php if($type == 'edit'): ?>
                <div class='required required-wrapper'></div>
                <?php echo html::input('serverAddr', $serverAddr, "class='form-control'");?>
              <?php else: echo empty($serverAddr) ? $lang->conference->notset : $serverAddr; endif; ?>
            </td>
            <td><?php echo $lang->conference->serverAddrTip;?></td>
          </tr>
          <tr class='edit-row srs-row'>
            <th class="w-120px"><?php echo $lang->conference->https;?></th>
            <td class="w-400px">
              <?php if($type != 'edit'): ?>
              <div class="checkbox-primary disabled <?php if($https) echo 'checked';?>">
                <label id="httpsTip"><?php echo $lang->conference->httpsTip;?></label>
              </div>
              <?php else: ?>
              <div class="checkbox-primary">
                <input type="checkbox" name="https" id='https' value="true" <?php if($https) echo 'checked';?> <?php if($type != 'edit') echo 'disabled';?>>
                <label id="httpsTip" for='https'><?php echo $lang->conference->httpsTip;?></label>
              </div>
              <?php endif; ?>
            </td>
          </tr>
          <tr class='edit-row common-row'>
            <th class="w-120px"><?php echo $lang->conference->apiPort;?></th>
            <td class="w-400px code">
              <?php if($type == 'edit'):?>
                <div class='required required-wrapper'></div>
                <input type="number" name="apiPort" id="apiPort" <?php echo empty($apiPort) ? "value='3004'" : "value='$apiPort'";?> min="1" max="65535" class="form-control">
              <?php else: echo empty($apiPort) ? $lang->conference->notset : $apiPort; endif; ?>
            </td>
            <td id="apiPortTip"><?php echo $type == 'edit' ? ($backendType == 'srs' ? $lang->conference->apiPortSrsTip : $lang->conference->apiPortOwtTip) : '';?></td>
          </tr>
          <tr class='edit-row owt-row'>
            <th class="w-120px"><?php echo $lang->conference->mgmtPort;?></th>
            <td class="w-400px code">
              <?php if($type == 'edit'): ?>
                <div class='required required-wrapper'></div>
                <input type="number" name="mgmtPort" id="mgmtPort" <?php echo empty($mgmtPort) ? '' : "value='$mgmtPort'";?> min="1" max="65535" class="form-control">
              <?php else: echo empty($mgmtPort) ? $lang->conference->notset : $mgmtPort; endif; ?>
            </td>
            <td><?php echo $type == 'edit' ? $lang->conference->mgmtPortTip : '';?></td>
          </tr>
          <tr class='edit-row srs-row'>
            <th class="w-120px"><?php echo $lang->conference->rtcPort;?></th>
            <td class="w-400px code">
              <?php if($type == 'edit'): ?>
                <div class='required required-wrapper'></div>
                <input type="number" name="rtcPort" id="rtcPort" <?php echo empty($rtcPort) ? '' : "value='$rtcPort'";?> min="1" max="65535" class="form-control">
              <?php else: echo empty($rtcPort) ? $lang->conference->notset : $rtcPort; endif; ?>
            </td>
            <td><?php echo $type == 'edit' ? $lang->conference->rtcPortTip : '';?></td>
          </tr>
          <tr class='edit-row owt-row'>
            <th class="w-120px"><?php echo $lang->conference->serviceId;?></th>
            <td class="w-400px code">
              <?php if($type == 'edit'): ?>
                <div class='required required-wrapper'></div>
                <?php echo html::input('serviceId', $serviceId, "class='form-control'");?>
              <?php else: echo empty($serviceId) ? $lang->conference->notset : $serviceId; endif; ?>
            </td>
            <td><?php echo $type == 'edit' ? $lang->conference->serviceIdTip : '';?></td>
          </tr>
          <tr class='edit-row owt-row'>
            <th class="w-120px vtop"><?php echo $lang->conference->serviceKey;?></th>
            <td class="w-400px code wrapper">
              <?php if($type == 'edit'): ?>
                <div class='required required-wrapper'></div>
                <?php echo html::textarea('serviceKey', $serviceKey, "class='form-control' style='height:100px;'");?>
              <?php else: echo empty($serviceKey) ? $lang->conference->notset : $serviceKey; endif; ?>
            </td>
            <td><?php echo $type == 'edit' ? $lang->conference->serviceKeyTip : '';?></td>
          </tr>
          <?php endif; ?>
          <tr>
            <th></th>
            <td colspan='2'>
              <?php if($type == 'edit') echo html::submitButton();?>
              <?php if($type != 'edit') echo '<a class="btn btn-primary" href="' . helper::createLink('conference', 'admin', 'type=edit') . '">' . $lang->edit;?>
            </td>
          </tr>
        </table>
        <div id="setupTip">
          <h3><?php echo $lang->conference->setupTitle;?></h3>
          <p><?php echo $lang->conference->setupDescription;?></p>
          <a href="https://www.xuanim.com/page/download.html" target="_blank"><?php echo $lang->conference->download;?></a>
          <hr>
          <h4><?php echo $lang->conference->srsSetupTitle;?></h4>
          <a href="https://www.zentao.net/book/zentaopms/1158.html" target="_blank"><?php echo $lang->conference->setupDoc;?></a>
          <br>
          <a href="https://www.zentao.net/book/zentaopms/1159.html" target="_blank"><?php echo $lang->conference->configDoc;?></a>
          <hr>
          <h4><?php echo $lang->conference->owtSetupTitle;?></h4>
          <a href="https://www.xuanim.com/book/xuanxuanserver/237.html" target="_blank"><?php echo $lang->conference->setupDoc;?></a>
          <br>
          <a href="https://www.xuanim.com/book/xxbservice/239.html" target="_blank"><?php echo $lang->conference->configDoc;?></a>
        </div>
      </form>
    </div>
    <div class="tab-pane fade <?php echo $type == 'video' ? 'active in' : '';?>" id="videoContent">
      <form method='post' id='ajaxForm' class='form-ajax' action=<?php echo $this->createLink("conference", 'admin', 'type=video');?>>
        <table class='table table-form'>
          <tr>
            <th class="w-120px"><?php echo $lang->conference->resolutionWidth;?></th>
            <td class="code w-100px">
              <input type="number" name="resolutionWidth" id="resolutionWidth" <?php echo "placeholder='{$lang->conference->placeholder->resolutionWidth}'" ;echo empty($resolutionWidth) ? '' : "value='$resolutionWidth'";?> min="320" max="1280" class="form-control">
            </td>
            <td><?php echo $lang->conference->resolutionWidthTip;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->conference->resolutionHeight;?></th>
            <td class="code w-100px">
              <input type="number" name="resolutionHeight" id="resolutionHeight" <?php echo "placeholder='{$lang->conference->placeholder->resolutionHeight}'" ;echo empty($resolutionHeight) ? '' : "value='$resolutionHeight'";?> min="240" max="720" class="form-control">
            <td><?php echo $lang->conference->resolutionHeightTip;?></td>
          </tr>
          <tr><th></th><td></td></tr>
          <tr>
            <th></th>
            <td colspan='2'>
              <?php echo html::submitButton();?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="detachedConferenceCheckModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-header">
      <strong>
        <?php echo $lang->conference->detachedConference;?>
      </strong>
    </div>
    <div class="modal-body">
      <?php echo $lang->conference->detachedConferencewarning;?>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang->cancel;?></button>
      <button type="button" class="btn btn-primary" id="detachedConferenceConfirmButton"><?php echo $lang->confirm;?></button>
    </div>
  </div>
</div>
<style>
.tab-content .tab-pane {padding: 0;}
.tab-content .tab-pane form {padding: 20px 0;}
.edit-row {display: none}
#conference-admin-form.conference-enabled .edit-row {display: table-row}
.srs-selected .owt-row {display: none!important}
.owt-selected .srs-row {display: none!important}
</style>
<script>
$(function()
{
    var owtPort = '3004';
    var srsPort = '1985';

    $('#enabled').on('change', function()
    {
        $('#conference-admin-form').toggleClass('conference-enabled', $('#enabled').is(':checked'));
    });
    $('input[type="radio"][name="backendType"]').on('change', function(e)
    {
        if(e.target.value == 'owt')
        {
            $('#conference-admin-form').removeClass('srs-selected');
            $('#conference-admin-form').addClass('owt-selected');
            document.getElementById('https').checked = true;
            document.getElementById('apiPortTip').innerHTML = '<?php echo $lang->conference->apiPortOwtTip;?>';
            srsPort = document.getElementById('apiPort').value;
            document.getElementById('apiPort').value = owtPort;
            document.getElementById('detachedConference').toggleAttribute('disabled', true);
        }
        if(e.target.value == 'srs')
        {
            $('#conference-admin-form').removeClass('owt-selected');
            $('#conference-admin-form').addClass('srs-selected');
            document.getElementById('https').checked = false;
            document.getElementById('apiPortTip').innerHTML = '<?php echo $lang->conference->apiPortSrsTip;?>';
            owtPort = document.getElementById('apiPort').value;
            document.getElementById('apiPort').value = srsPort;
            if(hasDetached)
            {
              document.getElementById('detachedConference').toggleAttribute('disabled', false);
            }
        }
    });
    $("#submit").click(function(event)
    {
      event.preventDefault();
      var conferenceAdminFormData = new FormData(document.getElementById('conference-admin-form'));
      var isDetachedConferenceEnable = conferenceAdminFormData.getAll('detachedConference').reduce((previousValue, currentValue) => previousValue || (currentValue === 'true'), false);
      if(isDetachedConferenceEnable)
      {
        $('#detachedConferenceCheckModal').modal('show', 'fit')
      }
      else
      {
        $.setAjaxForm('#conference-admin-form');
        document.getElementById("conference-admin-form").requestSubmit();
      }
    });
    $('#detachedConferenceConfirmButton').on('click', function()
    {
      $('#detachedConferenceCheckModal').modal('hide');
      $("#conference-admin-form").off( "submit");
      $.setAjaxForm('#conference-admin-form');
      document.getElementById("conference-admin-form").requestSubmit();
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
