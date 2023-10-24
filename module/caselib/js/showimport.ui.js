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

    let stepDesc   = "<input class='hidden' type='text' name='lib[" + row.id + "]' value='" + libID + "'/>";
    let stepExpect = '';

    let currentKey = index + 1;
    const descs  = stepData[currentKey]['desc'];
    const expect = stepData[currentKey]['expect'];
    if(descs.length)
    {
        $.each(descs, function(id, desc)
        {
            if(!desc.content) return;
            stepDesc   += "<div class='flex col'><div class='cell flex border p-2'><div class='cell center'><input class='hidden' type='text' name='stepType[" + row.id + "][" + desc.number + "]' value='" + desc.type + "' /><span>" + desc.number + "、</span></div><div class='cell center flex-1'><textarea class='form-control form-batch-input' rows='10' name='desc[" + row.id + "][" + desc.number + "]' style='min-height: 32px; height:2rem;'>" + desc.content + "</textarea></div></div></div>";
            stepExpect += "<div class='flex col'><div class='cell flex border p-2'><textarea class='form-control form-batch-input " + (desc.type != 'group' ? '' : 'disabled') + "'" + (desc.type != 'group' ? '' : 'readonly=readonly') + "' rows='10' name='expect[" + row.id + "][" + desc.number + "]' style='min-height: 32px; height:2rem;'>" + (expect[id]['content'] ? expect[id]['content'] : '') + "</textarea></div></div></div>";
        })
    }
    else
    {
        stepDesc   += "<div class='flex col'><div class='cell flex border p-2'><div class='cell center'><input class='hidden' type='text' name='stepType[" + row.id + "][1]' value='step' /><span>1、</span></div><div class='cell center flex-1'><textarea class='form-control form-batch-input' rows='10' name='desc[" + row.id + "][1]' style='min-height: 32px; height:2rem;'></textarea></div></div></div>";
        stepExpect += "<div class='flex col'><div class='cell flex border p-2'><textarea class='form-control form-batch-input' rows='10' name='expect[" + row.id + "][1]'  style='min-height: 32px; height:2rem;'></textarea></div></div>";
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
    return loadPage($.createLink('caselib', 'showImport', "libID='" + libID + "'&pageID=" + $('#pageID').val() + '&maxImport=' + $('#maxImport').val()));
}
