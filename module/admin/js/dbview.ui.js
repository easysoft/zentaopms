var dbViewUpdating = false;
/**
 * Regenerate database views.
 *
 * @access public
 * @return void
 */
function regenerateDbViews()
{
    if(dbViewUpdating) return;

    dbViewUpdating = true;
    $('#startUpdate').addClass('disabled');

    var $viewBox = $('#viewBox');
    var views    = viewNames;
    var finished = 0;
    var fails    = 0;

    $viewBox.empty();

    function updateProgress()
    {
        $('#viewProgress').text(finished + ' / ' + views.length);
    }

    function processOne(index)
    {
        if(index >= views.length)
        {
            $viewBox.append("<div class='flex items-center text-success my-1 pl-5 h-5'><i class='icon icon-check-circle mr-2'></i>" + dbViewResult.replace('%s', views.length - fails).replace('%s', views.length) + "</div>");
            if(fails > 0) $viewBox.append("<div class='flex items-center text-warning my-1 pl-5 h-5'><i class='icon icon-exclamation-sign mr-2'></i>" + dbViewFailed + "</div>");
            $viewBox.children().last().scrollIntoView({block: 'end'});
            dbViewUpdating = false;
            $('#startUpdate').removeClass('disabled');
            return;
        }

        var view = views[index];
        var $item = $("<div class='flex items-center my-1 pl-5 h-5' data-view='" + view + "'><i class='icon icon-spinner-indicator mr-2 animate-spin'></i>" + dbViewRegenerate.replace('%s', view) + "</div>");
        $viewBox.append($item);
        $viewBox.children().last().scrollIntoView({block: 'end'});

        $.getJSON($.createLink('admin', 'ajaxRegenerateView', 'view=' + encodeURIComponent(view)))
            .done(function(response)
            {
                if(response.result == 'success')
                {
                    $item.html("<i class='icon icon-check-circle mr-2'></i>" + dbViewSuccess.replace('%s', view)).addClass('text-success');
                }
                else
                {
                    fails++;
                    $item.html("<i class='icon icon-exclamation-sign mr-2'></i>" + (response.message || dbViewFail.replace('%s', view))).addClass('text-warning');
                }
                finished++;
                updateProgress();
                processOne(index + 1);
            })
            .fail(function()
            {
                fails++;
                $item.html("<i class='icon icon-exclamation-sign mr-2'></i>" + dbViewFail.replace('%s', view)).addClass('text-warning');
                finished++;
                updateProgress();
                processOne(index + 1);
            });
    }

    processOne(0);
}
