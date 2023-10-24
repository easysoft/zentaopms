<?php $oldMaxCount = $this->config->maxCount;?>
<?php $this->config->maxCount = 500;?>
<style>
#taskTeamEditor button > i {color: #5e626d;}
#taskTeamEditor .estimateBox span {background-color: #fff;}
#taskTeamEditor .estimateBox input {background-color: #fff; border-right-width: 0px;}
#taskTeamEditor input, #taskTeamEditor span, #taskTeamEditor .chosen-container > a {border-color: #eee;}
#taskTeamEditor td.sort-handler {padding-bottom: 11px;}
</style>
<?php if($app->rawMethod != 'create'):?>
<style>
#modalTeam .modal-dialog {width: 855px;}
</style>
<?php endif;?>
<?php if($app->rawMethod == 'assignto'):?>
<style>
#taskTeamEditor td.sort-handler {width: 70px !important;}
</style>
<?php endif;?>
<?php $hiddenArrow = (empty($task->mode) or $task->mode == 'linear') ? '' : 'hidden';?>
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
if(strpos('|closed|cancel|pause|', $task->status) !== false and $app->rawMethod != 'activate')
{
    $memberStatus   = $task->status;
    $memberDisabled = true;
    $sortDisabled   = true;
}

$hourDisabled = $memberDisabled;
if($task->mode == 'multi' and $app->rawMethod == 'activate') $hourDisabled = false;
?>
<tr class='member member-<?php echo $memberStatus;?>' data-estimate='<?php echo (float)$member->estimate?>' data-consumed='<?php echo (float)$member->consumed?>' data-left='<?php echo (float)$member->left?>'>
  <td>
    <span class="team-number"><?php echo $i;?></span>
    <i class="icon icon-angle-down <?php echo $hiddenArrow;?>"></i>
  </td>
  <td class='w-250px'>
    <?php echo html::select("team[]", $members, $member->account, "class='form-control chosen' data-placeholder='{$lang->task->assignedTo}'" . ($memberDisabled ? ' disabled' : ''))?>
    <?php echo html::hidden("teamSource[]", $member->account);?>
    <?php if($memberDisabled) echo html::hidden("team[]", $member->account);?>
  </td>
  <td class='hourBox'>
    <?php if($app->rawMethod == 'create'):?>
    <div class='input-group estimateBox'>
      <?php echo html::input("teamEstimate[]", (float)$member->estimate, "class='form-control text-center' placeholder='{$lang->task->estimateAB}'") ?>
      <span class='input-group-addon'><?php echo 'h';?></span>
    </div>
    <?php else:?>
    <div class='input-group'>
      <div class="input-control has-icon-right">
        <?php echo html::input("teamEstimate[]", (float)$member->estimate, "class='form-control text-center' placeholder='{$lang->task->estimate}'" . ($hourDisabled ? ' readonly' : ''))?>
        <label class="input-control-icon-right">h</label>
      </div>
      <div class="input-control has-icon-right">
        <?php echo html::input("teamConsumed[]", (float)$member->consumed, "class='form-control text-center' readonly placeholder='{$lang->task->consumed}'")?>
        <label class="input-control-icon-right">h</label>
      </div>
      <div class="input-control has-icon-right">
      <?php echo html::input("teamLeft[]", (float)$member->left, "class='form-control text-center' placeholder='{$lang->task->left}'" . ($hourDisabled ? ' readonly' : ''))?>
        <label class="input-control-icon-right">h</label>
      </div>
    </div>
    <?php endif;?>
  </td>
  <td class='w-100px sort-handler'>
    <button type="button" <?php echo $memberDisabled ? 'disabled' : '';?> class="btn btn-link btn-sm btn-icon btn-add"><i class="icon icon-plus"></i></button>
    <button type="button" <?php echo $memberDisabled ? 'disabled' : '';?> class="btn btn-link btn-sm btn-icon btn-delete"><i class="icon icon-trash"></i></button>
    <?php if(!empty($task->mode) and $task->mode == 'linear'):?>
    <button type="button" <?php echo $sortDisabled   ? 'disabled' : '';?> class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
    <?php endif;?>
  </td>
