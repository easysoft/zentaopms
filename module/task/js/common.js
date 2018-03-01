/* If left = 0, warning. */
function checkLeft()
{
    value = $("#left").val();
    if(isNaN(parseInt(value)) || value == 0) 
    {
        if(confirm(confirmFinish))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

$('.btn-move-up, .btn-move-down').click(function()
{
    var $this = $(this);
    if($this.hasClass('btn-move-up'))
    {
        $(this).parents('tr').prev().before($(this).parents('tr'));
    }
    else
    {
        $this.parents('tr').next().after($(this).parents('tr'));
    }
    $('.btn-move-up, .btn-move-down').removeClass('disabled').removeAttr('disabled');

    adjustSortBtn();
});

function adjustSortBtn()
{
    $('.btn-move-up:first').addClass('disabled').attr('disabled', 'disabled');
    $('.btn-move-down:last').addClass('disabled').attr('disabled', 'disabled');
}

$('#modalTeam .btn').click(function()
{
    var team = '';
    var time = 0;
    $('[name*=team]').each(function()
    {
        if($(this).find('option:selected').text() != '')
        {
            team += ' ' + $(this).find('option:selected').text();
        }

        estimate = parseFloat($(this).parents('td').next('td').find('[name*=teamEstimate]').val());
        if(!isNaN(estimate))
        {
            time += estimate;
        }

        $('#teamMember').val(team);
        $('#estimate').val(time);
    })
});
