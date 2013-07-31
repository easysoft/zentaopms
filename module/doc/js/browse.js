/* Browse by module. */
function browseByModule()
{
    $('#treebox').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#allTab').removeClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').addClass('hidden');
}

function browseBySearch()
{
    $('#treebox').addClass('hidden');
    $('.divider').addClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#allTab').addClass('active');
    $('#bysearchTab').addClass('active');
    $('#querybox').removeClass('hidden');
}

$(function(){
    $('#' + browseType + 'Tab').addClass('active');
    if(browseType == "bysearch")
    {
        ajaxGetSearchForm();
        browseBySearch();
    }
});

/**
 * Delete doc. 
 * 
 * @param  int    $docID 
 * @access public
 * @return void
 */
function deleteDoc(docID)
{
    if(confirm(confirmDelete))
    {
        url = createLink('doc', 'delete','docID=' + docID + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    $('#docbox').load(request + ' #doclist', function()
                    {
                        $('.colored').colorize();
                        $('tfoot td').css('background', 'white').unbind('click').unbind('hover');
                    });
                }
            }
        });
    }
}
