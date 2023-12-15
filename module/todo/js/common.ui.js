$(document).on('click', '.time-input', function()
{
    $('.time-input').removeClass('focus');

    $(this).addClass('focus');
})

let nameDefaultHtml = $('#nameInputBox').html();

/**
 * 切换周期类型。
 * Toggle cycle type.
 *
 * @return void
 */
function changeCycleType()
{
    var cycleType = $('#cycleType input[type=radio]:checked').val();
    toggleCycleConfig(cycleType);
    $('.type-day .input-group, .config-day .input-control').removeClass('has-error');
}

/**
 * 切换周期设置的展示。
 * Toggle cycle setting display.
 *
 * @param  string cycleType  Type of cycle.
 * @return void
 */
function toggleCycleConfig(cycleType)
{

    $('.cycle-type-detail:not(.type-' + cycleType + ')').addClass('hidden');
    $('.cycle-type-detail.type-' + cycleType).removeClass('hidden');
}

/**
 * 切换私人事务，用于切换指派给的禁用状态。
 * Toggle private transactions for switching the disabled state assigned to.
 *
 * @param  object switcher
 * @return void
 */
function togglePrivate(switcher)
{
    $assignedTo = $("[name='assignedTo']").zui('picker');
    $assignedTo.options.disabled = false;

    if($(switcher).prop('checked')) $assignedTo.options.disabled = true;
    $assignedTo.render($assignedTo.options);
}

/**
 * 更改指派给时。
 * change assignedTo.
 *
 * @return void
 */
function changeAssignedTo()
{
    var assignedTo = $('[name=assignedTo]').val();
    if(assignedTo !== userAccount)
    {
        $('#private').prop('disabled', true);
        $('#private').closest('.checkbox-primary').addClass('disabled');
    }
    else
    {
        $('#private').prop('disabled', false);
        $('#private').closest('.checkbox-primary').removeClass('disabled');
    }
}

/**
 * 切换日期待定复选框。
 * Toggle date pending checkbox.
 *
 * @param  object switcher
 * @return void
 */
function togglePending()
{
    $date   = $("[name='date']").zui('datePicker');
    options = $date.options;
    options.disabled = false;

    if($('#switchDate').prop('checked')) options.disabled = true;
    $date.render(options);
}

/**
 * 加载不同类型数据列表，从而更改待办名称控件。
 * Load different types of list to change the name control.
 *
 * @param  string type        Type of selected todo.
 * @param  string id          ID of selected todo.
 * @param  string defaultType Default type of selected todo.
 * @param  int    objectID    ID of the closed todo type.
 * @return void
 */
function loadList(type, id, todoDefaultType, objectID)
{
    let nameBoxClass = '.name-box';
    let nameBoxID    = '#nameBox';
    if(id)
    {
        nameBoxClass = '.name-box' + id;
        nameBoxID    = '#nameBox' + id;
    }

    id = id ? id : '';
    var param = 'userID=' + userID + '&id=' + id;
    if(type == 'task') param += '&status=wait,doing';
    if(type == 'risk') param += '&status=active,hangup';

    if(todoDefaultType && type == todoDefaultType && objectID != 0) param += '&objectID=' + objectID;

    if(moduleList.indexOf(type) !== -1)
    {
        let link = $.createLink(type, objectsMethod[type], param);
        $.get(link, function(data)
        {
            data = JSON.parse(data);
            data.defaultValue = objectID;
            $(nameBoxClass).find('#nameInputBox').html("<div class='picker-box' id='" + type + "'></div>");
            $('#nameInputBox #' + type).picker(data);
        });
    }
    else
    {
        $(nameBoxClass).find('#nameInputBox').html(nameDefaultHtml);
    }

    if(nameBoxLabel) return;

    var formLabel = type == 'custom' || (vision && vision == 'rnd') ?  nameBoxLabel.custom : nameBoxLabel.objectID;
    $('#nameBox .form-label').text(formLabel);
}

/**
 * 选择开始时间后，自动给出默认终止时间。
 * After selecting the start time, the default end time is automatically given.
 *
 * @return void
 */
function selectNext()
{
    if($('#begin').length == 0 || $('#end').length == 0) return;

    $begin = $("[name='begin']").zui('picker');
    $end   = $("[name='end']").zui('picker');

    beginValue = $begin.$.value;
    endValue   = $end.$.value;
    $end.options.items.forEach(function(item, index)
    {
        if(item.value == beginValue)
        {
            endValue = $end.options.items[index + 3].value;
            return;
        }
    })
    $end.$.setValue(endValue);
}

/**
 * 切换起止时间的禁用状态。
 * Switch the disabled state of start and end time.
 *
 * @param  object switcher
 * @return void
 */
function switchDateFeature(e)
{
    $begin = $("[name='begin']").zui('picker');
    $end   = $("[name='end']").zui('picker');
    $begin.options.disabled = false;
    $end.options.disabled   = false;

    if($(e.target).prop('checked'))
    {
        $begin.options.disabled = true;
        $end.options.disabled   = true;
    }
    $begin.render($begin.options);
    $end.render($end.options);
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
        $('#spaceDay').removeAttr('disabled');
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
 * @param  int specifiedMonth
 * @return void
 */
function setDays(e)
{
    var specifiedMonth = $(e.target).val()

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

/**
 * 更改日期。
 * Change date.
 *
 * @param  object dateInput
 * @return void
 */
function changeDate(event)
{
    $('#switchDate').prop('checked', !event.target.value);
}

/**
 * 验证间隔天数是否为空。
 * Verify if the sapceDay value is empty.
 *
 * @param  object spaceDay
 * @return void
 */
function verifySpaceDay(event)
{
    if(typeof event.target.value != 'undefined' && !event.target.value) $(event.target).closest('.input-control').addClass('has-error');
}

/**
 * 验证周期类型为天的日期是否为空。
 * Verify if the date with a cycle type of days is empty.
 *
 * @param  object dateInput
 * @return void
 */
function verifyCycleDate(event)
{
    if(!event.target.value) $(event.target).closest('.input-group').addClass('has-error');
}

/**
 * 验证结束时间是否正确。
 * Verify if the end time is correct.
 *
 * @param  object time
 * @return void
 */
function verifyEndTime(event)
{
    let end = $(event.target).zui('picker').$.value;
    if(end < $('#begin').zui('picker').$.value) $(event.target).closest('.picker-box').addClass('has-error');
}
