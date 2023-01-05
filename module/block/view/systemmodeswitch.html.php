<style>
.block-guide .tab-pane .mode-switch {padding-bottom: 10px;}
.block-guide .tab-pane .mode-switch .mode-title {padding-top: 14px; padding-left: 20px;}
.block-guide .tab-pane .mode-switch .mode-content {display: flex;}
.block-guide .tab-pane .mode-switch .dataTitle {padding: 14px 20px;}
.block-guide .tab-pane .mode-switch .mode-block {background: rgba(230, 240, 255, 0.4); margin-left: 10px; cursor: pointer; padding-top: 8px;}
.block-guide .tab-pane .mode-switch .mode-block:nth-child(2) {margin-left: 8%;}
.block-guide .tab-pane .mode-switch .mode-block:hover {box-shadow: 0 0 14px rgba(0, 0, 0, 0.12);}
.block-guide .tab-pane .mode-switch .mode-block.active {box-shadow: 0 0 0 2px #2E7FFF; border-radius: 2px;}
.block-guide .tab-pane .mode-switch .mode-desc {padding: 4px 4px 10px; font-size: 12px; color: #5E626D;}
#selectProgramModal .modal-header {border-bottom: 0;}
#selectProgramModal .modal-header h4.modal-title {font-weight: 700;}
#selectProgramModal .modal-dialog {width: 550px;}
.modal-body {padding: 20px;}
#selectProgramModal .modal-footer {border-top: 0px; text-align: center;}
<?php if(common::checkNotCN()):?>
.block-guide .tab-pane .mode-switch .mode-block:nth-child(1) {padding-bottom: 18px;}
@media screen and (max-width: 988px) {.block-guide .tab-pane .mode-switch .mode-content .mode-block:nth-child(2) img {max-width: 74%;}}
<?php endif;?>
</style>
<?php $usedMode = zget($this->config->global, 'mode', 'light');?>
<?php js::set('usedMode', $usedMode);?>
<?php js::set('hasProgram', !empty($programs));?>
<?php js::set('changeModeTips', sprintf($lang->custom->changeModeTips, $lang->custom->modeList[$usedMode == 'light' ? 'ALM' : 'light']));?>
<div class='table-row mode-switch'>
  <div class="col-4">
    <p class="col mode-title"><?php echo $lang->block->customModeTip->common;?></p>
    <div class='col pull-left col-md-12 mode-content'>
      <?php foreach($lang->block->customModes as $mode => $modeName):?>
      <div class="pull-left col-md-5 mode-block<?php if($usedMode == $mode) echo ' active';?>" data-mode='<?php echo $mode;?>'>
        <div style="width: 100%;"><?php echo html::image($config->webRoot . "theme/default/images/guide/{$mode}_" . (common::checkNotCN() ? 'en' : 'cn') . ".png");?></div>
        <div class='mode-desc'>
          <h4><?php echo $modeName;?></h4>
          <?php echo $lang->block->customModeTip->$mode;?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>

<div class='modal fade' id='selectProgramModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>× </span><span class='sr-only'><?php echo $this->lang->close;?></span></button>
        <h4 class='modal-title'><?php echo $lang->custom->selectDefaultProgram;?></h4>
      </div>
      <div class='modal-body'>
        <div class='alert alert-primary'>
          <p class='text-info'><?php echo $lang->custom->selectProgramTips;?></p>
        </div>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->custom->defaultProgram;?></th>
            <td><?php echo html::select('defaultProgram', $programs, $programID, "class='form-control chosen'");?></td>
          </tr>
        </table>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-primary btn-wide btn-save'><?php echo $lang->save;?></button>
      </div>
    </div>
  </div>
</div>

<script>
$(function()
{
    var selectedMode = usedMode;

    /**
     * Switch system mode.
     *
     * @access public
     * @return void
     */
    function switchMode(mode)
    {
        if(mode == usedMode) return;

        var postData = {mode: mode};
        if(mode == 'light' && hasProgram) postData.program = $('#defaultProgram').val();
        $.post(createLink('custom', 'mode'), postData, function(result)
        {
            $('#selectProgramModal').modal('hide');
            parent.location.reload();
        });
    }

    var $block             = $('#block<?php echo $blockID;?>');
    var systemModePosition = "<?php echo 'systemModePosition-' . $blockID;?>";
    $block.on('click', '.mode-block', function()
    {
        selectedMode = $(this).data('mode');
        if(selectedMode == usedMode) return;

        var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop || $block.find('#guideBody').offset().top;
        localStorage.setItem(systemModePosition, scrollTop);

        if(selectedMode == 'light' && hasProgram)
        {
            $('#selectProgramModal').modal('show');
        }
        else
        {
            bootbox.confirm(changeModeTips, function(result)
            {
                if(result) switchMode(selectedMode);
            });
        }
    }).on('click', '#selectProgramModal .btn-save', function()
    {
        switchMode('light');
    });
});
</script>
