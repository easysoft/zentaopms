$(function()
{
    $('[name^=products]').change(function()
    {
        value = $(this).val();
        if($(this).prop('checked'))
        {
            $('[data-product=' + value + ']').prop('checked', true)
        }
        else
        {
            $('[data-product=' + value + ']').prop('checked', false)
        }
    })

    $('[name^=projects]').change(function()
    {
        if($(this).prop('checked'))
        {
            var productID = $(this).attr('data-product');
            if($('[data-productid=' + productID + ']').length > 0 && !$('[data-productid=' + productID + ']').prop('checked')) $('[data-productid=' + productID + ']').prop('checked', true);
        }
    })

    toggleProgram($('form #newProgram0'));
});

function toggleProgram(obj)
{
    $obj         = $(obj);
    $programs    = $obj.closest('.input-group').find('[id^=programs]');
    $programName = $obj.closest('.input-group').find('[id^=programName]');
    if($obj.prop('checked'))
    {
        $programs.addClass('hidden');
        $programName.removeClass('hidden');
    }
    else
    {
        $programs.removeClass('hidden');
        $programName.addClass('hidden');
    }
}
