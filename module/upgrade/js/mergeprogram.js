$(function()
{
    $('.side-col .cell').height($('.side-col').height() - 20);
    $('#source .cell').height($('#source').height());
    $('#programBox .cell').height($('#programBox').height() - 20);

    PGMBegin = $('.PGMParams #begin').val();
    PGMEnd   = $('.PGMParams #end').val();
    setPGMBegin(PGMBegin);
    setPGMEnd(PGMEnd);

    $('[name^=lines]').change(function()
    {
        value = $(this).val();
        if($(this).prop('checked'))
        {
            $('[data-line=' + value + ']').prop('checked', true)
        }
        else
        {
            $('[data-line=' + value + ']').prop('checked', false)
        }
        setPGMBegin(PGMBegin);
        setPGMEnd(PGMEnd);
    })

    var PGMOriginEnd = $('#end').val();
    $('#longTime').change(function()
    {
        if($(this).prop('checked'))
        {
            PGMOriginEnd = $('#end').val();
            $('#end').val('').attr('disabled', 'disabled');
            $('#days').val('');
        }
        else
        {
            $('#end').val(PGMOriginEnd).removeAttr('disabled');
        }
    });

    $('#lineList li a').click(function()
    {
        if($('#longTime').is(':checked'))
        {
            $('#longTime').attr('checked', false);
            $('#end').removeAttr('disabled');
        }

        /* Active current li and remove active before li. */
        $(this).closest('ul').find('li').removeClass('active');
        $(this).closest('li').addClass('active');

        /* Show current data and hide before data. */
        var target = $(this).attr('data-target');
        $('.lineBox').addClass('hidden');
        $(target).removeClass('hidden');
        $('#source').find('.lineBox :checkBox').prop('checked', false);
        $(target).find(":checkbox").prop('checked', true);

        /* Replace program name. */
        $('#PGMName').val($(this).text());

        /* Replace project name. */
        var productID = $(target).find('.lineGroup .productList input[name*="product"]').val();
        var link = createLink('upgrade', 'ajaxGetProductName', 'productID=' + productID);
        $.post(link, function(data)
        {
            $('#PRJName').val(data);
        })

        setPGMBegin(PGMBegin);
        setPGMEnd(PGMEnd);
    })

    $('[name^=products]').change(function()
    {
        value = $(this).val();
        if($(this).prop('checked'))
        {
            $('[data-product=' + value + ']').prop('checked', true)

            var lineID = $(this).attr('data-line');
            if(lineID && $('[data-lineid=' + lineID + ']').length > 0 && !$('[data-lineid=' + lineID + ']').prop('checked')) $('[data-lineid=' + lineID + ']').prop('checked', true);
        }
        else
        {
            $('[data-product=' + value + ']').prop('checked', false)
        }
        setPGMBegin(PGMBegin);
        setPGMEnd(PGMEnd);
    })

    $('[name^=sprints]').change(function()
    {
        if($(this).prop('checked'))
        {
            var lineID = $(this).attr('data-line');
            if(lineID && $('[data-lineid=' + lineID + ']').length > 0 && !$('[data-lineid=' + lineID + ']').prop('checked')) $('[data-lineid=' + lineID + ']').prop('checked', true);

            var productID = $(this).attr('data-product');
            if(productID && $('[data-productid=' + productID + ']').length > 0 && !$('[data-productid=' + productID + ']').prop('checked')) $('[data-productid=' + productID + ']').prop('checked', true);
        }
        setPGMBegin(PGMBegin);
        setPGMEnd(PGMEnd);
    })

    toggleProgram($('form #newProgram0'));
    toggleProject($('form #newProject0'));
});

function getProjectByProgram(obj)
{
    var programID = $(obj).val();
    var link = createLink('upgrade', 'ajaxGetProjectPairsByProgram', 'programID=' + programID);
    $.post(link, function(data)
    {
        $('#projects').replaceWith(data);
        if($('#newProject0').is(':checked'))
        {
            $('#projects').attr('disabled', 'disabled');
            $('#projects').addClass('hidden');
        }
    })
}

function toggleProgram(obj)
{
    var $obj = $(obj);
    if($obj.length == 0) return false;

    var $programs = $obj.closest('table').find('#programs');
    if($obj.prop('checked'))
    {
        $('form .pgm-no-exist').removeClass('hidden');
        $('form .pgm-exist').addClass('hidden');
        $programs.attr('disabled', 'disabled');
        $('#PGMStatus').closest('.PGMParams').show();

        $('form #newProject0').prop('checked', true);
        toggleProject($('form #newProject0'));
    }
    else
    {
        $('form .pgm-exist').removeClass('hidden');
        $('form .pgm-no-exist').addClass('hidden');
        $('#PGMStatus').closest('.PGMParams').hide();
        $programs.removeAttr('disabled');
    }
}

