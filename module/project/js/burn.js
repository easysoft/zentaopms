$('#interval').change(function()
{
    location.href = createLink('project', 'burn', 'projectID=' + projectID + '&type=' + type + '&interval=' + $(this).val());
})

var weekendType = $('#weekend').attr('checked');
$('#weekend').click(function()
{
    weekendType = weekendType ? 'noweekend' : 'widthweekend';
    params      = 'projectID=' + projectID + '&type=' + weekendType;
    if(interval) params = params + '&interval=' + interval;
    location.href = createLink('project', 'burn', params);
})
