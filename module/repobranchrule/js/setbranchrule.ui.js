window.onRadioForAllowCreateChange = function()
{
    const selectedValue = $('input[name="radioForAllowCreate"]:checked').val();
    $('#userAllowCreateGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowDeleteChange = function()
{
    const selectedValue = $('input[name="radioForAllowDelete"]:checked').val();
    $('#userAllowDeleteGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowUpdateChange = function()
{
    const selectedValue = $('input[name="radioForAllowUpdate"]:checked').val();
    $('#userAllowUpdateGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowForcePushChange = function()
{
    const selectedValue = $('input[name="radioForAllowForcePush"]:checked').val();
    $('#userAllowForcePushGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowMergeFromChange = function()
{
    const selectedValue = $('input[name="radioForAllowMergeFrom"]:checked').val();
    $('#branchTypeAllowMergeFromGroup').toggleClass('hidden', selectedValue !== 'specify');
}

window.onRadioForAllowMergeToChange = function()
{
    const selectedValue = $('input[name="radioForAllowMergeTo"]:checked').val();
    $('#branchTypeAllowMergeToGroup').toggleClass('hidden', selectedValue !== 'specify');
}

// Initialize on page load
$(function()
{
    onRadioForAllowCreateChange();
    onRadioForAllowDeleteChange();
    onRadioForAllowUpdateChange();
    onRadioForAllowForcePushChange();
    onRadioForAllowMergeFromChange();
    onRadioForAllowMergeToChange();
});
