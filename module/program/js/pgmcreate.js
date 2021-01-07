$(function()
{
    setAclList($("#parent").val());
});

/**
 * Set acl list.
 *
 * @param  int    $programID
 * @access public
 * @return void
 */
function setAclList(programID)
{
    if(programID != 0)
    {
        $('.aclBox').html($('#subPGMAcl').html());
    }
    else
    {
        $('.aclBox').html($('#PGMAcl').html());
    }
}

/**
 * Set parent program.
 *
 * @param  int    $programID
 * @access public
 * @return void
 */
function setParentProgram(programID)
{
    location.href = createLink('program', 'PGMCreate', 'programID=' + programID);
}
