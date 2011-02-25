function restoreDefault()
{
    $('#customFields option').remove();
    $('#defaultFields option').clone().appendTo('#customFields');
}
