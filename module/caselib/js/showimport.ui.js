window.handleRenderRow = function($row, index, row)
{
    if(!row.new)
    {
        $row.find('td[data-name=id_static]').append("<input class='hidden' type='text' name='id[" + row.id + "]' value='" + row.id + "'/>");
    }
    else
    {
        $row.find('td[data-name=id_static]').html(row.id + "<sup class='text-success small'>" + newTestcase + "</sup>")
    }
}

function computeImportTimes()
{
    if(parseInt($(this).val()))
    {
        $('#times').html(Math.ceil(parseInt($("#totalAmount").html()) / parseInt($(this).val())));
    }
}

function importNextPage()
{
    let pageID = $('#pageID').val();
    if(typeof pageID == 'undefined' || pageID == '') pageID = 1;

    return loadPage($.createLink('caselib', 'showImport', "libID=" + libID + "&pageID=" + pageID + '&maxImport=' + $('#maxImport').val()));
}
