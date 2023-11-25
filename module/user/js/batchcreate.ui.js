function batchChangeType(event)
{
    const isInside = type == 'inside';
    $('[data-name="companyItem"]').toggleClass('hidden', isInside);
    $('[data-name="dept"], [data-name="join"]').toggleClass('hidden', !isInside);
    $('#userType').val(type);
}

function batchToggleNew(event)
{
    const $currentRow = $(event.target).closest('tr');
    const $company    = $currentRow.find('div[data-name="company"]').closest('.picker-box').toggleClass('hidden');
    const $newCompany = $currentRow.find('input[data-name="newCompany"]').toggleClass('hidden');
}

/**
 * Toggle checkbox and check password strength.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function batchTogglePasswordStrength(event)
{
    const $target   = $(event.target);
    const password  = $target.val();
    const $strength = $target.closest('tr').find('.passwordStrength');
    $strength.toggleClass('hidden', password == '');
    $strength.html(password == '' ? '' : passwordStrengthList[computePasswordStrength(password)]);
}
