/**
 * Change all table charsets.
 *
 * @access public
 * @return void
 */
function changeCharset()
{
    var $charsetBox = $('#charsetBox');
    var tables      = charsetTables;
    var finished    = 0;
    var fails       = 0;

    var startBtnWidth = $('#startUpdate').outerWidth();
    $('#startUpdate').remove();

    function updateProgress()
    {
        $('#charsetProgress').text(finished + ' / ' + tables.length);
    }

    function processOne(index)
    {
        if(index >= tables.length)
        {
            $charsetBox.append("<div class='flex items-center text-success my-1 pl-5 h-5'><i class='icon icon-check-circle mr-2'></i>" + charsetFinished.replace('%s', tables.length - fails).replace('%s', tables.length) + "</div>");
            $('#charsetAction').append("<a class='btn primary' style='width:" + startBtnWidth + "px' data-on='click' data-call='loadCurrentPage'>" + refresh + "</a>");
            $charsetBox.children().last().scrollIntoView({block: 'end'});
            return;
        }

        var table = tables[index];
        var $item = $("<div class='flex items-center my-1 pl-5 h-5' data-table='" + table + "'><i class='icon icon-spinner-indicator mr-2 animate-spin'></i>" + charsetChanging.replace('%s', table) + "</div>");
        $charsetBox.append($item);
        $charsetBox.children().last().scrollIntoView({block: 'end'});

        $.getJSON($.createLink('admin', 'ajaxChangeCharset', 'table=' + encodeURIComponent(table)))
            .done(function(response)
            {
                if(response.result == 'success')
                {
                    $item.html("<i class='icon icon-check-circle mr-2'></i>" + (response.message || charsetSuccess)).addClass('text-success');
                }
                else
                {
                    fails++;
                    $item.html("<i class='icon icon-exclamation-sign mr-2'></i>" + (response.message || charsetFailed)).addClass('text-warning');
                }
                finished++;
                updateProgress();
                processOne(index + 1);
            })
            .fail(function()
            {
                fails++;
                $item.html("<i class='icon icon-exclamation-sign mr-2'></i>" + charsetFailed).addClass('text-warning');
                finished++;
                updateProgress();
                processOne(index + 1);
            });
    }

    processOne(0);
}
