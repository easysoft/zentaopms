/**
 * 切换日期选择的禁用状态。
 * Toggle the disabled state of the date select.
 *
 * @param  object switcher
 * @return void
 */
function toggleDateTodo(switcher)
{
    $('#date').prop('disabled', switcher.checked);
}

/**
 * Load data.
 * @param  type        $type        Type of selected todo.
 * @param  id          $id          ID of selected todo.
 * @param  objectID    $objectID    ID of the closed todo type.
 * @return void
 */
function loadList(type, id, objectID)
{
    if(id)
    {
        divClass = '.name-box' + id;
        divID    = '#nameBox' + id;
    }
    else
    {
        divClass = '.name-box';
        divID    = '#nameBox';
    }
    id = id ? id : '';
    var param = 'userID=' + userID + '&id=' + id;
    if(type == "task") param += '&status=wait,doing';
    if(defaultType && type == defaultType && objectID != 0) param += '&objectID=' + objectID;

    if(moduleList.indexOf(type) !== -1)
    {
        link = '/index.php?m=' + type + '&f=' + objectsMethod[type] + '&' + param + '&t=html';
        $.get(link, function(data, status)
        {
            if(data.length != 0)
            {
                $(divClass).find('#nameInputBox').html(data).find('select').chosen();
                if(config.currentMethod == 'edit' || type == 'feedback') $(divClass).find('select').val(objectID).trigger('chosen:updated');
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

    if(nameBoxLabel) return;
    var formLabel = type == 'custom' ||(vision && vision == 'rnd') ?  nameBoxLabel.custom : nameBoxLabel.objectID;
    $('#nameBox .form-label').text(formLabel);
}

/**
 * 选择开始时间后，自动给出默认终止时间。
 * After selecting the start time, the default end time is automatically given.
 *
 * @return viod
 */
function selectNext()
{
    $("#end ")[0].selectedIndex = $("#begin ")[0].selectedIndex + 3;
    $('#end').trigger('chosen:updated');
}

function setBeginsAndEnds(i, beginOrEnd)
{
    if(!i)
    {
        for(j = 0; j < batchCreateNum; j++)
        {
            if(j != 0) $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
            $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
            $("#begins" + j, "#ends" + j).trigger('chosen:updated');
        }
    }
    else
    {
        if(beginOrEnd == 'begin')
        {
            $("#ends" + i)[0].selectedIndex = $("#begins" + i)[0].selectedIndex + 3;
            $("#ends" + i).trigger('chosen:updated');
        }

        if(batchCreateNum)
        {
            for(j = i+1; j < batchCreateNum; j++)
            {
                $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
                $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
                $("#begins" + j, "#ends" + j).trigger('chosen:updated');
            }
        }
    }
}

function switchTimeList(number)
{
    if($('#switchTime' + number).prop('checked'))
    {
        $('#begins' + number, '#ends' + number).attr('disabled', 'disabled').trigger('chosen:updated');
    }
    else
    {
        $('#begins' + number, '#ends' + number).removeAttr('disabled').trigger('chosen:updated');
    }
}

function switchDateFeature(switcher)
{
    if(switcher.checked)
    {
        $('#begin, #end').attr('disabled','disabled').trigger('chosen:updated');
    }
    else
    {
        $('#begin, #end').removeAttr('disabled').trigger('chosen:updated');
    }
}

/**
 * 周期待办切换指定复选框时的交互展示。
 * Interactive display when switching the specified checkbox for cycle.
 *
 * @param  object switcher
 * @return void
 */
function showSpecifiedDate(switcher)
{
    if(switcher.checked)
    {
        $('#everyInput').attr('disabled','disabled');
        $('.specify').removeClass('hidden');
        $('.every').addClass('hidden')
        $('#configEvery').prop('checked', false);
    }
}

/**
 * 切换周期复选框的回调函数，用于页面交互展示。
 * Switch the cycle checkbox for page interactive display.
 *
 * @param  object switcher
 * @return void
 */
function showEvery(switcher)
{
    if(switcher.checked)
    {
        $('#everyInput').removeAttr('disabled');
        $('.specify').addClass('hidden');
        $('.every').removeClass('hidden');
        $('#cycleYear').removeAttr('checked');
        $('#configSpecify, #configEvery').prop('checked', false);
    }
}

/**
 * 周期设置为天并为指定时，更改月份时获取天数。
 * When the cycle is set to days and specified, obtain the number of days when changing the month.
 *
 * @param  int $specifiedMonth
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