function toggleProject(obj)
{
    var $obj       = $(obj);
    if($obj.length == 0) return false;

    var $projects  = $obj.closest('table').find('#projects');
    var $programs  = $obj.closest('table').find('#programs');
    var $PGMParams = $obj.closest('table').find('.PGMParams');
    if($obj.prop('checked'))
    {
        $('form .prj-no-exist').removeClass('hidden');
        $('form .prj-exist').addClass('hidden');
        $PGMParams.removeClass('hidden');
        $projects.attr('disabled', 'disabled');
    }
    else
    {
        $('form .prj-exist').removeClass('hidden');
        $('form .prj-no-exist').addClass('hidden');
        $PGMParams.addClass('hidden');
        $projects.removeAttr('disabled');

        $('form #newProgram0').prop('checked', false);
        toggleProgram($('form #newProgram0'));

        getProjectByProgram(programs);
    }
}

function setPRJStatus()
{
    var PRJStatus = 'closed';
    $(':checkbox:checked[data-status]').each(function()
    {
        var status = $(this).attr('data-status');
        if(status == 'doing' || status == 'suspended') 
        {
            PRJStatus = 'doing';
            return false;
        }

        if(status == 'wait') PRJStatus = 'wait';
    });
    if($(':checkbox:checked[data-status]').length == 0) PRJStatus = 'wait';

    $('#PRJStatus').val(PRJStatus);
    $('#PRJStatus').trigger('chosen:updated');

    setPGMStatus(PRJStatus);
}

function setPGMStatus(PRJStatus)
{
    var PGMStatus = 'wait';
    if(PRJStatus != 'wait') PGMStatus = 'doing';
    if(PRJStatus == 'closed') PGMStatus = 'closed';

    $('#PGMStatus').val(PGMStatus);
    $('#PGMStatus').trigger('chosen:updated');
}

function setPGMBegin(PGMBegin)
{
    $(':checkbox:checked[data-begin]').each(function()
    {
        begin = $(this).attr('data-begin').substr(0, 10);
        if(begin == '0000-00-00') return true;

        if(begin < PGMBegin)
        {
            PGMBegin = begin;
            $('.PGMParams #begin').val(PGMBegin);
        }
    });

	setPRJStatus();
}

function setPGMEnd(PGMEnd)
{
    $(':checkbox:checked[data-end]').each(function()
    {
        end = $(this).attr('data-end').substr(0, 10);
        if(end == '0000-00-00') return true;

        if(end > PGMEnd)
        {
            PGMEnd = end;
            $('.PGMParams #end').val(PGMEnd);
        }
    });
}

function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    return new Date(dateString[0], dateString[1] - 1, dateString[2]);
}

/**
 * Compute delta of two days.
 *
 * @param  string $date1
 * @param  string $date1
 * @access public
 * @return int
 */
function computeDaysDelta(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
    delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

    weekEnds = 0;
    for(i = 0; i < delta; i++)
    {
        if((weekend == 2 && date1.getDay() == 6) || date1.getDay() == 0) weekEnds ++;
        date1 = date1.valueOf();
        date1 += 1000 * 60 * 60 * 24;
        date1 = new Date(date1);
    }
    return delta - weekEnds;
}

/**
 * Compute work days.
 *
 * @access public
 * @return void
 */
function computeWorkDays(currentID)
{
    isBactchEdit = false;
    if(currentID)
    {
        index = currentID.replace('begins[', '');
        index = index.replace('ends[', '');
        index = index.replace(']', '');
        if(!isNaN(index)) isBactchEdit = true;
    }

    if(isBactchEdit)
    {
        beginDate = $('#begins\\[' + index + '\\]').val();
        endDate   = $('#ends\\[' + index + '\\]').val();
    }
    else
    {
        beginDate = $('#begin').val();
        endDate   = $('#end').val();
    }

    if(beginDate && endDate)
    {
        if(isBactchEdit)  $('#dayses\\[' + index + '\\]').val(computeDaysDelta(beginDate, endDate));
        if(!isBactchEdit) $('#days').val(computeDaysDelta(beginDate, endDate));
    }
    else if($('input[checked="true"]').val())
    {
        computeEndDate();
    }
}

/**
 * Compute the end date for project.
 *
 * @param  int    $delta
 * @access public
 * @return void
 */
function computeEndDate(delta)
{
    beginDate = $('#begin').val();
    if(!beginDate) return;

    delta     = parseInt(delta);
    beginDate = convertStringToDate(beginDate);
    if((delta == 7 || delta == 14) && (beginDate.getDay() == 1))
    {
        delta = (weekend == 2) ? (delta - 2) : (delta - 1);
    }

    endDate = $.zui.formatDate(beginDate.addDays(delta - 1), 'yyyy-MM-dd');
    $('#end').val(endDate).datetimepicker('update');
    computeWorkDays();
}
