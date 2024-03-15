window.handleRenderRow = function($row, index, row)
{
    if(!row) return false;

    if(row.rawID == '') row.rawID = index + 1;
    let stepDesc   = "<input class='hidden' type='text' name='product[" + row.rawID + "]' value='" + productID + "'/>";
    let stepExpect = '';

    if(!stepData[index]) return false;

    const descs  = stepData[index]['desc'];
    const expect = stepData[index]['expect'];
    if(descs.length)
    {
        $.each(descs, function(id, desc)
        {
            if(!desc.content) return;
            stepDesc   += "<div class='flex col'><div class='cell flex border p-2'><div class='cell center'><input class='hidden' type='text' name='stepType[" + row.rawID + "][" + desc.number + "]' value='" + desc.type + "' /><span>" + desc.number + "、</span></div><div class='cell center flex-1'><textarea class='form-control form-batch-input' rows='10' name='desc[" + row.rawID + "][" + desc.number + "]' style='min-height: 32px; height:2rem;'>" + desc.content + "</textarea></div></div></div>";
            stepExpect += "<div class='flex col'><div class='cell flex border p-2'><textarea class='form-control form-batch-input " + (desc.type != 'group' ? '' : 'disabled') + "'" + (desc.type != 'group' ? '' : 'readonly=readonly') + "' rows='10' name='expect[" + row.rawID + "][" + desc.number + "]' style='min-height: 32px; height:2rem;'>" + (expect[id]['content'] ? expect[id]['content'] : '') + "</textarea></div></div></div>";
        })
    }
    else
    {
        stepDesc   += "<div class='flex col'><div class='cell flex border p-2'><div class='cell center'><input class='hidden' type='text' name='stepType[" + row.rawID + "][1]' value='step' /><span>1、</span></div><div class='cell center flex-1'><textarea class='form-control form-batch-input' rows='10' name='desc[" + row.rawID + "][1]' style='min-height: 32px; height:2rem;'></textarea></div></div></div>";
        stepExpect += "<div class='flex col'><div class='cell flex border p-2'><textarea class='form-control form-batch-input' rows='10' name='expect[" + row.rawID + "][1]'  style='min-height: 32px; height:2rem;'></textarea></div></div>";
    }


    $row.find('td[data-name=stepDesc]').html(stepDesc);
    $row.find('td[data-name=stepExpect]').html(stepExpect);
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
    link = $.createLink('testcase', 'showImport', "productID=" + productID + "&branch=" + branch + "&pageID=1&maxImport=" + $('#maxImport').val());
    loadPage(link);
}
