$(function()
{
    $('.side-col .cell').height($('.side-col').height() - 20);
    $('#source .cell').height($('#source').height());
    $('#programBox .cell').height($('#programBox').height() - 20);

    programBegin = $('.programParams #begin').val();
    programEnd   = $('.programParams #end').val();
    setProgramBegin(programBegin);
    setProgramEnd(programEnd);
    setProjectPM();

    setProgramByProduct($(':checkbox:checked[data-productid]'));

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
        setProgramBegin(programBegin);
        setProgramEnd(programEnd);
        setProjectPM();
    })

    var programOriginEnd = $('#end').val();
    $('#longTime').change(function()
    {
        if($(this).prop('checked'))
        {
            programOriginEnd = $('#end').val();
            $('#end').val('').attr('disabled', 'disabled');
            $('#days').val('');
        }
        else
        {
            $('#end').val(programOriginEnd).removeAttr('disabled');
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

        /* The first group of products is selected by default. */
        var firstProduct = $(target).find(":checkbox:first").prop('checked', true);
        $('[data-product=' + firstProduct.val() + ']').prop('checked', true);

        /* Replace program name. */
        $('#programName').val($(this).text());

        /* Replace project name. */
        var productID = $(target).find('.lineGroup .productList input[name*="product"]').val();
        var link = createLink('upgrade', 'ajaxGetProductName', 'productID=' + productID);
        $.post(link, function(data)
        {
            $('#projectName').val(data);
        })

        setProgramBegin(programBegin);
        setProgramEnd(programEnd);
        setProjectPM();
    })

    $('[name^=products]').change(function()
    {
        setProgramByProduct($(this));

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
        setProgramBegin(programBegin);
        setProgramEnd(programEnd);
        setProjectPM();

        hiddenProject();
    })

    $('[name^=sprints]').change(function()
    {
        if($(this).prop('checked'))
        {
            var lineID = $(this).attr('data-line');
            if(lineID && $('[data-lineid=' + lineID + ']').length > 0 && !$('[data-lineid=' + lineID + ']').prop('checked')) $('[data-lineid=' + lineID + ']').prop('checked', true);

            var productID = $(this).attr('data-product');
            if(productID && $('[data-productid=' + productID + ']').length > 0 && !$('[data-productid=' + productID + ']').prop('checked')) $('[data-productid=' + productID + ']').prop('checked', true);

            setProgramByProduct($(':checkbox[data-productid=' + productID + ']'));
        }
        setProgramBegin(programBegin);
        setProgramEnd(programEnd);
        setProjectPM();

        hiddenProject();
    })

    toggleProgram($('form #newProgram0'));
    toggleProject($('form #newProject0'));
    toggleProject($('form #newLine0'));

    hiddenProject();
});

/**
 * Get project by program id.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
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

    getLineByProgram();
    getProgramStatus('program', programID);
}

/**
 * Get lines by program id.
 *
 * @access public
 * @return void
 */
function getLineByProgram()
{
    var programID = $('#programs').val();
    var link      = createLink('upgrade', 'ajaxGetLinesPairsByProgram', 'programID=' + programID);

    $.post(link, function(data)
    {
        $('#lines').replaceWith(data);
        if($('#newLine0').is(':checked'))
        {
            $('#lines').attr('disabled', 'disabled');
            $('#lines').addClass('hidden');
        }
    })

    if(!programID) $('lineBox').addClass('hidden');
    if(programID)  $('lineBox').removeClass('hidden');
}

