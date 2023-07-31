/**
 * Change group by role.
 *
 * @param  string $role
 * @param  int    $i
 * @access public
 * @return void
 */
function changeGroup(role, i)
{
    if(role && roleGroup[role])
    {
        $('#group' + i).val(roleGroup[role]);
    }
    else
    {
        $('#group' + i).val('');
    }
    $('#group' + i).trigger('chosen:updated');
}

/**
 * Toggle checkbox and check password strength.
 *
 * @param  object $obj
 * @param  int    $i
 * @access public
 * @return void
 */
function toggleCheck(obj, i)
{
    var $this    = $(obj);
    var password = $this.val();
    var $ditto   = $('#ditto' + i);
    var $passwordStrength = $this.closest('.input-group').find('.passwordStrength');
    if(password == '')
    {
        $ditto.attr('checked', true);
        $ditto.closest('.input-group-addon').show();
        $passwordStrength.hide();
        $passwordStrength.html('');
    }
    else
    {
        $ditto.removeAttr('checked');
        $ditto.closest('.input-group-addon').hide();
        $passwordStrength.html(passwordStrengthList[computePasswordStrength(password)]);
        $passwordStrength.show();
    }
}

$(function()
{
    removeDitto(); //Remove 'ditto' in first row.
})

$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'ditto')
    {
        if($(select).attr('id').substr(0, 7) == 'visions')
        {
            /* Fix bug #19960. */
            var value = '';
        }
        else
        {
            var index = $(select).closest('td').index();
            var row   = $(select).closest('tr').index();
            var table = $(select).closest('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'ditto') break;
            }
        }

        $(select).val(value);
        $(select).trigger("chosen:updated");
    }
})

$(document).on('change', '[id^=visions]', function()
{
    if($.inArray('ditto', $(this).val()) >= 0)
    {
        $(this).val('ditto');
        $(this).trigger("chosen:updated");
    }
})

var rndGroupSelect = liteGroupSelect = orGroupSelect = rndliteGroupSelect = rndorGroupselect = liteorGroupSelect = allGroupSelect = emptyGroupSelect = '';
$.post(createLink('user', 'ajaxGetGroup', "visions=rnd&i=2"), function(data)
{
    rndGroupSelect = data;
    var vision = $('#visions1 option:selected').val();
    if(vision == 'rnd') initGroup(data);
});
$.post(createLink('user', 'ajaxGetGroup', "visions=lite&i=2"), function(data)
{
    liteGroupSelect = data;
    var vision = $('#visions1 option:selected').val();
    if(vision == 'lite') initGroup(data);
});
$.post(createLink('user', 'ajaxGetGroup', "visions=or&i=2"), function(data)
{
    orGroupSelect = data;
    var vision = $('#visions1 option:selected').val();
    if(vision == 'or') initGroup(data);
});
$.post(createLink('user', 'ajaxGetGroup', "visions=rnd,lite&i=2"), function(data)
{
    rndliteGroupSelect = data;
});
$.post(createLink('user', 'ajaxGetGroup', "visions=rnd,or&i=2"), function(data)
{
    rndorGroupSelect = data;
});
$.post(createLink('user', 'ajaxGetGroup', "visions=lite,or&i=2"), function(data)
{
    liteorGroupSelect = data;
});
$.post(createLink('user', 'ajaxGetGroup', "visions=rnd,lite,or&i=2"), function(data)
{
    allGroupSelect = data;
});
$.post(createLink('user', 'ajaxGetGroup', "visions=null&i=2"), function(data)
{
    emptyGroupSelect = data;
});

function initGroup(data)
{
    $('select[id^="visions"]').each(function()
    {
        var i        = $(this).attr('id').replace(/[^0-9]/ig, '');
        var groupVal = $('#group' + i).val();

        var dataObj = $(data);
        var dataHtml = $(dataObj).attr('id', 'group' + i).attr('name', 'group[' + i + '][]').prop('outerHTML');

        $('#group' + i).replaceWith(dataHtml);
        $('#group' + i + '_chosen').remove();
        if(i == 1) $('#group' + i).find('option[value="ditto"]').remove();
        $('#group' + i).val(groupVal);
        $('#group' + i).chosen();
    })
}

