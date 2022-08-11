$(document).on('change', "[name^='estStarted'], [name^='deadline']", function()
{
    toggleCheck($(this));
});

/**
 * Set story related.
 *
 * @param  int    $num
 * @access public
 * @return void
 */
function setStoryRelated(num)
{
    setPreview(num);
}

/**
 * Toggle checkbox.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function toggleCheck(obj)
{
    var $this  = $(obj);
    var date   = $this.val();
    var $ditto = $this.closest('div').find("input[type='checkBox']");
    if(date == '')
    {
        $ditto.attr('checked', true);
        $ditto.closest('.input-group-addon').show();
    }
    else
    {
        $ditto.removeAttr('checked');
        $ditto.closest('.input-group-addon').hide();
    }
}

/**
 * Set stories.
 *
 * @param  int    $moduleID
 * @param  int    $executionID
 * @param  int    $num
 * @access public
 * @return void
 */
function setStories(moduleID, executionID, num)
{
    var link = createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=0&num=' + num + '&type=short');
    $.get(link, function(stories)
    {
        var storyID = $('#story' + num).val();
        if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
        $('#story' + num).replaceWith(stories);
        if(num != 0 && (moduleID == 0 || moduleID == 'ditto')) $('#story' + num).append("<option value='ditto'>" + ditto + "</option>");
        $('#story' + num).val(storyID);

        var chosenWidth = $("#story" + num + "_chosen").css('max-width');
        $("#story" + num + "_chosen").remove();
        $("#story" + num).next('.picker').remove();
        $("#story" + num).chosen();
        $("#story" + num + "_chosen").width(chosenWidth).css('max-width', chosenWidth);
    });
}

/**
 * Copy story title.
 *
 * @param  int    $num
 * @access public
 * @return void
 */
function copyStoryTitle(num)
{
    var $story     = $('#story' + num);
    var storyTitle = $story.find('option:selected').text();
    var storyValue = $story.find('option:selected').val();
    var begin      = parseInt($story.closest('tr').children('.c-id').text()) - 2;

    if(storyValue === 'ditto')
    {
        for(var i = begin; i >= 0; i--)
        {
            var selectedValue = $('#tableBody tbody > tr').eq(i).find('select[name^="story"]').val();
            var selectedTitle = $('#tableBody tbody > tr').eq(i).find('select[name^="story"]').find('option:selected').text();
            if(selectedValue !== 'ditto')
            {
                storyTitle = selectedTitle;
                break;
            }
        }
    }

    var startPosition = storyTitle.indexOf(':') + 1;
    var endPosition   = storyTitle.lastIndexOf('[');
    storyTitle        = storyTitle.substr(startPosition, endPosition - startPosition);

    $('#name\\[' + num + '\\]').val(storyTitle);
}

/**
 * Set preview.
 *
 * @param  int $num
 * @access public
 * @return void
 */
function setPreview(num)
{
    var storyID = $('#story' + num).val();
    if(storyID != 0  && storyID != 'ditto')
    {
        storyLink  = createLink('story', 'view', "storyID=" + storyID, '', true);
        $('#preview' + num).removeAttr('disabled');
        $('#preview' + num).modalTrigger({type:'iframe'});
        $('#preview' + num).css('pointer-events', 'auto');
        $('#preview' + num).attr('href', storyLink);
    }
    else
    {
        storyLink  = '#';
        $('#preview' + num).attr('disabled', true);
        $('#preview' + num).css('pointer-events', 'none');
        $('#preview' + num).attr('href', storyLink);
    }
}

/**
 * Mark story task.
 *
 * @access public
 * @return void
 */
function markStoryTask()
{
    $('select[name^="story"]').each(function()
    {
        var $select = $(this);
        $select.find('option').each(function()
        {
            var $option    = $(this);
            var value      = $option.attr('value');
            var tasksCount = storyTasks[value];
            $option.attr('data-data', value).toggleClass('has-task', !!(tasksCount && tasksCount !== '0'));
        });
        $select.trigger("chosen:updated");
    });

    var getStoriesHasTask = function()
    {
        var storiesHasTask = {};
        $('#tableBody tbody>tr').each(function()
        {
            var $tr = $(this);
            if ($tr.find('input[name^="name"]').val())
            {
                storiesHasTask[$tr.find('select[name^="story"]').val()] = true;
            }
        });
        return storiesHasTask;
    };

    $('#batchToTaskForm').on('chosen:showing_dropdown', 'select[name^="story"],.chosen-with-drop', function()
    {
        var storiesHasTask = getStoriesHasTask();
        var $container     = $(this).closest('td').find('.chosen-container');
        setTimeout(function()
        {
            $container.find('.chosen-results>li').each(function()
            {
                var $li = $(this);
                $li.toggleClass('has-new-task', !!storiesHasTask[$li.data('data')]);
            });
        }, 100);
    });
}

$(document).on('chosen:showing_dropdown', 'select[name^="story"],.chosen-with-drop', function()
{
    var select = $(this).closest('td').find('select');
    if($(select).val() == 'ditto')
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
        $(select).val(value);
        $(select).trigger("chosen:updated");
        $(select).trigger("change");
    }
})

$(document).on('mousedown', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var table = $(this).closest('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(this).val(value);
    }
})

$(function()
{
    $('.chosen-container[id^=module]').width(chosenWidth);
    $('.chosen-container[id^=module]').css('max-width', chosenWidth);

    var chosenWidth = $('#story1_chosen').width();
    $('.chosen-container[id^=story]').width(chosenWidth);
    $('.chosen-container[id^=story]').css('max-width', chosenWidth);

    markStoryTask();

    $(document).keydown(function(event)
    {
        if(event.ctrlKey && event.keyCode == 38)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('up');
        }
        else if(event.ctrlKey && event.keyCode == 40)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('down');
        }
        else if(event.keyCode == 38)
        {
            inputFocusJump('up');
        }
        else if(event.keyCode == 40)
        {
            inputFocusJump('down');
        }
    });
});

/**
 * Add row.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function addRow(obj)
{
    var row = $('#addRow').html().replace(/%i%/g, rowIndex + 1);
    $('<tr class="addedRow">' + row  + '</tr>').insertAfter($(obj).closest('tr'));

    var $beginRow = $row = $(obj).closest('tr').next();

    $row.find(".form-date").datepicker();
    $row.find("input[name^=color]").colorPicker();
    $row.find('div[id$=_chosen]').remove();
    $row.find('.picker').remove();
    $row.find('.chosen').chosen();
    $row.find('.picker-select').picker();

    var begin     = parseInt($(obj).parent().siblings('.c-id').text()) + 1;
    var count     = $('#tableBody tbody > tr').length;
    for(var i = begin; i <= count; i++)
    {
        $beginRow.children('.c-id').text(i);
        $beginRow = $beginRow.next();
    }
    rowIndex ++;
}

/**
 * Delete row.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteRow(obj)
{
    var $beginRow = $(obj).closest('tr').next();
    var begin     = parseInt($(obj).parent().siblings('.c-id').text());
    var count     = $('#tableBody tbody > tr').length;
    for(var i = begin; i <= count; i++)
    {
        $beginRow.children('.c-id').text(i);
        $beginRow = $beginRow.next();
    }

    $(obj).closest('tr').remove();
}