/**
 * Toggle program name.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
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
        $('.programStatus').show();

        $('form #newProject0').prop('checked', true);
        $('form #newLine0').prop('checked', true);
        toggleProject($('form #newProject0'));
        toggleLine($('form #newProject0'));
    }
    else
    {
        $('form .pgm-exist').removeClass('hidden');
        $('form .pgm-no-exist').addClass('hidden');
        $('.programStatus').hide();

        if(!$('#newProgram0').prop('disabled'))
        {
            $programs.removeAttr('disabled');
        }

        var programID = $('#programs').val();
        getProgramStatus('program', programID);
    }
}

/**
 * Toggle line.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function toggleLine(obj)
{
    var $obj       = $(obj);
    if($obj.length == 0) return false;

    var $lines     = $obj.closest('table').find('#lines');
    var $programs  = $obj.closest('table').find('#programs');

    if($obj.prop('checked'))
    {
        $('form .line-no-exist').removeClass('hidden');
        $('form .line-exist').addClass('hidden');
        $lines.attr('disabled', 'disabled');
    }
    else
    {
        $('form .line-exist').removeClass('hidden');
        $('form .line-no-exist').addClass('hidden');
        $('.programStatus').hide();
        $lines.removeAttr('disabled');

        $('form #newProgram0').prop('checked', false);
        toggleProgram($('form #newProgram0'));

        getLineByProgram();
    }
}

/**
 * Toggle project.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function toggleProject(obj)
{
    var $obj       = $(obj);
    if($obj.length == 0) return false;

    var $projects  = $obj.closest('table').find('#projects');
    var $programs  = $obj.closest('table').find('#programs');
    var $programParams = $obj.closest('table').find('.programParams');
    if($obj.prop('checked'))
    {
        $('form .prj-no-exist').removeClass('hidden');
        $('form .prj-exist').addClass('hidden');
        $programParams.removeClass('hidden');
        $projects.attr('disabled', 'disabled');
    }
    else
    {
        $('form .prj-exist').removeClass('hidden');
        $('form .prj-no-exist').addClass('hidden');
        $programParams.addClass('hidden');
        $('#projectStatus').closest('tr').removeClass('hidden');
        $projects.removeAttr('disabled');

        if($('#newProgram0').prop('checked'))
        {
            $('form #newProgram0').prop('checked', false);
            toggleProgram($('form #newProgram0'));
        }

        getProjectByProgram(programs);
    }
}

/**
 * When there are no sprints for the selected product, hidden the project.
 *
 * @access public
 * @return void
 */
function hiddenProject()
{
    if($('[name^=sprints]:checked').length == 0)
    {
        $(".programParams input").attr('disabled' ,'disabled');
        $(".programParams select").attr('disabled' ,'disabled').trigger('chosen:updated');
        $('.programParams').hide();

        $(".projectName input").attr('disabled' ,'disabled');
        $(".projectName select").attr('disabled' ,'disabled').trigger('chosen:updated');
        $('.projectName').hide();
    }
    else
    {
        $(".projectName input").removeAttr('disabled');
        $(".projectName select").removeAttr('disabled').trigger('chosen:updated');
        $('.projectName').show();

        $(".programParams input").removeAttr('disabled');
        $(".programParams select").removeAttr('disabled').trigger('chosen:updated');
        $('.programParams').show();

        if($('#newProject0').is(':checked')) $('#projects').attr('disabled', 'disabled');
    }
}

/**
 * When the selected product already set program, the program name is fixed.
 *
 * @param  object $product
 * @access public
 * @return void
 */
function setProgramByProduct(product)
{
    var programID = product.attr('data-programid');
    $(':checkbox[data-productid]').each(function()
    {
        var currentProgramID = $(this).attr('data-programid');
        if(currentProgramID != programID)
        {
            var currentProductID = $(this).val();
            if(product.prop('checked'))
            {
                $(this).prop('checked', false);
                $(this).attr('disabled', 'disabled');
                $('[data-product=' + currentProductID + ']').prop('checked', false);
                $('[data-product=' + currentProductID + ']').attr('disabled', 'disabled');
            }
            else if($(':checkbox:checked[data-programid=' + programID + ']').length == 0)
            {
                $(this).removeAttr('disabled');
                $('[data-product=' + currentProductID + ']').removeAttr('disabled');
            }
        }
    });

    if(product.prop('checked') && programID != 0)
    {
        $('form #newProgram0').prop('checked', false);
        toggleProgram($('form #newProgram0'));
        $('form #newProgram0').attr('disabled', 'disabled');

        $('#programs').val(programID).trigger("chosen:updated");
        $('#programs').attr('disabled', 'disabled');
        $('#programID').val(programID);

        getProjectByProgram($('#programs'));
    }
    else
    {
        $('form #newProgram0').removeAttr('disabled');
        $('#programs').removeAttr('disabled');
        $('#programID').val('');
    }
}

