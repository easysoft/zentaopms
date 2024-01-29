$(function()
{
    initFormData();
});

window.changeAllLines = function()
{
    $('input[name^=productLines]').prop('checked', $('#checkAllLines').prop('checked'));
    $('#checkAllProducts').prop('checked', $('#checkAllLines').prop('checked'));
    changeAllProducts();
}

window.changeAllProducts = function()
{
    $('input[name^=products]').prop('checked', $('#checkAllProducts').prop('checked'));

    $('input[name^=products]').each(function()
    {
        const lineID = $(this).data('line');
        if(lineID) checkGroupLines(lineID);
    });

    $('#checkAllSprints').prop('checked', $('#checkAllProducts').prop('checked'));
    changeAllSprints();
}

window.changeAllSprints = function()
{
    $('input[name^=sprints]').prop('checked', $('#checkAllSprints').prop('checked'));

    $('input[name^=sprints]').each(function()
    {
        const productID = $(this).data('product');
        const lineID    = $(this).data('line');
        checkGroupProducts(lineID, productID);
        if(lineID) checkGroupLines(lineID);
    });
    buildForm();
}

window.changeLines = function(event)
{
    checkAllLines();

    const lineID = $(event.target).val();
    $('input[id^=products-' + lineID + '-]').prop('checked', $(event.target).prop('checked'));
    checkAllProducts();
    $('input[id^=sprints-' + lineID + '-]').prop('checked', $(event.target).prop('checked'));
    checkAllSprints();
    buildForm();
}

window.changeProducts = function(event)
{
    checkAllProducts();

    const productID = $(event.target).val();
    const lineID    = $(event.target).data('line');
    if(lineID)
    {
        $('input[id^=sprints-' + lineID + '-' + productID + '-]').prop('checked', $(event.target).prop('checked'));
        checkGroupLines(lineID);
    }
    else
    {
        $('input[id^=sprints-' + productID + '-]').prop('checked', $(event.target).prop('checked'));
    }
    checkAllSprints();
    buildForm();
}

window.changeSprints = function(event)
{
    checkAllSprints();

    const productID = $(event.target).data('product');
    const lineID    = $(event.target).data('line');
    checkGroupProducts(lineID, productID);
    if(lineID) checkGroupLines(lineID);
    buildForm();
}

window.changeProjectType = function()
{
    const projectType = $('input[name=projectType]:checked').val();

    $('.programForm, .formTitle').toggleClass('hidden', projectType == 'project' && mode == 'light');
    $('.createProjectTip').toggleClass('hidden', projectType == 'project');
    $('.createExecutionTip').toggleClass('hidden', projectType == 'execution');
    $('[name=projectAcl]').closest('.check-list').toggleClass('hidden', projectType == 'project');
    $('[name=programAcl]').closest('.check-list').toggleClass('hidden', projectType == 'execution');
    $('.projectName').toggleClass('hidden', projectType == 'project');
    $('.projectStatus').toggleClass('hidden', projectType == 'project');

    if(projectType == 'project')
    {
        $('[name=projectAcl]').attr('disabled', 'disabled');
        $('[name=programAcl]').removeAttr('disabled');
    }

    if(projectType == 'execution')
    {
        $('[name=programAcl]').attr('disabled', 'disabled');
        $('[name=projectAcl]').removeAttr('disabled');
    }
}

window.changeNewProgram = function()
{
    const checkedNewProgram = $('input[name=newProgram]').prop('checked');

    $('#programs').toggleClass('hidden', checkedNewProgram);
    $('#programName').toggleClass('hidden', !checkedNewProgram);
    if(checkedNewProgram)
    {
        $('#programs').attr('disabled', 'disabled');
    }
    else
    {
        $('#programs').removeAttr('disabled');
    }
}

window.changeNewProject = function()
{
    const checkedNewProject = $('input[name=newProject]').prop('checked');

    $('#projects').toggleClass('hidden', checkedNewProject);
    $('#projectName').toggleClass('hidden', !checkedNewProject);

    if(checkedNewProject)
    {
        $('#projects').attr('disabled', 'disabled');
    }
    else
    {
        $('#projects').removeAttr('disabled');
    }
}

