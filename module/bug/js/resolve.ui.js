$(document).off('change', "[name^='resolution']").on('change', "[name^='resolution']", function()
{
    $('#duplicateBugBox').closest('.form-row').toggleClass('hidden', $(this).val() != 'duplicate');
    if(requiredFields.indexOf('resolvedBuild') == -1)
    {
        var resolvedBuildGroup = $('[data-name=resolvedBuild]').find('label.form-label');
        if($(this).val() == 'fixed')
        {
            resolvedBuildGroup.addClass('required');
        }
        else
        {
            resolvedBuildGroup.removeClass('required');
        }
    }
});

$(function()
{
    $('#duplicateBugBox').closest('.form-row').addClass('hidden');
    $('[data-name=newBuildBox]').closest('.form-row').addClass('hidden');
    $('#createBuild').on('change', function()
    {
        $('[data-name=resolvedBuild]').closest('.form-row').toggleClass('hidden', $(this).prop('checked'));
        $('[data-name=newBuildBox]').closest('.form-row').toggleClass('hidden', !$(this).prop('checked'));
    })
})