</tr>
<?php $i ++;?>
<?php endforeach;?>
<?php endif;?>
<tr class='template teamTemplate member member-wait'>
  <td>
    <span class="team-number"><?php echo $i;?></span>
    <i class="icon icon-angle-down <?php echo $hiddenArrow;?>"></i>
  </td>
  <td class='w-240px'>
    <?php echo html::select("team[]", $members, '', "class='form-control chosen' data-placeholder='{$lang->task->assignedTo}'")?>
    <?php echo html::hidden("teamSource[]", '');?>
  </td>
  <td class='hourBox'>
    <?php if(empty($task->team) or $app->rawMethod == 'create'):?>
    <div class='input-group estimateBox'>
      <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->estimateAB}'") ?>
      <span class='input-group-addon'><?php echo 'h';?></span>
    </div>
    <?php else:?>
    <div class='input-group'>
      <div class="input-control has-icon-right">
        <?php echo html::input("teamEstimate[]", '', "class='form-control text-center' placeholder='{$lang->task->estimate}'")?>
        <label class="input-control-icon-right">h</label>
      </div>
      <div class="input-control has-icon-right">
        <?php echo html::input("teamConsumed[]", 0, "class='form-control text-center' readonly placeholder='{$lang->task->consumed}'")?>
        <label class="input-control-icon-right">h</label>
      </div>
      <div class="input-control has-icon-right">
        <?php echo html::input("teamLeft[]", '', "class='form-control text-center' placeholder='{$lang->task->left}'")?>
        <label class="input-control-icon-right">h</label>
      </div>
    </div>
    <?php endif;?>
  </td>
  <td class='w-100px sort-handler'>
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
<?php if(!empty($task->mode) and $task->mode == 'linear') js::set('sortSelector', 'tr.member-wait');?>
<?php js::set('teamMemberError', $lang->task->error->teamMember);?>
<?php if(isset($task->status)):?>
<?php js::set('taskStatus', $task->status);?>
<?php if($newRowCount == 0 and $app->rawMethod == 'edit' and $task->mode == 'linear'):?>
<tr>
  <td colspan='4'>
    <div class='alert alert-info'><?php printf($lang->task->noticeManageTeam, zget($lang->task->statusList, $task->status));?></div>
  </td>
</tr>
<?php endif;?>
<?php endif;?>
<script>
$(document).ready(function()
{
    <?php if(!empty($task->mode) and $app->rawMethod == 'create'):?>
    $('#multipleBox').attr('checked', 'checked');
    showTeamMenu();
    <?php endif;?>

    <?php if(isset($task->mode) and $task->mode == 'multi'):?>
    $('tr.teamTemplate').closest('tbody.sortable').sortable('destroy');
    <?php else:?>
    var options = {
        selector: '.icon-move',
        dragCssClass: 'drag-row',
        reverse: true,
        finish: setLineNumber
    }

    $('#taskTeamEditor tbody.sortable').sortable(options);
    <?php endif;?>

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
        if($deleteBtn.length == 1)
        {
            $deleteBtn.addClass('disabled').attr('disabled', 'disabled');
        }
        else if(config.currentMethod == 'create')
        {
            $deleteBtn.removeClass('disabled').removeAttr('disabled');
        }

    };

    var disableMembers = function()
    {
        var mode = $('[name="mode"]').length > 0 ? $('[name="mode"]').val() : '<?php echo (!empty($task->mode) ? $task->mode : '')?>';
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

        var taskMode = $('[name="mode"]').val();
        if(taskMode == 'multi') $('#taskTeamEditor tr.member .icon-angle-down').addClass('hidden');

        disableMembers();
        adjustButtons();
        setLineNumber();
    }).on('click', '.btn-delete', function()
    {
        if($(this).hasClass('disabledDeleted')) return;

        var $row = $(this).closest('tr');
        <?php if(!empty($task->team)):?>
        if(!checkRemove($row.index())) return;
        <?php endif;?>

        $taskTeamEditor.find('.btn-delete').addClass('disabledDeleted');
        $row.addClass('highlight').fadeOut(700, function()
        {
            $row.remove();
            $taskTeamEditor.find('.btn-delete').removeClass('disabledDeleted');
            disableMembers();
            adjustButtons();
            setLineNumber();
        });
    });

    adjustButtons();
    disableMembers();
    setLineNumber();

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

    $('[name="mode"]').change(function()
    {
        if($(this).val() == 'multi')
        {
            disableMembers();
            $('#taskTeamEditor tr.member .icon-angle-down').addClass('hidden');
        }
        else
        {
            $('#taskTeamEditor tr.member .icon-angle-down').removeClass('hidden');
            $taskTeamEditor.find('select#team').each(function()
            {
                $(this).find('option:disabled').removeAttr('disabled').trigger("chosen:updated");
            })
        }
        if($('#teamMember').val() != '' && page != 'edit') $taskTeamEditor.find('tfoot .btn').click();
    })
});

/**
 * Set line number.
 *
 * @access public
 * @return void
 */
function setLineNumber()
{
    var lineNumber = 1;
    $('.team-number').each(function()
    {
        $(this).text(lineNumber);
        lineNumber ++;
    });

}
</script>
<?php $this->config->maxCount = $oldMaxCount;?>
