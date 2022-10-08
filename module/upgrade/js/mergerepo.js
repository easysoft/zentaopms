$(function()
{
    /* Define drag to select relevant parameters. */
    var options = {
        selector: 'input',
        listenClick: false,
        select: function(e)
        {
            $('[data-id=' + e.id + ']').prop('checked', true);

            var checked        = true;
            var repoNum        = $("[id^='repoes'").length;
            var checkedRepoNum = $("[id^='repoes']:checked").length;

            if(repoNum > checkedRepoNum) checked = false;
            $('#checkAllRepos').prop('checked', checked);
        }
    }

    /* Initialize the drag selected. */
    $('#source').selectable(options);

    /* Select all repo events. */
    $('#checkAllRepos').click(function()
    {
        if($(this).is(':checked'))
        {
            $('[name^=repoes]').prop('checked', true);
        }
        else
        {
            $('[name^=repoes]').prop('checked', false);
        }
    })

    /* Select a repo event. */
    $('[name^=repoes]').change(function()
    {
        /* Determine whether all repo buttons are selected. */
        var checked        = true;
        var repoNum        = $("[id^='repoes'").length;
        var checkedRepoNum = $("[id^='repoes']:checked").length;

        if(repoNum > checkedRepoNum) checked = false;
        $('#checkAllRepos').prop('checked', checked);
    })

    $('#products').change(function()
    {
        $('#submit').removeAttr('disabled');
    })
})
