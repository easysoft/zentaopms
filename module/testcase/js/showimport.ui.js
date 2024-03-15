window.renderRowCol = function($result, col, row)
{
    if(col.name == 'steps')
    {
        $result.empty();
        const index = $result.closest('tr').attr('data-index');
        $.each(stepData[index]['desc'], function(i, desc){
            const stepIndex = desc.number;
            $result.append('<div class="input-group step-box mb-2 gap-x-2"><span class="w-8 flex-none">' + stepIndex + '</span></div>');
            $result.find('.step-box').last().append('<input type="hidden" name="stepType[' + (parseInt(index) + 1) + '][' + stepIndex + ']" id="stepType' + index + stepIndex + '" value="' + desc.type + '"></input>');
            $result.find('.step-box').last().append('<div class="input-group mb-2 gap-x-2 w-full"></div>');

            const $currentInputGroup = $result.find('.step-box').last().find('.input-group');

            $currentInputGroup.last().append('<textarea name="desc[' + (parseInt(index) + 1) + '][' + stepIndex + ']" id="desc' + index + stepIndex + '" class="form-control w-1/2"></textarea>');
            $currentInputGroup.last().find('textarea').val(desc.content);

            const expect = stepData[index]['expect'][i];
            if(expect.type != 'group')
            {
                $currentInputGroup.append('<textarea name="expect[' + (parseInt(index) + 1) + '][' + stepIndex + ']" id="expect' + index + stepIndex + '" class="form-control w-1/2"></textarea>');
                $currentInputGroup.find('textarea[name^=expect]').val(expect.content);
            }
            else
            {
                $currentInputGroup.append('<div class="w-1/2"></div>');
            }
        });
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
    link = $.createLink('testcase', 'showImport', "productID=" + productID + "&branch=" + branch + "&pageID=1&maxImport=" + $('#maxImport').val());
    loadPage(link);
}
