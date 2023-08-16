function switchDateTodo(switcher)
{
    if(switcher.checked)
    {
        $('#date').attr('disabled','disabled');
    }
    else
    {
        $('#date').removeAttr('disabled');
    }
}
/**
 * Load data.
 * @param  type        $type        Type of selected todo.
 * @param  id          $id          ID of selected todo.
 * @param  defaultType $defaultType Default type of selected todo.
 * @param  idvalue     $idvalue     ID of the closed todo type.
 * @access public
 * @return void
 */
function loadList(type, id, defaultType, idvalue)
{
    if(id)
    {
        divClass = '.nameBox' + id;
        divID    = '#nameBox' + id;
    }
    else
    {
        divClass   = '.nameBox';
        divID      = '#nameBox';
    }

    id = id ? id : '';
    var param = 'userID=' + userID + '&id=' + id;
    if(type == 'task') param += '&status=wait,doing';
    if(type == 'risk') param += '&status=active,hangup';
    if(type == defaultType && idvalue != 0) param += '&idvalue=' + idvalue;

    if(moduleList.indexOf(type) !== -1)
    {
        link = createLink(type, objectsMethod[type], param);

        $.get(link, function(data, status)
        {
            if(data.length != 0)
            {
                $(divClass).html(data).find('select').chosen();
                if(config.currentMethod == 'edit' || type == 'feedback') $(divClass).find('select').val(idvalue).trigger('chosen:updated');
                if($(divClass + " select").val() == null) $(divClass + " select").attr("data-placeholder", noOptions.replace("%s", chosenType[type])).trigger('chosen:updated');
            }
            else
            {
                $(divClass).html("<select id="+ type +" class='form-control'></select>").find('select').chosen();
            }
        });
    }
    else
    {
        $(divClass).html($(divID).html());
    }


    if(typeof(nameBoxLabel) != "undefined")
    {
        if(type == 'custom' || (typeof(vision) != "undefined" && vision == 'rnd'))
        {
            $('#nameBoxLabel').text(nameBoxLabel.custom);
        }
        else
        {
            $('#nameBoxLabel').text(nameBoxLabel.idvalue);
        }
    }
}

function selectNext()
{
    $("#end ")[0].selectedIndex = $("#begin ")[0].selectedIndex + 3;
    $('#end').trigger('chosen:updated');
}

function setBeginsAndEnds(i, beginOrEnd)
{
    if(typeof i == 'undefined')
    {
        for(j = 0; j < batchCreateNum; j++)
        {
            if(j != 0) $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
            $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
            $("#begins" + j).trigger('chosen:updated');
            $("#ends" + j).trigger('chosen:updated');
        }
    }
    else
    {
        if(beginOrEnd == 'begin')
        {
            $("#ends" + i)[0].selectedIndex = $("#begins" + i)[0].selectedIndex + 3;
            $("#ends" + i).trigger('chosen:updated');
        }

        if(typeof batchCreateNum != 'undefined')
        {
            for(j = i+1; j < batchCreateNum; j++)
            {
                $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
                $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
                $("#begins" + j).trigger('chosen:updated');
                $("#ends" + j).trigger('chosen:updated');
            }
        }
    }
}

function switchTimeList(number)
{
    if($('#switchTime' + number).prop('checked'))
    {
        $('#begins' + number).attr('disabled', 'disabled').trigger('chosen:updated');
        $('#ends' + number).attr('disabled', 'disabled').trigger('chosen:updated');
    }
    else
    {
        $('#begins' + number).removeAttr('disabled').trigger('chosen:updated');
        $('#ends' + number).removeAttr('disabled').trigger('chosen:updated');
    }
}

function switchDateFeature(switcher)
{
    if(switcher.checked)
    {
        $('#begin').attr('disabled','disabled').trigger('chosen:updated');
        $('#end').attr('disabled','disabled').trigger('chosen:updated');
    }
    else
    {
        $('#begin').removeAttr('disabled').trigger('chosen:updated');
        $('#end').removeAttr('disabled').trigger('chosen:updated');
    }
}

/**
 * Show specified date.
 *
 * @param  switcher $switcher
 * @access public
 * @return void
 */
function showSpecifiedDate(switcher)
{
    if(switcher.checked)
    {
        $('#everyInput').attr('disabled','disabled');
        $('.specify').removeClass('hidden');
        $('.every').addClass('hidden')
        $('#configEvery').removeAttr('checked');
    }
}

/**
 * Show every.
 *
 * @param  switcher $switcher
 * @access public
 * @return void
 */
function showEvery(switcher)
{
    if(switcher.checked)
    {
        $('#everyInput').removeAttr('disabled');
        $('.specify').addClass('hidden');
        $('.every').removeClass('hidden');
        $('#configSpecify').removeAttr('checked');
        $('#cycleYear').removeAttr('checked');
        $('#configEvery').removeAttr('checked');
    }
}

/**
 * Set days by specified month.
 *
 * @param  int $specifiedMonth
 * @access public
 * @return void
 */
function setDays(specifiedMonth)
{
    /* Get last day in specified month. */
    var date = new Date();
    date.setMonth(specifiedMonth);
    var month = date.getMonth() + 1;
    date.setMonth(month);
    date.setDate(0);
    var specifiedMonthLastDay = date.getDate();

    $('#specifiedDay').empty('');
    for(var i = 1; i <= specifiedMonthLastDay; i++)
    {
        html = "<option value='" + i + "' title='" + i + "' data-keys='" + i + "'>" + i + "</option>";

        $('#specifiedDay').append(html);
    }
}
