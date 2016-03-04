$(function()
{
    if($('#needNotReview').prop('checked'))
    {
        $('#assignedTo').attr('disabled', 'disabled');
    }
    else
    {
        $('#assignedTo').removeAttr('disabled');
    }
    $('#assignedTo').trigger("chosen:updated");

    $('#needNotReview').change(function()
    {
        if($('#needNotReview').prop('checked'))
        {
            $('#assignedTo').attr('disabled', 'disabled');
        }
        else
        {
            $('#assignedTo').removeAttr('disabled');
        }
        $('#assignedTo').trigger("chosen:updated");
    });

    $('[data-toggle=tooltip]').tooltip();

    // ajust style for file box
    var ajustFilebox = function()
    {
        applyCssStyle('.fileBox > tbody > tr > td:first-child {transition: none; width: ' + ($('#mailtoGroup').width() - 2) + 'px}', 'filebox')
    };
    ajustFilebox();
    $(window).resize(ajustFilebox);
});


