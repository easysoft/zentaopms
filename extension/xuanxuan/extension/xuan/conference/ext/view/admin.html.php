<?php
/**
 * The admin view file of conference module of XXB.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     conference
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
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
            <td></td>
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
          <a href="https://www.xuanim.com/book/xuanxuanserver/273.html" target="_blank"><?php echo $lang->conference->setupDoc;?></a>
          <br>
          <a href="https://www.xuanim.com/book/xxbservice/274.html" target="_blank"><?php echo $lang->conference->configDoc;?></a>
          <hr>
          <h4><?php echo $lang->conference->owtSetupTitle;?></h4>
          <a href="https://www.xuanim.com/book/xuanxuanserver/237.html" target="_blank"><?php echo $lang->conference->setupDoc;?></a>
          <br>
          <a href="https://www.xuanim.com/book/xxbservice/239.html" target="_blank"><?php echo $lang->conference->configDoc;?></a>
        </div>
      </form>
    </div>
    <div class="tab-pane fade <?php echo $type == 'video' ? 'active in' : '';?>" id="videoContent">
      <div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->conference->video?></strong>
        </div>
      </div>
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
<style>
.edit-row {display: none}
#conference-admin-form.conference-enabled .edit-row {display: table-row}
.srs-selected .owt-row {display: none!important}
.owt-selected .srs-row {display: none!important}
#videoContent, #serverContent{padding-top:0px;}
.tab-content .main-header {margin-bottom:20px;}
</style>
<script>
$(function()
{
    let owtPort = '3004';
    let srsPort = '1985';
    $('.side-col .nav a[data-toggle=tab]').click(function()
    {
        $(this).parent().find('a').removeClass('active');
        $(this).addClass('active');
    });
    $.setAjaxForm('#conference-admin-form');
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
        }
        if(e.target.value == 'srs')
        {
            $('#conference-admin-form').removeClass('owt-selected');
            $('#conference-admin-form').addClass('srs-selected');
            document.getElementById('https').checked = false;
            document.getElementById('apiPortTip').innerHTML = '<?php echo $lang->conference->apiPortSrsTip;?>';
            owtPort = document.getElementById('apiPort').value;
            document.getElementById('apiPort').value = srsPort;
        }
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