/**
 * Get group data by selected vision.
 *
 * @param int $i
 * @access public
 * @return html
 */
function getGroupSelect(i)
{
    if(i < 1) return '';
    var visions = $('select[id="visions' + i + '"]').val();
    if(visions) visions = visions.sort();

    visions = visions ? visions.join() : '';
    switch(visions)
    {
        case 'rnd':
            var data = rndGroupSelect;
            break;
        case 'lite':
            var data = liteGroupSelect;
            break;
        case 'or':
            var data = orGroupSelect;
            break;
        case 'lite,rnd':
            var data = rndliteGroupSelect;
            break;
        case 'or,rnd':
            var data = rndorGroupSelect;
            break;
        case 'lite,or':
            var data = liteorGroupSelect;
            break;
        case 'lite,or,rnd':
            var data = allGroupSelect;
            break;
        case 'ditto':
            var data = getGroupSelect(i - 1);
            break;
        default:
            var data = emptyGroupSelect;
            break;
    }

    return data;
}

$(document).on('change', "select[id^='visions']", function()
{
    var i    = parseInt($(this).attr('id').replace(/[^0-9]/ig, ''));
    var data = getGroupSelect(i);

    for(n = i; n <= batchCreateCount; n++)
    {
        if(n != i && $.inArray('ditto', $('select[id="visions' + n + '"]').val()) < 0) break;

        ((function(n)
        {
            var groupVal = $('#group' + n).val();
            var dataHtml = $(data).attr('id', 'group' + n).attr('name', 'group[' + n + '][]').prop('outerHTML');
            $('#group' + n).replaceWith(dataHtml);
            $('#group' + n + '_chosen').remove();
            $('#group' + n).val(groupVal);
            $('#group' + n).chosen();
        }(n)));
    }
});

/**
 * Show or hide companies based on user type.
 *
 * @param  type $type
 * @access public
 * @return void
 */
function changeType(type)
{
    if(type == 'inside')
    {
        $('#companyBox').addClass('hide');
        $('select[id^=dept], input[id^=join]').closest('td').removeClass('hide');
        $('select[id^=company]').closest('td').addClass('hide');
        $('th.c-dept, th.c-join').removeClass('hide');
        $('th.c-company').addClass('hide');
    }
    else
    {
        $('#companyBox').removeClass('hide');
        $('select[id^=dept], input[id^=join]').closest('td').addClass('hide');
        $('select[id^=company]').closest('td').removeClass('hide');
        $('th.c-dept, th.c-join').addClass('hide');
        $('th.c-company').removeClass('hide');
    }
    $('#userType').val(type);
}

$(function()
{
    $(":checkbox[name^='new']").change(function()
    {
        var companyNode     = $(this).prop('checked') ? $(this).closest('span').prevAll('select') : $(this).closest('span').prevAll('input');
        var companyNodeID   = $(companyNode).attr('id');
        var companyNodeName = $(companyNode).attr('name');

        if($(this).prop('checked'))
        {
            $(companyNode).replaceWith("<input name='" + companyNodeName + "' id='" + companyNodeID + "' class='form-control'/>");
            $('#' + companyNodeID + '_chosen').remove();
        }
        else
        {
            var i        = companyNodeID.replace(/[^0-9]/ig, '');
            var dataHtml = $(companies).attr('id', companyNodeID).attr('name', companyNodeName).prop('outerHTML');
            $('#' + companyNodeID).replaceWith(dataHtml);
            if(i == 1) $('#' + companyNodeID).find('option[value="ditto"]').remove();
            $('#' + companyNodeID).chosen();
        }
    })
});
