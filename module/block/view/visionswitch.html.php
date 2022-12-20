<style>
.vision-switch {padding: 14px;}
.vision-switch-container {display: flex;}
#vision-ALM {background: url('/theme/default/images/guide/vision_alm.png') no-repeat;}
#vision-light {background: url('/theme/default/images/guide/vision_light.png') no-repeat;}
.vision {width: 47%; border: none; background: #E6F0FF; cursor:pointer; border-radius: 2px;}
.vision + .vision {margin-left: 10px;}
.vision.active {box-shadow: 0 0 0 2px #2E7FFF;}
.vision-img {height: 118px; width: 100%; background-size: 100% !important;}
.vision-title {font-size: 14px; color: #0B0F18; padding: 0 8px;}
.vision-text {font-size: 12px; color: #5E626D; padding: 8px;}
#selectProgramModal .modal-dialog {width: 550px}
#selectProgramModal .modal-header {border-bottom: 0px}
#selectProgramModal .modal-header h4.modal-title{font-weight: 700}
#selectProgramModal .modal-footer {border-top: 0px; text-align: center}
.btn-wide {padding: 6px 85px;}
</style>
<?php js::set('changeModeTips', sprintf($lang->custom->changeModeTips, $lang->custom->modeList[$this->config->systemMode == 'light' ? 'ALM' : 'light']));?>
<?php js::set('hasProgram', !empty($programs));?>
<?php js::set('systemMode', $this->config->systemMode);?>

<form id='modeForm' class="load-indicator main-form form-ajax"  method='post'>
  <div class='vision-switch'>
  <p><?php echo $lang->block->visionTitle;?></p>
  <div class="vision-switch-container">
    <?php foreach($lang->block->visions as $vision):?>
    <?php $active = $this->config->systemMode == $vision->key ? 'active' : '';?>
    <div class='vision <?php echo $active;?>' data-value="<?php echo $vision->key;?>">
        <div class='vision-img' id="<?php echo 'vision-' . $vision->key;?>"></div>
        <div class='vision-title'><?php echo $vision->title;?></div>
        <div class='vision-text'><?php echo $vision->text;?></div>
      </div>
    <?php endforeach;?>
    </div>
  </div>
  <div class='modal fade' id='selectProgramModal'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span><span class='sr-only'><?php echo $this->lang->close;?></span></button>
          <h4 class='modal-title'><?php echo $lang->custom->selectDefaultProgram;?></h4>
        </div>
        <div class='modal-body'>
          <div class='alert alert-primary'>
            <p class='text-info'><?php echo $lang->custom->selectProgramTips;?></p>
          </div>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->custom->defaultProgram;?></th>
              <td><?php echo html::select('program', $programs, $programID, "class='form-control chosen'");?></td>
            </tr>
          </table>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-primary btn-wide btn-save'><?php echo $lang->save;?></button>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
$(function()
{
    $('.vision-switch .vision').click(function()
    {
        var selectedMode = $(this).data('value');
        if (systemMode == selectedMode) return false;
        $('#mode').val(selectedMode);

        if(selectedMode == 'light' && hasProgram)
        {
            $('#selectProgramModal').modal('show');
        }
        else
        {
            bootbox.confirm(changeModeTips, function(result)
            {
                if(result) $('#modeForm').submit();
            });
        }

        return false;
    })

    $(document).on('click', '.btn-save', function()
    {
        setTimeout(function()
        {
            $('#selectProgramModal').modal('hide');
            $('#modeForm').submit();
        }, 1000);
    });
})
</script>
