<style>
#taskTeamEditor button > i {color: #5e626d;}
#taskTeamEditor .estimateBox span {background-color: #fff;}
#taskTeamEditor .estimateBox input {background-color: #fff; border-right-width: 0px;}
#taskTeamEditor input, #taskTeamEditor span, #taskTeamEditor .chosen-container > a {border-color: #eee;}
</style>
<?php $i = 1;?>
<?php if(!empty($task->team)):?>
<?php foreach($task->team as $member):?>
<?php
$memberDisabled = false;
$sortDisabled   = false;
$memberStatus   = $member->status;
if($memberStatus == 'done') $memberDisabled = true;
if($memberStatus != 'wait' and $task->mode == 'linear') $sortDisabled = true;
if($memberStatus == 'done' and $task->mode == 'multi')  $sortDisabled = true;
if($task->mode == 'linear' and strpos('|closed|cancel|pause|', $task->status) !== false and $app->rawMethod != 'activate')
{
    $memberStatus   = $task->status;
    $memberDisabled = true;
    $sortDisabled   = true;
}

$hourDisabled = $memberDisabled;
if($task->mode == 'multi' and $app->rawMethod == 'activate') $hourDisabled = false;
?>
<tr class='member-<?php echo $memberStatus;?>' data-estimate='<?php echo (float)$member->estimate?>' data-consumed='<?php echo (float)$member->consumed?>' data-left='<?php echo (float)$member->left?>'>
  <td>
    <span><?php echo $i;?></span>
  </td>
  <td class='w-250px'>
    <?php echo html::select("team[]", $members, $member->account, "class='form-control chosen'" . ($memberDisabled ? ' disabled' : ''))?>
    <?php echo html::hidden("teamSource[]", $member->account);?>
    <?php if($memberDisabled) echo html::hidden("team[]", $member->account);?>
  </td>
  <td>
    <div class='input-group'>
      <span class="input-group-addon <?php echo zget($requiredFields, 'estimate', '', ' required');?>"><?php echo $lang->task->estimate?></span>
      <?php echo html::input("teamEstimate[]", (float)$member->estimate, "class='form-control text-center' placeholder='{$lang->task->hour}'" . ($hourDisabled ? ' readonly' : ''))?>
      <span class='input-group-addon fix-border'><?php echo $lang->task->consumed?></span>
      <?php echo html::input("teamConsumed[]", (float)$member->consumed, "class='form-control text-center' readonly placeholder='{$lang->task->hour}'")?>
      <span class='input-group-addon fix-border'><?php echo $lang->task->left?></span>
      <?php echo html::input("teamLeft[]", (float)$member->left, "class='form-control text-center' placeholder='{$lang->task->hour}'" . ($hourDisabled ? ' readonly' : ''))?>
    </div>
  </td>
  <td class='w-130px sort-handler'>
    <button type="button" <?php echo $memberDisabled ? 'disabled' : '';?> class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
    <button type="button" <?php echo $memberDisabled ? 'disabled' : '';?> class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-trash"></i></button>
    <?php if(isset($task->mode) and $task->mode == 'linear'):?>
    <button type="button" <?php echo $sortDisabled   ? 'disabled' : '';?> class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
    <?php endif;?>
  </td>
</tr>
<?php $i ++;?>
<?php endforeach;?>
<?php endif;?>
<tr class='template teamTemplate member-wait'>
  <td>
    <span><?php echo $i;?></span>
  </td>
  <td class='w-250px'>
    <?php echo html::select("team[]", $members, '', "class='form-control chosen'")?>
    <?php echo html::hidden("teamSource[]", '');?>
  </td>
  <td>
    <?php if(empty($task->team)):?>
    <div class='input-group estimateBox'>
      <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->estimateAB}'") ?>
      <span class='input-group-addon'><?php echo 'h';?></span>
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
    <button type="button" class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-trash"></i></button>
    <?php if(empty($task->mode) or $task->mode == 'linear'):?>
    <button type='button' class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
    <?php endif;?>
  </td>
</tr>
<?php $newRowCount = (!empty($task->team) and count($task->team) < 6) ? 6 - count($task->team) : 1;?>
<?php if(isset($task->status) and $task->status != 'wait' and $task->status != 'doing') $newRowCount = 0;?>
<?php js::set('newRowCount', $newRowCount);?>
<?php if(isset($task->mode) and $task->mode == 'linear') js::set('sortSelector', 'tr.member-wait');?>
<?php js::set('teamMemberError', $lang->task->error->teamMember);?>
<?php if(isset($task->status)):?>
<?php js::set('taskStatus', $task->status);?>
<?php js::set('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));?>
<?php if($newRowCount == 0 and $app->rawMethod == 'edit'):?>
<tr>
  <td colspan='3'>
    <div class='alert alert-info'><?php printf($lang->task->noticeManageTeam, zget($lang->task->statusList, $task->status));?></div>
  </td>
</tr>
<?php endif;?>
<?php endif;?>
<?php js::set('id', $i);?>
<script>
$(document).ready(function()
{
    <?php if(isset($task->mode) and $task->mode == 'multi'):?>
    $('tr.teamTemplate').closest('tbody.sortable').sortable('destroy');
    <?php endif;?>

    /* Init task team manage dialog */
    var $taskTeamEditor = $('tr.teamTemplate').closest('table').batchActionForm(
    {
        idStart: id,
        idEnd: id + newRowCount - 1,
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
                if($.inArray(value, members) >= 0)
                {
                    $teams.eq(i).closest('tr').addClass('hidden');
                    continue;
                }
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
            $taskTeamEditor.find('tr.hidden').remove();
        }
    }

    var checkRemove = function(removeIndex)
    {
        var $teams      = $taskTeamEditor.find('select#team');
        var totalLeft   = 0;
        var memberCount = 0;
        for(i = 0; i < $teams.length; i++)
        {
            var $this = $teams.eq(i);
            var value = $this.val();
            if(value == '') continue;

            var $tr = $this.closest('tr');
            if($tr.index() == removeIndex) continue;

            memberCount++;

            var $teamLeft = $tr.find('[name^=teamLeft]');
            if($teamLeft.length > 0)
            {
                left = parseFloat($teamLeft.val());
                if(!isNaN(left)) totalLeft += left;
            }
        }

        if(memberCount < 2)
        {
            bootbox.alert(teamMemberError);
            return false;
        }

        <?php if($app->rawMethod == 'edit'):?>
        if(totalLeft == 0 && (taskStatus == 'doing' || taskStatus == 'pause'))
        {
            bootbox.alert(totalLeftError);
            return false;
        }
        <?php endif;?>

        if($taskTeamEditor.find('td > .btn-delete:enabled').length == 1) return false;

        return true;
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
        <?php if(!empty($task->team)):?>
        if(!checkRemove($row.index())) return;
        <?php endif;?>
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

    $('#mode').change(function()
    {
        if($(this).val() == 'multi')
        {
            disableMembers();
        }
        else
        {
            $taskTeamEditor.find('select#team').each(function()
            {
                $(this).find('option:disabled').removeAttr('disabled').trigger("chosen:updated");
            })
        }
        if($('#teamMember').val() != '') $taskTeamEditor.find('tfoot .btn').click();
    })
});
</script>