window.changeNewLine = function()
{
    const checkedNewLine = $('input[name=newLine]').prop('checked');

    $('#lines').toggleClass('hidden', checkedNewLine);
    $('#lineName').toggleClass('hidden', !checkedNewLine);
    if(checkedNewLine)
    {
        $('#lines').attr('disabled', 'disabled');
    }
    else
    {
        $('#lines').removeAttr('disabled');
    }
}

window.changePrograms = function()
{
    const programID = $('#programs').zui('picker').$.state.value;

    setStatus('program', programID);
    getProjectByProgram(programID);
    getLineByProgram(programID);
}

window.changeLongTime = function()
{
    const checkedLongTime = $('input[name=longTime]').prop('checked');
    if(checkedLongTime)
    {
        $('#end').datePicker({disabled: true});
    }
    else
    {
        $('#end').datePicker({disabled: false});
    }
}

window.clickSubmit = function()
{
    if(type == 'productline')
    {
        var checkedProductCount = $("input[name^='products']:checked").length;
        if(checkedProductCount <= 0)
        {
            zui.Modal.alert(errorNoProduct);
            return false;
        }
    }
    else if(type == 'product')
    {
        var checkedProductCount = $("input[name^='products']:checked").length;
        if(checkedProductCount <= 0)
        {
            zui.Modal.alert(errorNoProduct);
            return false;
        }

        var executionCount        = 0;
        var checkedExecutionCount = 0;
        $("input[name^='products']:checked").each(function()
        {
            var productID = $(this).val()

            executionCount        += $("[data-product='" + productID + "']").length;
            checkedExecutionCount += $("[data-product='" + productID + "']:checked").length;
        });

        if(executionCount !== 0 && checkedExecutionCount === 0)
        {
            zui.Modal.alert(errorNoExecution);
            return false;
        }
    }
    else
    {
        var checkedExecutionCount = $("input[name^='sprints']:checked").length;
        if(checkedExecutionCount === 0)
        {
            zui.Modal.alert(errorNoExecution);
            return false;
        }
    }
}

window.checkAllLines = function()
{
    let allChecked = true;
    $('input[name^=productLines]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#checkAllLines').prop('checked', allChecked);
}

window.checkAllProducts = function()
{
    let allChecked = true;
    $('input[name^=products]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#checkAllProducts').prop('checked', allChecked);
}

window.checkAllSprints = function()
{
    let allChecked = true;
    $('input[name^=sprints]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#checkAllSprints').prop('checked', allChecked);
}

window.checkGroupLines = function(lineID)
{
    let allChecked = true;
    $('input[id^=products-' + lineID + '-]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#productLines' + lineID).prop('checked', allChecked);
    checkAllLines();
}

window.checkGroupProducts = function(lineID, productID)
{
    let allChecked = true;
    if(lineID)
    {
        $('input[id^=sprints-'+ lineID +'-' + productID + '-]').each(function()
        {
            if(!$(this).prop('checked')) allChecked = false;
        });
        $('#products-'+ lineID +'-' + productID).prop('checked', allChecked);
    }
    else
    {
        $('input[id^=sprints-' + productID + '-]').each(function()
        {
            if(!$(this).prop('checked')) allChecked = false;
        });
        $('#products' + productID).prop('checked', allChecked);
    }
    checkAllProducts();
}

window.buildForm = function()
{
    initFormData();
    setProgramName();
    setProgramBegin();
    setProgramEnd();
    setProjectStatus();
    setProjectPM();
}

window.setProgramName = function()
{
    const programID = $('input[name^=products]:checked').data('programid');
    if(programID)
    {
        $('.programName').not('.hidden').find('input[name=newProgram]').prop('checked', false);
        changeNewProgram();
        $('input[name=newProgram]').attr('disabled', 'disabled');
        $('#programs').zui('picker').$.changeState({value: programID.toString()});
        $("#programs").zui('picker').render({disabled: true});
        $('#programID').val(programID);
        getProjectByProgram(programID);
    }
    else
    {
        $('input[name=newProgram]').removeAttr('disabled');
        $("#programs").zui('picker').render({disabled: false});
        $('#programID').val('');
    }
}

