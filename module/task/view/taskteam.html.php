<?php if(!empty($task->team)):?>
<?php foreach($task->team as $member):?>
<?php
$memberDisabled = false;
$sortDisabled   = false;
$memberStatus   = $member->status;
if($memberStatus == 'done') $memberDisabled = true;
if($memberStatus != 'wait' and $task->mode == 'linear') $sortDisabled = true;
if($task->mode == 'linear' and strpos('|closed|cancel|pause|', $task->status) !== false and $app->rawMethod != 'activate')
{
    $memberStatus   = $task->status;
    $memberDisabled = true;
    $sortDisabled   = true;
}
?>
<tr class='member-<?php echo $memberStatus;?>' data-estimate='<?php echo (float)$member->estimate?>' data-consumed='<?php echo (float)$member->consumed?>' data-left='<?php echo (float)$member->left?>'>
  <td class='w-250px'>
    <?php echo html::select("team[]", $members, $member->account, "class='form-control chosen'" . ($memberDisabled ? ' disabled' : ''))?>
    <?php echo html::hidden("teamSource[]", $member->account);?>
    <?php if($memberDisabled) echo html::hidden("team[]", $member->account);?>
  </td>
  <td>
    <div class='input-group'>
      <span class="input-group-addon <?php echo zget($requiredFields, 'estimate', '', ' required');?>"><?php echo $lang->task->estimate?></span>
      <?php echo html::input("teamEstimate[]", (float)$member->estimate, "class='form-control text-center' placeholder='{$lang->task->hour}'" . ($memberDisabled ? ' readonly' : ''))?>
      <span class='input-group-addon fix-border'><?php echo $lang->task->consumed?></span>
      <?php echo html::input("teamConsumed[]", (float)$member->consumed, "class='form-control text-center' readonly placeholder='{$lang->task->hour}'")?>
      <span class='input-group-addon fix-border'><?php echo $lang->task->left?></span>
      <?php echo html::input("teamLeft[]", (float)$member->left, "class='form-control text-center' placeholder='{$lang->task->hour}'" . ($memberDisabled ? ' readonly' : ''))?>
    </div>
  </td>
  <td class='w-130px sort-handler'>
    <button type="button" <?php echo $memberDisabled ? 'disabled' : '';?> class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
    <button type="button" <?php echo $sortDisabled   ? 'disabled' : '';?> class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
    <button type="button" <?php echo $memberDisabled ? 'disabled' : '';?> class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-close"></i></button>
  </td>
</tr>
<?php endforeach;?>
<?php endif;?>
<tr class='template teamTemplate member-wait'>
  <td class='w-250px'>
    <?php echo html::select("team[]", $members, '', "class='form-control chosen'")?>
    <?php echo html::hidden("teamSource[]", '');?>
  </td>
  <td>
    <?php if(empty($task->team)):?>
    <div class='input-group'>
      <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->estimateAB}'") ?>
      <span class='input-group-addon'><?php echo $lang->task->hour;?></span>
    </div>
    <?php else:?>
    <div class='input-group'>
      <span class="input-group-addon <?php echo zget($requiredFields, 'estimate', '', ' required');?>"><?php echo $lang->task->estimate?></span>
      <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
      <span class='input-group-addon fix-border'><?php echo $lang->task->consumed?></span>
      <?php echo html::input("teamConsumed[]", 0, "class='form-control text-center' readonly placeholder='{$lang->task->hour}'")?>
      <span class='input-group-addon fix-border'><?php echo $lang->task->left?></span>
      <?php echo html::input("teamLeft[]", '', "class='form-control text-center' placeholder='{$lang->task->hour}'")?>
    </div>
    <?php endif;?>
  </td>
  <td class='w-130px sort-handler'>
    <button type="button" class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
    <button type='button' class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
    <button type="button" class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-close"></i></button>
  </td>
</tr>
<?php $newRowCount = (!empty($task->team) and count($task->team) < 6) ? 6 - count($task->team) : 1;?>
<?php if(isset($task->status) and $task->status != 'wait' and $task->status != 'doing') $newRowCount = 0;?>
<?php js::set('newRowCount', $newRowCount);?>
<?php if(isset($task->mode) and $task->mode == 'linear') js::set('sortSelector', 'tr.member-wait');?>
<script>
$(document).ready(function()
{
    /* Init task team manage dialog */
    var $taskTeamEditor = $('tr.teamTemplate').closest('table').batchActionForm(
    {
        idStart: 0,
        idEnd: newRowCount - 1,
        chosen: true,
        datetimepicker: false,
        colorPicker: false,
    });
    var taskTeamEditor = $taskTeamEditor.data('zui.batchActionForm');

    var adjustButtons = function()
    {
        var $deleteBtn = $taskTeamEditor.find('.btn-delete');
        if ($deleteBtn.length == 1) $deleteBtn.addClass('disabled').attr('disabled', 'disabled');
    };

    var disableMembers = function()
    {
        var mode = $('#mode').length > 0 ? $('#mode').val() : '<?php echo (isset($task->mode) ? $task->mode : '')?>';
        if(mode == 'multi')
        {
            var members = [];
            var $teams  = $taskTeamEditor.find('select#team');
            for(i = 0; i < $teams.length; i++)
            {
                var value = $teams.eq(i).val();
                if(value != '') members.push(value);
            }

            $teams.each(function()
            {
                var $this = $(this);
                var value = $this.val();
                $this.find('option:disabled').removeAttr('disabled');
                $.each(members, function(i, account)
                {
                    if(account == value) return;
                    $this.find('option[value=' + account + ']').attr('disabled', 'disabled');
                })
                $this.trigger("chosen:updated");
            });
        }
    }

    $taskTeamEditor.on('click', '.btn-add', function()
    {
        var $newRow = taskTeamEditor.createRow(null, $(this).closest('tr'));
        $newRow.addClass('highlight');
        setTimeout(function()
        {
            $newRow.removeClass('highlight');
        }, 1600);
        disableMembers();
        adjustButtons();
    }).on('click', '.btn-delete', function()
    {
        var $row = $(this).closest('tr');
        $row.addClass('highlight').fadeOut(700, function()
        {
            $row.remove();
            disableMembers();
            adjustButtons();
        });
    });

    adjustButtons();
    disableMembers();

    $taskTeamEditor.on('change', 'select#team', function()
    {
        $(this).closest('tr').find('input[id^=teamEstimate]').closest('.input-group').toggleClass('required', $(this).val() != '')

        disableMembers();

        var $teamSource = $(this).siblings('[name^=teamSource]');
        if($teamSource.val() == '') return;

        var $tr      = $(this).closest('tr');
        var consumed = 0;
        var estimate = $tr.attr('data-left');;
        if($(this).val() == $teamSource.val())
        {
            consumed = $tr.attr('data-consumed');
            estimate = $tr.attr('data-estimate');
        }
        $tr.find('[name^=teamConsumed]').val(consumed);
        $tr.find('[name^=teamEstimate]').val(estimate);
    });
    $taskTeamEditor.find('select#team:enabled').each(function()
    {
        $(this).closest('tr').find('input[id^=teamEstimate]').closest('.input-group').toggleClass('required', $(this).val() != '')
    });
});
</script>
