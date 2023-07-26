var changeFails = 0;
/**
 * Change all table engines.
 *
 * @access public
 * @return void
 */
function changeAllEngines()
{
    $(event.target).hide();
    var $engineBox = $('#engineBox');
    $engineBox.empty();

    changeTableEngine();
}

/**
 * Ajax change table engine.
 *
 * @access public
 * @return void
 */
function changeTableEngine()
{
    var $engineBox = $('#engineBox');
    var link       = $.createLink('admin', 'ajaxChangeTableEngine');
    $.ajax(
    {
        type: "GET",
        url: link,
        success: function(response)
        {
            response = JSON.parse(response);
            if(response == null || response.result == 'finished')
            {
                $engineBox.append("<div class='flex items-center text-success my-1 pl-5 h-5'><div class='rounded-full success mr-2 w-1 h-1'></div>" + changeFinished + '</div>');
                $engineBox.append("<div class='flex items-center my-1 pl-8 h-5'><button class='btn mt-2' data-on='click' data-call='loadCurrentPage'>" + refresh + '</button></div>');
                if(changeFails == 0) $('#mainContent .table-engine').html(allInnoDB);
                if(changeFails != 0) $('#mainContent .table-engine').html(hasMyISAM.replace('%s', changeFails));
                $engineBox.children().last().scrollIntoView({block: 'end'});
            }
            else
            {
                table = response.thisTable;
                if($engineBox.find('[data-table=' + table + ']').length == 0) $engineBox.append("<div class='flex items-center my-1 pl-5 h-5' data-table='" + table + "'><div class='rounded-full black mr-2 w-1 h-1'></div>" + changingTable.replace('%s', table) + '</div>');
                $engineBox.find('[data-table=' + table + ']').html("<div class='rounded-full black mr-2 w-1 h-1'></div>" + response.message);
                if(response.result == 'success') $engineBox.find('[data-table=' + table + ']').addClass('text-success').find('.black').toggleClass('black success');
                if(response.result == 'fail') $engineBox.find('[data-table=' + table + ']').addClass('text-warning');

                nextTable = response.nextTable;
                if(nextTable && $engineBox.find('[data-table=' + nextTable + ']').length == 0) $engineBox.append("<div class='flex items-center my-1 pl-5 h-5' data-table='" + nextTable + "'><div class='rounded-full black mr-2 w-1 h-1'></div>" + changingTable.replace('%s', nextTable) + "</div>");

                if(response.result == 'fail') changeFails++;
                $engineBox.children().last().scrollIntoView({block: 'end'});

                changeTableEngine();
            }
        },
        error: function()
        {
            changeTableEngine();
        }
    })
}
