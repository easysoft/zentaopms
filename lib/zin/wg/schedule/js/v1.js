window.clickCalendar = function(event)
{
    if($(event.target).closest('.btn').hasClass('disabled')) return false;

    const $begin    = $(event.target).closest('.btn').data('begin');
    const $end      = $(event.target).closest('.btn').data('end');
    const begin     = $(event.target).closest(type == 'form' ? '.form-group' : 'tr').find($begin).val();
    const end       = $(event.target).closest(type == 'form' ? '.form-group' : 'tr').find($end).val();
    const projectID = $(event.target).closest('.btn').data('project');
    if(!begin || !end) return false;
    if(begin > end)    return false;
    if(begin == longTime || end == longTime) return false;

    const schedule = $(event.target).closest('.btn').prev('input[name^=schedule]').val();
    sessionStorage.setItem('schedule', $(event.target).closest('.btn').prev('input[name^=schedule]').attr('name'));
    zui.Modal.open({key: 'scheduleModal', type: 'ajax', request: {url: $.createLink('project', 'ajaxgetschedule', 'begin=' + begin.replaceAll('-', '') + '&end=' + end.replaceAll('-', '') + '&projectID=' + projectID + '&callback=' + callback), method: 'post', data: {schedule}}, size: 'sm'});
}

window.generateSchedule = function(objectType, $schedule, projectID, begin, end)
{
    if(!begin || !end || end == longTime)
    {
        if(typeof end != 'undefined') $schedule.val('');
        return false;
    }

    schedule = $schedule.val();
    begin    = begin.replaceAll('-', '');
    end      = end.replaceAll('-', '');
    $.post($.createLink('project', 'ajaxGenerateSchedule', 'begin=' + begin + '&end=' + end + '&projectID=' + projectID), {schedule}, function(result)
    {
        result = JSON.parse(result);
        if(result.schedule)
        {
            $schedule.val(result.schedule);
            if(callback) window[callback]($schedule, objectType);
        }
    });
}
