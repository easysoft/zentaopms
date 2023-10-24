<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<script>
function setDownloading()
{
  if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.

  $.cookie('downloading', 0);
  time = setInterval("closeWindow()", 300);
  return true;
}

function closeWindow()
{
  if($.cookie('downloading') == 1)
  {
    parent.$.closeModal();
    $.cookie('downloading', null);
    clearInterval(time);
  }
}
</script>
<style>
  .xmind-title  { font-size:14px; font-weight:700; margin-bottom:10px;}
  .group-label  { line-height:25px; margin-left:10px;}
  .product-name { max-width: 440px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
</style>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->testcase->xmindExport;?></h2>
  </div>
  <form method='post' target='hiddenwin' onsubmit='setDownloading();' style='padding: 0px 5% 20px;'>
    <input name="download" type="hidden" value="download"/>
    <table class='w-p100 table table-form'>
      <tr>
        <td>
          <span style="display: inline-block; margin-bottom: 5px;" class='xmind-title'>
            <?php echo $lang->testcase->xmindExportSetting;?>
          </span>
          <div style="margin-bottom: 2px;" class="row">
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><?php echo $lang->testcase->settingModule;?></span>
                <?php echo html::input('module', $settings['module'], "class='form-control' placeholder='M'");?>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><?php echo $lang->testcase->settingScene;?></span>
                <?php echo html::input('scene', $settings['scene'], "class='form-control' placeholder='S'");?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><?php echo $lang->testcase->settingCase;?></span>
                <?php echo html::input('case', $settings['case'], "class='form-control' placeholder='C'");?>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><?php echo $lang->testcase->settingPri;?></span>
                <?php echo html::input('pri', $settings['pri'], "class='form-control' placeholder='P'");?>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon"><?php echo $lang->testcase->settingGroup;?></span>
                <?php echo html::input('group', $settings['group'], "class='form-control' placeholder='G'");?>
              </div>
            </div>
          </div>
        </td>
        <td class="w-150px"/>
      </tr>
      <tr>
        <td>
          <div class="row">
            <div class="col-sm-12">
              <div class="input-group">
                <span class="input-group-addon"><?php echo $lang->testcase->product;?></span>
                <span class="form-control product-name" title='<?php echo $productName; ?>' ><?php echo $productName;?></span>
              </div>
            </div>
          </div>
        </td>
        <td>
        </td>
      </tr>
      <tr>
        <td>
          <div class="row">
            <div class="col-sm-12">
              <div class="input-group">
                <span class="input-group-addon"><?php echo $lang->testcase->module;?></span>
                <?php echo html::select('imodule', $moduleOptionMenu, $moduleID, "class='form-control chosen'"); ?>
              </div>
            </div>
          </div>
        </td>
        <td>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
