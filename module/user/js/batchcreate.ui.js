function changeType(event)
{
    const type        = $(event.target).val();
    const $dept       = $('[data-name="dept"]');
    const $company    = $('[data-name="companyItem"]');
    const $join       = $('[data-name="join"]');
    if(type == 'inside')
    {
        $dept.removeClass('hidden');
        $company.addClass('hidden');
        $join.removeClass('hidden');
    }
    else
    {
        $dept.addClass('hidden');
        $company.removeClass('hidden');
        $join.addClass('hidden');
    }
    $('#userType').val(type);
}

function toggleNew(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const $company    = $currentRow.find('select[data-name="company"]');
    const $newCompany = $currentRow.find('input[data-name="newCompany"]');
    $target.toggleClass('checked');
    if($target.hasClass('checked'))
    {
        $company.addClass('hidden');
        $newCompany.removeClass('hidden');
    }
    else
    {
        $company.removeClass('hidden');
        $newCompany.addClass('hidden');
    }
}
