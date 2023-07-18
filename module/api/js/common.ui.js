/**
 * Toggle acl.
 *
 * @param  string $acl
 * @param  string $type
 * @access public
 * @return void
 */
window.toggleAcl = function(type)
{
    const acl = $('input[name=acl]:checked').val();
    if(acl == 'private')
    {
        $('#whiteListBox').removeClass('hidden');
    }
    else
    {
        $('#whiteListBox').addClass('hidden');
    }
}

/**
 * Toggle lib type.
 *
 * @param  string $libType
 * @access public
 * @return void
 */
window.toggleLibType = function()
{
    const libType = $('input[name=libType]:checked').val();
    if(libType == 'project')
    {
        $('#projectBox').removeClass('hidden');
        $('#productBox').addClass('hidden');
        $('#acl_default').parent().show();
        $('#acl_default').next('label').html($('#acl_default').next('label').html().replace(productLang, projectLang));
    }
    else if(libType == 'product')
    {
        $('#projectBox').addClass('hidden');
        $('#productBox').removeClass('hidden');
        $('#acl_default').parent().show();
        $('#acl_default').next('label').html($('#acl_default').next('label').html().replace(projectLang, productLang));
    }
    else
    {
        const acl = $("input[name='acl']:checked").val();
        if(acl == 'default') $("input[id='acl_open']").prop('checked', true);

        $('#projectBox').addClass('hidden');
        $('#productBox').addClass('hidden');
        $('#acl_default').parent().hide();
    }
}
