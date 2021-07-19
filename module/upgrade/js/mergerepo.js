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
            var repoNum        = $("[id^='repos'").length;
            var checkedRepoNum = $("[id^='repos']:checked").length;

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
            $('[name^=repos]').prop('checked', true);
        }
        else
        {
            $('[name^=repos]').prop('checked', false);
        }
    })

    /* Select a repo event. */
    $('[name^=repos]').change(function()
    {
        /* Determine whether all repo buttons are selected. */
        var checked        = true;
        var repoNum        = $("[id^='repos'").length;
        var checkedRepoNum = $("[id^='repos']:checked").length;

        if(repoNum > checkedRepoNum) checked = false;
        $('#checkAllRepos').prop('checked', checked);
    })
})
