/* Show team menu. */
function showTeamBox()
{
    if($('[name^=multiple]').prop('checked'))
    {
        $('.team-group').removeClass('hidden');
        $('.modeBox').removeClass('hidden');
        $('#estimate').attr('readonly', true);
    }
    else
    {
        $('.team-group').addClass('hidden');
        $('.modeBox').addClass('hidden');
        $('#estimate').attr('readonly', false);
    }
}

/* Set the assignedTos field. */
function typeChange(e)
{
    var result = $(e.target).val();
    if(result == 'affair')
    {
        $("#multipleBox").removeAttr("checked");
        $('.team-group').addClass('hidden');
        $('.modeBox').addClass('hidden');
        $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
        $('#assignedTo').next('.picker').removeClass('hidden');

        $('#assignedTo').attr('multiple', 'multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen();
        $('.affair').hide();
        $('.team-group').addClass('hidden');
        $('.modeBox').addClass('hidden');
        $('#selectAllUser').removeClass('hidden');
    }
    else if($('#assignedTo').attr('multiple') == 'multiple')
    {
        $('#assignedTo').removeAttr('multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen();
        $('.affair').show();
        $('#selectAllUser').addClass('hidden');
    }

    if(lifetime != 'ops' && attribute != 'request' && attribute != 'review')
    {
        $('#selectTestStoryBox').toggleClass('hidden', result != 'test');
        toggleSelectTestStory();
    }
}

function toggleSelectTestStory()
{
    if(!$('#selectTestStoryBox').hasClass('hidden') && $('#selectTestStory').prop('checked'))
    {
        $('#module').closest('tr').addClass('hidden');
        $('#multipleBox').closest('td').addClass('hidden');
        $('#story').closest('tr').addClass('hidden');
        $('#estStarted').closest('tr').addClass('hidden');
        $('#estimate').closest('.table-col').addClass('hidden');
        $('#testStoryBox').removeClass('hidden');
        $('#copyButton').addClass('hidden');
        $('.colorpicker').css('right', '0');
        $('#dataform .table-form>tbody>tr>th').css('width', '130px');
        $('[lang^="zh-"] #dataform .table-form>tbody>tr>th').css('width', '120px');

        $('[name^=multiple]').attr('checked', false);
        showTeamMenu();
    }
    else
    {
        $('#module').closest('tr').removeClass('hidden');
        $('#multipleBox').closest('td').removeClass('hidden');
        if(showFields.indexOf('story') != -1) $('#story').closest('tr').removeClass('hidden');
        $('#estStarted').closest('tr').removeClass('hidden');
        $('#estimate').closest('.table-col').removeClass('hidden');
        $('#testStoryBox').addClass('hidden');
        $('#dataform .table-form>tbody>tr>th').css('width', '100px');
    }
}