window.setProgramBegin = function()
{
    let minBegin = today;
    $('input[type=checkbox][data-begin]:checked').each(function()
    {
        begin = $(this).attr('data-begin').substr(0, 10);
        if(begin == '0000-00-00') return;
        if(begin < minBegin) minBegin = begin;
    });
    $('#begin').zui('datePicker').$.changeState({value: minBegin});
}

window.setProgramEnd = function()
{
    let minEnd = '';
    $('input[type=checkbox][data-end]:checked').each(function()
    {
        end = $(this).attr('data-end').substr(0, 10);
        if(end == '0000-00-00') return true;
        if(end > minEnd) minEnd = end;
    });
    $('#end').zui('datePicker').$.changeState({value: minEnd});
}

window.setProjectStatus = function()
{
    var projectStatus = 'wait';
    $('input[type=checkbox][data-status]:checked').each(function()
    {
        var status = $(this).attr('data-status');
        if(status == 'doing' || status == 'suspended')
        {
            projectStatus = 'doing';
            return false;
        }
    });

    $('#projectStatus').zui('picker').$.changeState({value: projectStatus})

    setProgramStatus(projectStatus);
}

window.setProjectPM = function()
{
    let PM = [];
    $('input[type=checkbox][data-pm]:checked').each(function()
    {
        let PMName = $(this).attr('data-pm');
        PM[PMName] = PM[PMName] == undefined ? 0 : PM[PMName];
        PM[PMName] = PM[PMName] + 1;
    });
    PM.sort(function(el1, el2){return el2 - el1;});
    PMNameList = Object.keys(PM);
    PMNameList = PMNameList.filter(Boolean);

    $('#PM').zui('picker').$.changeState({value: PMNameList[0]});
}

window.setProgramStatus = function(projectStatus)
{
    var programStatus = 'wait';
    if(projectStatus != 'wait')   programStatus = 'doing';
    if(projectStatus == 'closed') programStatus = 'closed';

    $('#programStatus').zui('picker').$.changeState({value: programStatus});
}

window.initFormData = function()
{
    $('#programBox').toggleClass('hidden', $('[name^=sprints]:checked').length == 0 && mode == 'light');
    $('.programParams').toggleClass('hidden', $('[name^=sprints]:checked').length == 0);
    $('.programForm').toggleClass('hidden', $('[name^=sprints]:checked').length != 0 && projectType == 'project' && mode == 'light');

    if($('[name^=sprints]:checked').length == 0)
    {
        $(".programParams input").attr('disabled' ,'disabled');
        $(".programParams .picker-field").zui('picker').render({disabled: true});

        $(".projectName input").attr('disabled' ,'disabled');
        $(".projectName .picker-field").zui('picker').render({disabled: true});
    }
    else
    {
        $(".programParams input").removeAttr('disabled');
        $(".programParams .picker-field").zui('picker').render({disabled: false});

        $(".projectName input").removeAttr('disabled');
        $(".projectName .picker-field").zui('picker').render({disabled: false});

        changeProjectType();

        if(mode == 'light')
        {
            $('.programName').find('input[name=newProgram]').prop('checked', false);
            changeNewProgram();
        }
    }
}

window.setStatus = function(objectType, objectID)
{
    const link = $.createLink('upgrade', 'ajaxGetProgramStatus', 'objectID=' + objectID);
    $.get(link, function(data)
    {
        if(objectType == 'program') $('#programStatus').zui('picker').$.changeState({value: data});
        if(objectType == 'project') $('#projectStatus').zui('picker').$.changeState({value: data});
    })
}

window.getProjectByProgram = function(programID)
{
    const link = $.createLink('upgrade', 'ajaxGetProjectPairsByProgram', 'programID=' + programID);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        $('#projects').zui('picker').render({items: data.projects});
    })
}

window.getLineByProgram = function(programID)
{
    const link = $.createLink('upgrade', 'ajaxGetLinesPairsByProgram', 'programID=' + programID);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        $('#lines').zui('picker').render({items: data.lines});
    })
}
