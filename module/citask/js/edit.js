$(function()
{
    $('#' + module + 'Tab').addClass('btn-active-text');
    triggerTypeChanged(triggerType);
});

function exeCitask(id) {
    var link = createLink('ci', 'exeCitask', 'id=' + id);
    console.log(link);
    $.ajax({
        type:"POST",
        url: link,
        data: {},
        datatype: "json",
        success:function(str){
            $('.exe-citask-button').tooltip('show', '发送执行请求成功！');
        }
    });
}

function triggerTypeChanged(type) {
    if(type == 'tag') {
        $('.tag-fields').removeClass('hidden');
        $('.comment-fields').addClass('hidden');

        scheduleTypeChanged();
    } else if(type == 'commit') {
        $('.tag-fields').addClass('hidden');
        $('.comment-fields').removeClass('hidden');

        $('.corn-fields').addClass('hidden');
        $('.custom-fields').addClass('hidden');

        scheduleTypeChanged();
    } else if(type == 'schedule') {
        $('.tag-fields').addClass('hidden');
        $('.comment-fields').addClass('hidden');

        var val = $("input[name='scheduleType']:checked").val();
        scheduleTypeChanged(val? val: 'cron');
    }
}

function scheduleTypeChanged(type) {
    if(type == 'cron') {
        $('.schedule-fields').removeClass('hidden');

        $('.cron-fields').removeClass('hidden');
        $('.custom-fields').addClass('hidden');
    } else if(type == 'custom') {
        $('.schedule-fields').removeClass('hidden');

        $('.cron-fields').addClass('hidden');
        $('.custom-fields').removeClass('hidden');
    } else {
        $('.schedule-fields').addClass('hidden');

        $('.cron-fields').addClass('hidden');
        $('.custom-fields').addClass('hidden');
    }
}