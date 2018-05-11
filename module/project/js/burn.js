$(function()
{
    initBurnChar();
    $('#interval').change(function()
    {
        location.href = createLink('project', 'burn', 'projectID=' + projectID + '&type=' + type + '&interval=' + $(this).val());
    });
})
