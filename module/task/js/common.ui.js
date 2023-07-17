$('#teamTable').on('click.team', '.btn-add', function()
{
    var $newRow = $(this).closest('tr').clone();

    let options = zui.Picker.query("[name^='team']").options;

    $newRow.find('.picker-box').empty();
    $newRow.find('.picker-box').append(`<div id='line'></div>`);

    $newRow.find('input').val('');
    $(this).closest('tr').after($newRow);

    toggleBtn();
    let index = setLineIndex();
    let newID = $newRow.find('[id^=line]').attr('id');

    options.defaultValue = '';
    new zui.Picker(`#${newID}`, options);

})

$('#teamTable').on('click.team', '.btn-delete', function()
{
    var $row = $(this).closest('tr').remove();
    toggleBtn();
    setLineIndex();
});

/* 切换串行/并行 展示/隐藏工序图标. */
$('.form').on('change.team', '[name="mode"]', function()
{
    if($(this).val() == 'multi')
    {
        $('#teamTable td .icon-angle-down').addClass('hidden');
    }
    else
    {
        $('#teamTable td .icon-angle-down').removeClass('hidden');
    }
});

/**
 * Set line number.
 *
 * @access public
 * @return void
 */
function setLineIndex()
{
    let index = 1;
    $('.team-number').each(function()
    {
        $(this).text(index);
        $(this).closest('tr').find('[id^="line"]').attr('id', 'line' + index);
        index ++;
    });
}

/**
 * Check delete button hide or not.
 *
 * @access public
 * @return void
 */
function toggleBtn()
{
    var $deleteBtn = $('#teamTable').find('.btn-delete');
    if($deleteBtn.length == 1)
    {
        $deleteBtn.addClass('hidden');
    }
    else
    {
        $deleteBtn.removeClass('hidden');
    }
};

function onPageUnmount()
{
    $('#modalTeam').off('.saveTeam');
}