/**
 * Set project status.
 *
 * @access public
 * @return void
 */
function setProjectStatus()
{
    var projectStatus = 'closed';
    $(':checkbox:checked[data-status]').each(function()
    {
        var status = $(this).attr('data-status');
        if(status == 'doing' || status == 'suspended')
        {
            projectStatus = 'doing';
            return false;
        }

        if(status == 'wait') projectStatus = 'wait';
    });
    if($(':checkbox:checked[data-status]').length == 0) projectStatus = 'wait';

    $('#projectStatus').val(projectStatus);
    $('#projectStatus').trigger('chosen:updated');

    setProgramStatus(projectStatus);
}

/**
 * Set program status.
 *
 * @param  string $projectStatus
 * @access public
 * @return void
 */
function setProgramStatus(projectStatus)
{
    var programStatus = 'wait';
    if(projectStatus != 'wait') programStatus = 'doing';
    if(projectStatus == 'closed') programStatus = 'closed';

    $('#programStatus').val(programStatus);
    $('#programStatus').trigger('chosen:updated');
}

/**
 * Set program begin time.
 *
 * @param  string $programBegin
 * @access public
 * @return void
 */
function setProgramBegin(programBegin)
{
    $(':checkbox:checked[data-begin]').each(function()
    {
        begin = $(this).attr('data-begin').substr(0, 10);
        if(begin == '0000-00-00') return true;

        if(begin < programBegin)
        {
            programBegin = begin;
            $('.programParams #begin').val(programBegin);
        }
    });

    setProjectStatus();
}

/*
 * Set program end time.
 *
 * @param  string $programEnd
 * @access public
 * @return void
 */
function setProgramEnd(programEnd)
{
    var length = $(':checkbox:checked[data-end]').length;
    if(length == 0)
    {
        $('.programParams #end').val('');
        return false;
    }

    $(':checkbox:checked[data-end]').each(function()
    {
        end = $(this).attr('data-end').substr(0, 10);
        if(end == '0000-00-00') return true;

        if(end > programEnd)
        {
            programEnd = end;
            $('.programParams #end').val(programEnd);
        }
    });
}

/**
 * Set the project PM when merge the sprint.
 *
 * @access public
 * @return void
 */
function setProjectPM()
{
    var PM = [];
    $(':checkbox:checked[data-pm]').each(function()
    {
        var PMName = $(this).attr('data-pm');
        PM[PMName] = PM[PMName] == undefined ? 0 : PM[PMName];
        PM[PMName] = PM[PMName] + 1;
    });
    PM.sort(function(el1, el2){return el2 - el1;});
    PMNameList = Object.keys(PM);
    PMNameList = PMNameList.filter(Boolean);
    $('#PM').val(PMNameList[0]).trigger("chosen:updated");
}

/*
 * Convert string to date.
 *
 * @param  string $dateString
 * @access public
 * @return void
 */
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

/**
 * Get program status.
 *
 * @param  string $objectType
 * @param  int    $objectID
 * @access public
 * @return void
 */
function getProgramStatus(objectType, objectID)
{
    var link = createLink('upgrade', 'ajaxGetProgramStatus', 'objectID=' + objectID);
    $.post(link, function(data)
    {
        if(objectType == 'program') $('#programStatus').val(data).trigger("chosen:updated");
        if(objectType == 'project') $('#projectStatus').val(data).trigger("chosen:updated");
    })
}
