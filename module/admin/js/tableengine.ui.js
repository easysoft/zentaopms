/**
 * Change all table engines to InnoDB.
 *
 * @access public
 * @return void
 */
function changeTableEngines()
{
    var $engineBox = $('#engineBox');
    var tables     = tableEngines;
    var finished   = 0;
    var changeFails = 0;

    var startBtnWidth = $('#startUpdate').outerWidth();
    $('#startUpdate').remove();

    function updateProgress()
    {
        $('#engineProgress').text(finished + ' / ' + tables.length);
    }

    function processOne(index)
    {
        if(index >= tables.length)
        {
            $engineBox.append("<div class='flex items-center text-success my-1 pl-5 h-5'><i class='icon icon-check-circle mr-2'></i>" + changeFinished.replace('%s', tables.length - changeFails).replace('%s', tables.length) + "</div>");
            if(changeFails > 0) $engineBox.append("<div class='flex items-center text-warning my-1 pl-5 h-5'><i class='icon icon-exclamation-sign mr-2'></i>" + hasMyISAM.replace('%s', changeFails) + "</div>");
            $('#engineAction').append("<a class='btn primary' style='width:" + startBtnWidth + "px' data-on='click' data-call='loadCurrentPage'>" + refresh + "</a>");
            $engineBox.children().last().scrollIntoView({block: 'end'});
            return;
        }

        var table = tables[index];
        var $item = $("<div class='flex items-center my-1 pl-5 h-5' data-table='" + table + "'><i class='icon icon-spinner-indicator mr-2 animate-spin'></i>" + changingTable.replace('%s', table) + "</div>");
        $engineBox.append($item);
        $engineBox.children().last().scrollIntoView({block: 'end'});

        $.getJSON($.createLink('admin', 'ajaxChangeTableEngine', 'table=' + encodeURIComponent(table)))
            .done(function(response)
            {
                if(response.result == 'success')
                {
                    $item.html("<i class='icon icon-check-circle mr-2'></i>" + (response.message || changeSuccess.replace('%s', table))).addClass('text-success');
                }
                else
                {
                    changeFails++;
                    $item.html("<i class='icon icon-exclamation-sign mr-2'></i>" + response.message).addClass('text-warning');
                }
                finished++;
                updateProgress();
                processOne(index + 1);
            })
            .fail(function()
            {
                changeFails++;
                $item.html("<i class='icon icon-exclamation-sign mr-2'></i>" + tableEngineFail.replace('%s', table)).addClass('text-warning');
                finished++;
                updateProgress();
                processOne(index + 1);
            });
    }

    processOne(0);
}
