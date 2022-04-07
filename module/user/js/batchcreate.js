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

$('select[id^="visions"]').each(function()
{
    var i      = $(this).attr('id').replace(/[^0-9]/ig, '');
    var vision = $('#visions1 option:selected').val();

    $.post(createLink('user', 'ajaxGetGroup', "visions=" + vision + '&i=' + i + '&selected=' + $('#group' + i).val()), function(data)
    {
         $('#group' + i).replaceWith(data);
         $('#group' + i + '_chosen').remove();
         $('#group' + i).chosen();
    })
})

$(document).on('change', "select[id^='visions']", function()
{
    var i       = parseInt($(this).attr('id').replace(/[^0-9]/ig, ''));
    var visions = $('select[id="visions' + i + '"]').val();

    var groups  = $('#group' + i).val();
    if($.inArray('ditto', groups) >= 0) groups = '';

    $.post(createLink('user', 'ajaxGetGroup', "visions=" + visions + '&i=' + i + '&selected=' + groups), function(data)
    {
        $('#group' + i).replaceWith(data);
        $('#group' + i + '_chosen').remove();
        $('#group' + i).chosen();
    })

    for(n = i + 1; n <= batchCreateCount; n++)
    {
        if(n == i) continue;
        if($.inArray('ditto', $('select[id="visions' + n + '"]').val()) < 0) break;

        ((function(n)
        {
            $.post(createLink('user', 'ajaxGetGroup', "visions=" + visions + '&i=' + n + '&selected=' + $('#group' + n).val()), function(data)
            {
                $('#group' + n).replaceWith(data);
                $('#group' + n + '_chosen').remove();
                $('#group' + n).chosen();
            })
        }(n)));
    }
});
