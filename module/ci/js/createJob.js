$(function()
{
    triggerTypeChanged('tag');
});

function triggerTypeChanged(type) {
    if(type == 'tag') {
        $('.tag-fields').removeClass('hidden');
        $('.comment-fields').addClass('hidden');

        scheduleTypeChanged();
    } else if(type == 'commit') {
        $('.tag-fields').addClass('hidden');
        $('.comment-fields').removeClass('hidden');

        $('.cron-fields').addClass('hidden');
        $('.custom-fields').addClass('hidden');

        scheduleTypeChanged();
    } else if(type == 'schedule') {
        $('.tag-fields').addClass('hidden');
        $('.comment-fields').addClass('hidden');

        var val = $("input[name='scheduleType']:checked").val();
        console.log(val);
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