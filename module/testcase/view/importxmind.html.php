<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<style>
    .xmind-title {
        font-size:14px;
        font-weight:700;
        margin-bottom:10px;
    }
</style>
<main id="main">
  <div class="container">
    <div id="mainContent" class='main-content'>
      <div class='main-header'>
        <h2><?php echo $lang->testcase->xmindImport;?></h2>
      </div>
      <form method='post' enctype='multipart/form-data' target='hiddenwin' style="padding: 0px 3% 20px;">
      <table class='table table-form w-p100'>
        <tr>
          <td>
            <span style="display: inline-block; margin-bottom: 5px;" class='xmind-title'>
                <?php echo $lang->testcase->xmindImportSetting;?>
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
          <td class="w-150px"></td>
        </tr>
        <tr>
          <td align='center'>
            <input type='file' name='file' class='form-control'/>
          </td>
          <td>
            <?php echo html::submitButton('', '', 'btn btn-primary btn-block');?>
          </td>
        </tr>
      </table>
      </form>
    </div>
  </div>
</main>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
