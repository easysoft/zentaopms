/**
 * Set duplicate field.
 *
 * @param  string $resolution
 * @access public
 * @return void
 */
function setDuplicate(resolution, bugID)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox' + bugID).show();
    }
    else
    {
        $('#duplicateBugBox' + bugID).hide();
    }
}

$(document).ready(removeDitto());//Remove 'ditto' in first row.

$(document).on('click', '.chosen-with-drop', function(){oldValue = $(this).prev('select').val();})//Save old value.

/* Set ditto value. */
$(document).on('change', 'select', function()
{
    if($(this).data('zui.picker').getValue() == 'ditto')
    {
        var index  = $(this).closest('td').index();
        var row    = $(this).closest('tr').index();
        var tbody = $(this).closest('tr').parent();

        if($(this).attr('name').indexOf('resolutions') != -1)
        {
            index  = $(this).closest('tr').closest('td').index();
            row    = $(this).closest('tr').closest('td').parent().index();
            tbody = $(this).closest('tr').closest('td').parent().parent();
        }

        var value = '';
        var label = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = tbody.children('tr').eq(i).find('td').eq(index).find('select').data('zui.picker').getValue();
            label = tbody.children('tr').eq(i).find('td').eq(index).find('select').data('zui.picker').getListItem(value).text;
            if(value != 'ditto') break;
        }

        isPlans = $(this).attr('name').indexOf('plans') != -1;

        $(this).data('zui.picker').updateOptionList([{text: label, value}]);
        if(isPlans)
        {
            var valueStr = ',' + $(this).find('option').map(function(){return $(this).val();}).get().join(',') + ',';
            if(valueStr.indexOf(',' + value + ',') != -1)
            {
                $(this).data('zui.picker').setValue(value);
            }
            else
            {
                alert(dittoNotice);
                $(this).data('zui.picker').setValue(oldValue);
            }
        }
        else
        {
            $(this).data('zui.picker').setValue(value);
        }
    }
})

$(function()
{
    $('#subNavbar li[data-id="bug"]').addClass('active');

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    var firstResolution  = $('select[id^="resolutions"]').eq(0);
    var maxAutoDropWidth = document.body.scrollWidth + ($(firstResolution)[0].offsetWidth / 2) - $(firstResolution)[0].getBoundingClientRect().right;

    initPicker = function($element)
    {
        var picker = $element.data('zui.picker');
        var originOptions = picker.options;

        if(picker) picker.destroy();

        var addOptions =
        {
          disableEmptySearch : true,
          dropWidth : 'auto',
          maxAutoDropWidth : maxAutoDropWidth,
          searchDelay : 1000,
          onReady: function(event)
          {
            $(event.picker.$container).addClass('required');
          }
        }
        var options = $.extend({}, originOptions, addOptions);
        $element.picker(options);
    };

    $('select[id^="duplicateBugs"]').each(function(){
      initPicker($(this));
    });
});
