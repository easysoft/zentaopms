$(function()
{
    programBegin = $('.programParams #begin').val();
    programEnd   = $('.programParams #end').val();
    setProgramBegin(programBegin);
    setProgramEnd(programEnd);
    setProjectPM();

    /* Define drag to select relevant parameters. */
    var options = {
        selector: 'input',
        listenClick: false,
        select: function(e)
        {
            $('[data-id=' + e.id + ']').prop('checked', true);

            var lineID         = $('.nav li.currentPage').attr('lineid');
            var checkedLines   = true;
            var checkedProduct = true;
            var checkedProject = true;
            var type           = $('[data-id=' + e.id + ']').attr('name');

            /* Select the product line of the current page. */
            if(typeof(type) != 'undefined' && type.indexOf('productLines') != -1)
            {
                if($('.nav li.currentPage').find('[id^=productLines]').prop('checked'))
                {
                    var lineID = $('.nav li.currentPage').attr('lineid');
                    $('#checkAllProducts').prop('checked', true);
                    $('#checkAllProjects').prop('checked', true);
                    $("[id^='products\[" + lineID + "\]']").prop('checked', true);
                    $("[id^='sprints\[" + lineID + "\]'").prop('checked', true);
                }
            }

            /* All products selected. */
            if(typeof(type) != 'undefined' && type.indexOf('products') != -1)
            {
                if($('[id^=productLines]').length > 0)
                {
                    var checkedProduct = isSelectAll(lineID, 'product');
                    var productID      = $('[data-id=' + e.id  + ']').val();
                    $('[data-product=' + productID +']').prop('checked', true);
                    if(!$("[id^='productLines\[" + lineID + "\]']").prop('checked')) $("[id^='productLines\[" + lineID + "\]']").prop('checked', true);
                    var checkedProject = isSelectAll(lineID, 'project');
                }
                else
                {
                    var checkedProduct = isSelectAll(0, 'product');
                    var productID      = $('[data-id=' + e.id  + ']').val();
                    $('[data-product=' + productID +']').prop('checked', true);
                    var checkedProject = isSelectAll(0, 'project');
                }
                $('#checkAllProducts').prop('checked', checkedProduct);
                $('#checkAllProjects').prop('checked', checkedProject);
            }

            /* All projects selected. */
            if(typeof(type) != 'undefined' && type.indexOf('sprints') != -1)
            {
                if($('[id^=productLines]').length > 0)
                {
                    var checkedProject = isSelectAll(lineID, 'project');
                    var productID      = $('[data-id=' + e.id + ']').attr('data-product');
                    if(productID && $('[data-productid=' + productID + ']').length > 0 && !$('[data-productid=' + productID + ']').prop('checked'))
                    {
                        $('[data-productid=' + productID + ']').prop('checked', true);
                        if(!$("[id^='productLines\[" + lineID + "\]']").prop('checked')) $("[id^='productLines\[" + lineID + "\]']").prop('checked', true);
                    }
                }
                else if($('[id^=products]').length > 0)
                {
                    var checkedProject = isSelectAll(0, 'project');
                    var productID      = $('[data-id=' + e.id + ']').attr('data-product');
                    if(productID && $('[data-productid=' + productID + ']').length > 0 && !$('[data-productid=' + productID + ']').prop('checked')) $('[data-productid=' + productID + ']').prop('checked', true);
                }
                else
                {
                    var checkedProject = isSelectAll(0, 'project');
                }

                var checkedProduct = isSelectAll(lineID, 'product');
                $('#checkAllProjects').prop('checked', checkedProject);
                $('#checkAllProducts').prop('checked', checkedProduct);
            }

            /* All product lines selected. */
            if(typeof(type) != 'undefined' && type.indexOf('productLines') != -1)
            {
                $('[name^=productLines]').each(function()
                {
                    if(!$(this).prop('checked'))
                    {
                        checkedLines = false;
                    }
                    else
                    {
                        var productLine = $(this).val();
                        $('[lineid=' + productLine + ']').addClass('active');
                        $("[id^='products\[" + productLine + "\]']").prop('checked', true);
                        $("[id^='sprints\[" + productLine + "\]']").prop('checked', true);
                    }
                })
                $('#checkAllLines').prop('checked', checkedLines);
            }

            var checkAllLines = isSelectAll(0, 'line');
            $("[id='checkAllLines']").prop('checked', checkAllLines);

            setProgramBegin(programBegin);
            setProgramEnd(programEnd);
            setProjectPM();

            /* If the project is checked, the relevant form will be displayed according to the selected mode. */
            hiddenProject();
        }
    };

    /* Initialize the drag selected. */
    $('#lineBox').selectable(options);
    $('#source').selectable(options);

    $('.side-col .cell').height($('.side-col').height() - 20);
    $('#source .cell').height($('#source').height());
    $('#programBox .cell').height($('#programBox').height() - 20);

    /* Select all product line events. */
    $('#checkAllLines').click(function()
    {
        var checked = true;
        if($(this).is(':checked'))
        {
            $('.main-row .side-col .nav li').addClass('active');
            $('#programName').val($('.main-row .side-col .nav li.currentPage div a').text());
        }
        else
        {
            checked = false;
            $('.main-row .side-col .nav li').removeClass('active');
            $('#programName').val('');
        }

        $('#checkAllProducts').prop('checked', checked);
        $('#checkAllProjects').prop('checked', checked);
        $('[name^=productLines]').prop('checked', checked);
        $('[name^=products]').prop('checked', checked);
        $('[name^=sprints]').prop('checked', checked);

        setProgramBegin(programBegin);
        setProgramEnd(programEnd);

        /* If the project is checked, the relevant form will be displayed according to the selected mode. */
        hiddenProject();
    })

    /* Select all product events. */
    $('#checkAllProducts').click(function()
    {
        var lineID  = $('li.currentPage').attr('lineid');
        var checked = true;
        if($(this).is(':checked'))
        {
            $('#programName').val($('.main-row .side-col .nav li.currentPage div a').text());
            if($('[id^=productLines]').length > 0)
            {
                var projectNum = $("[id^='sprints\[" + lineID + "\]']").length;
            }
            else
            {
                var projectNum = $("[id^='sprints']").length;
            }
            if(projectNum !== 0) $('#checkAllProjects').prop('checked', true);
        }
        else
        {
            checked = false;
            $('#checkAllProjects').prop('checked', false);
            $('form #newProgram0').removeAttr('disabled');
            $('#programs').removeAttr('disabled');
            $('#programID').val('');
            $('#programName').val('');
        }

        if($('[id^=productLines]').length > 0)
        {
            $('[data-line=' + lineID + ']').prop('checked', checked);
            $("[id^='productLines\[" + lineID + "\]']").prop('checked', checked);
        }
        else
        {
            $('[name^=products]').prop('checked', checked);
            $('[name^=sprints]').prop('checked', checked);

            if(checked)
            {
                $('[name^=products]').each(function()
                {
                    if($(this).prop('checked'))
                    {
                        setProgramByProduct($(this));
                        return false;
                    }
                })
            }
        }

        var checkAllLines = isSelectAll(0, 'line');
        $("[id='checkAllLines']").prop('checked', checkAllLines);

        setProgramBegin(programBegin);
        setProgramEnd(programEnd);

        hiddenProject();
    })

    /* Select all project events. */
    $('#checkAllProjects').click(function()
    {
        var lineID  = $('li.currentPage').attr('lineid');
        var checked = true;
        if($(this).is(':checked'))
        {
            $('#programName').val($('.main-row .side-col .nav li.currentPage div a').text());
        }
        else
        {
            checked = false;
            $('#programName').val('');
        }

        $('#checkAllProducts').prop('checked', checked);
        if($('[id^=productLines]').length > 0)
        {
            $('[data-line=' + lineID + ']').prop('checked', checked);
            $("[id^='productLines\[" + lineID + "\]']").prop('checked', checked);
        }
        else
        {
            $('[name^=products]').prop('checked', checked);
            $('[name^=sprints]').prop('checked', checked);

            if(checked)
            {
                $('[name^=products]').each(function()
                {
                    if($(this).prop('checked'))
                    {
                        setProgramByProduct($(this));
                        return false;
                    }
                })
            }
        }

        var checkAllLines = isSelectAll(0, 'line');
        $("[id='checkAllLines']").prop('checked', checkAllLines);

        setProgramBegin(programBegin);
        setProgramEnd(programEnd);

        hiddenProject();
    })

    /* Select a product line event. */
    $('[name^=productLines]').change(function()
    {
        var value  = $(this).val();
        var hidden = $('#line' + value).is(':hidden');
        if($(this).prop('checked'))
        {
            if(!hidden)
            {
                $('#checkAllProducts').prop('checked', true);
                $('#checkAllProjects').prop('checked', true);
            }
            $('[data-line=' + value + ']').prop('checked', true);
            $('[lineid=' + value + ']').addClass('active');
            $('#programName').val($("[lineid='" + value + "']").find('a').text());
        }
        else
        {
            $('#checkAllLines').prop('checked', false);
            if(!hidden)
            {
                $('#checkAllProducts').prop('checked', false);
                $('#checkAllProjects').prop('checked', false);
            }
            $('[data-line=' + value + ']').prop('checked', false);
            $('[lineid=' + value + ']').removeClass('active');
            $('#programName').val('');
            $('#programStatus').val('wait');
            $('#programStatus').trigger('chosen:updated');
        }

        /* Determine whether all product line buttons are selected. */
        var checked = isSelectAll(0, 'line');
        $('#checkAllLines').prop('checked', checked);

        setProgramBegin(programBegin);
        setProgramEnd(programEnd);

        /* If the project is checked, the relevant form will be displayed according to the selected mode. */
        hiddenProject();
    })

    $('[name^=lines]').change(function()
    {
        value = $(this).val();
        if($(this).prop('checked'))
        {
            $('[data-line=' + value + ']').prop('checked', true);
        }
        else
        {
            $('[data-line=' + value + ']').prop('checked', false);
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

        var currentLine = $(this).closest('li').attr('lineid');

        /* Active current li and remove active before li. */
        $(this).closest('li').addClass('active');
        $(this).closest('ul').find('li').removeClass('currentPage');
        $(this).closest('li').addClass('currentPage');

        $('[id^=productLines]').each(function()
        {
            var lineID = $(this).val();
            if($("[id^='productLines\[" + lineID +"\]'").prop('checked') || lineID == currentLine)
            {
                $(this).closest('li').addClass('active');
            }
            else
            {
                $(this).closest('li').removeClass('currentPage');
                $(this).closest('li').removeClass('active');
            }
        })

        /* Show current data and hide before data. */
        var target = $(this).attr('data-target');
        $('.lineBox').addClass('hidden');
        $(target).removeClass('hidden');

        /* Replace program name. */
        if(!$('#programName').val() && $("[id^='productLines\[" + currentLine +"\]'").prop('checked')) $('#programName').val($(this).text());

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

        /* Determine whether products and projects are selected. */
        if($(this).closest("li").find('[id^=productLines]').prop('checked'))
        {
            var productLine    = $('.nav li.currentPage').attr('lineid');
            var checkedProduct = isSelectAll(productLine, 'product');
            var checkedProject = isSelectAll(productLine, 'project');
            $('#checkAllProducts').prop('checked', checkedProduct);
            $('#checkAllProjects').prop('checked', checkedProject);
        }
        else
        {
            $('#checkAllProducts').prop('checked', false);
            $('#checkAllProjects').prop('checked', false);
        }

        /* Determines whether to display an project related form control. */
        hiddenProject();
    })

    $('[name^=products]').change(function()
    {
        setProgramByProduct($(this));

        var checked     = true;
        var checkedLine = true;
        var lineID      = $(this).attr('data-line');
        if($('[id^=productLines]').length > 0)
        {
            var productNum        = $("[id^='products\[" + lineID + "\]'").length;
            var checkedProductNum = $("[id^='products\[" + lineID + "\]']:checked").length;

            if(productNum > checkedProductNum) checked = false;
            if(checkedProductNum > 0) $('[lineid=' + lineID + ']').addClass('active');
            if(checkedProductNum == 0)
            {
                $('[lineid=' + lineID + ']').removeClass('active');
                checkedLine = false;
            }
            $("[id^='productLines\[" + lineID + "\]']").prop('checked', checkedLine);

            var checkAllLines = isSelectAll(0, 'line');
            $("[id='checkAllLines']").prop('checked', checkAllLines);
        }
        else if($('[id^=products]').length > 0)
        {
            checked = isSelectAll(0, 'product');
        }

        $('#checkAllProducts').prop('checked', checked);

        value = $(this).val();
        if($(this).prop('checked'))
        {
            $('[data-product=' + value + ']').prop('checked', true)

            if(lineID && $('[data-lineid=' + lineID + ']').length > 0 && !$('[data-lineid=' + lineID + ']').prop('checked')) $('[data-lineid=' + lineID + ']').prop('checked', true);

            $('#programName').val($("[lineid='" + lineID + "']").find('a').text());
        }
        else
        {
            $('[data-product=' + value + ']').prop('checked', false)
            $('#programName').val('');
        }

        $('#programStatus').val('wait');
        $('#programStatus').trigger('chosen:updated');

        var checkedProject = true;
        if($('[id^=productLines]').length > 0)
        {
            checkedProject = isSelectAll(lineID, 'project');
        }
        else if($('[id^=products]').length > 0)
        {
            checkedProject = isSelectAll(0, 'project');
        }
        $('#checkAllProjects').prop('checked', checkedProject);

        setProgramBegin(programBegin);
        setProgramEnd(programEnd);
        setProjectPM();

        hiddenProject();
    })

    $('[name^=sprints]').change(function()
    {
        var lineID = $(this).attr('data-line');
        if($(this).prop('checked'))
        {
            if(lineID && $('[data-lineid=' + lineID + ']').length > 0 && !$('[data-lineid=' + lineID + ']').prop('checked')) $('[data-lineid=' + lineID + ']').prop('checked', true);

            var productID = $(this).attr('data-product');
            if(productID && $('[data-productid=' + productID + ']').length > 0 && !$('[data-productid=' + productID + ']').prop('checked')) $('[data-productid=' + productID + ']').prop('checked', true);

            $('#programName').val($("[lineid='" + lineID + "']").find('a').text());
            setProgramByProduct($(':checkbox[data-productid=' + productID + ']'));
        }

        var checked        = true;
        var checkedProduct = true;
        if($('[id^=productLines]').length > 0)
        {
            var lineID            = $(this).attr('data-line');
            var checkedProductNum = $("[id^='products\[" + lineID + "\]']:checked").length;
            var checkedLine       = true;

            checked        = isSelectAll(lineID, 'project');
            checkedProduct = isSelectAll(lineID, 'product');

            if(checkedProductNum > 0) $('[lineid=' + lineID + ']').addClass('active');
            if(checkedProductNum == 0)
            {
                $('[lineid=' + lineID + ']').removeClass('active');
                checkedLine = false;
            }
            $("[id^='productLines\["+ lineID +"\]").prop('checked', checkedLine);

            var checkAllLines = isSelectAll(0, 'line');
            $("[id='checkAllLines']").prop('checked', checkAllLines);
        }
        else if($('[id^=products]').length > 0)
        {
            checked        = isSelectAll(0, 'project');
            checkedProduct = isSelectAll(0, 'product');
        }
        else if($('[id^=sprints]').length > 0)
        {
            checked = isSelectAll(0, 'project');
        }

        $('#checkAllProjects').prop('checked', checked);
        $('#checkAllProducts').prop('checked', checkedProduct);

        setProgramBegin(programBegin);
        setProgramEnd(programEnd);
        setProjectPM();
        toggleProgram();

        hiddenProject();
    })

    toggleProgram($('form #newProgram0'));
    toggleProject($('form #newProject0'));
    toggleProject($('form #newLine0'));

    hiddenProject();

    /* Toggles data migration mode events. */
    $('input[name="projectType"]').change(function()
    {
        $('.programForm').show();
        $('.createProjectTip').toggleClass('hidden');
        $('.createExecutionTip').toggleClass('hidden');
        $('.projectName').toggleClass('hidden');
        $('.projectAcl').toggleClass('hidden');
        $('.programAcl').toggleClass('hidden');
        $('.projectStatus').toggleClass('hidden');

        if($(this).val() == 'project')
        {
            if(mode == 'light') $('.programForm').hide();
            $('[name=projectAcl]').attr('disabled', 'disabled');
            $('[name=programAcl]').removeAttr('disabled');
        }

        if($(this).val() == 'execution')
        {
            $('[name=programAcl]').attr('disabled', 'disabled');
            $('[name=projectAcl]').removeAttr('disabled');
        }
    })

    /* Hide project information. */
    var projectType = $('input[name="projectType"]:checked').val();
    if(projectType == 'project')
    {
        $('.projectName').addClass('hidden');
        $('.projectAcl').addClass('hidden');
        $('.programAcl').removeClass('hidden');
        $('.projectStatus').addClass('hidden');
        $('[name=projectAcl]').attr('disabled', 'disabled');
        $('[name=programAcl]').removeAttr('disabled');
    }

    if(projectType == 'execution')
    {
        $('.projectName').removeClass('hidden');
        $('.programAcl').addClass('hidden');
        $('.projectAcl').removeClass('hidden');
        $('.projectStatus').removeClass('hidden');
        $('[name=programAcl]').attr('disabled', 'disabled');
        $('[name=projectAcl]').removeAttr('disabled');
    }

    $('#submit').click(function()
    {
        if(type == 'productline')
        {
            var checkedProductCount = $("input[name^='products']:checked").length;
            if(checkedProductCount <= 0)
            {
                alert(errorNoProduct);
                return false;
            }
        }
        else if(type == 'product')
        {
            var checkedProductCount = $("input[name^='products']:checked").length;
            if(checkedProductCount <= 0)
            {
                alert(errorNoProduct);
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
                alert(errorNoExecution);
                return false;
            }
        }
        else
        {
            var checkedExecutionCount = $("input[name^='sprints']:checked").length;
            if(checkedExecutionCount === 0)
            {
                alert(errorNoExecution);
                return false;
            }
        }
    })
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
        $('#programStatus').val('wait');
        $('#programStatus').trigger("chosen:updated");

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

    var projectType = $('input[name="projectType"]:checked').val();
    if(projectType == 'project')
    {
        $('.projectStatus').addClass('hidden');
    }
    if(projectType == 'execution')
    {
        $('.projectStatus').removeClass('hidden');
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
    $('#programBox').show();
    if($('[name^=sprints]:checked').length == 0)
    {
        if(mode == 'light') $('#programBox').hide();
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

        $('.programForm').show();
        var projectType = $('input[name="projectType"]:checked').val();
        if(projectType == 'project')
        {
            $('.projectName').addClass('hidden');
            $('.projectAcl').addClass('hidden');
            $('.programAcl').removeClass('hidden');
            $('.projectStatus').addClass('hidden');
            $('[name=projectAcl]').attr('disabled', 'disabled');
            $('[name=programAcl]').removeAttr('disabled');
            if(mode == 'light') $('.programForm').hide();
        }

        if(projectType == 'execution')
        {
            $('.projectName').removeClass('hidden');
            $('.projectAcl').removeClass('hidden');
            $('.programAcl').addClass('hidden');
            $('.projectStatus').removeClass('hidden');
            $('[name=programAcl]').attr('disabled', 'disabled');
            $('[name=projectAcl]').removeAttr('disabled');
        }

        if(mode == 'light')
        {
            $('form #newProgram0').prop('checked', false);
            toggleProgram($('form #newProgram0'));
        }
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
    if(product.length == 0) return;

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
                $('#checkAllProducts').attr('disabled', 'disabled');
                $('#checkAllProjects').attr('disabled', 'disabled');
                $('[data-product=' + currentProductID + ']').prop('checked', false);
                $('[data-product=' + currentProductID + ']').attr('disabled', 'disabled');
            }
            else if($(':checkbox:checked[data-programid=' + programID + ']').length == 0)
            {
                $(this).removeAttr('disabled');
                $('#checkAllProducts').removeAttr('disabled');
                $('#checkAllProjects').removeAttr('disabled');
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
    else if(programID && $(':checkbox:checked[data-programid=' + programID + ']').length == 0)
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

/**
 * Checked all objects.
 *
 * @param  int    lineID
 * @param  string type
 * @access public
 * @return bool
 */
function isSelectAll(lineID = 0, type = 'product')
{
    var checked = true;
    if(lineID)
    {
        if(type == 'project')
        {
            var objectNum        = $("[id^='sprints\[" + lineID + "\]']").length;
            var checkedObjectNum = $("[id^='sprints\[" + lineID + "\]']:checked").length;
        }
        else if(type == 'product')
        {
            var objectNum        = $("[id^='products\[" + lineID + "\]']").length;
            var checkedObjectNum = $("[id^='products\[" + lineID + "\]']:checked").length;
        }
    }
    else
    {
        if(type == 'project')
        {
            var objectNum        = $("[id^='sprints']").length;
            var checkedObjectNum = $("[id^='sprints']:checked").length;
        }
        else if(type == 'product')
        {
            var objectNum        = $("[id^='products']").length;
            var checkedObjectNum = $("[id^='products']:checked").length;
        }
        else if(type = 'line')
        {
            var objectNum        = $("[id^='productLines'").length;
            var checkedObjectNum = $("[id^='productLines']:checked").length;
        }
    }

    if(objectNum > checkedObjectNum || objectNum == 0) checked = false;
    return checked;
}
