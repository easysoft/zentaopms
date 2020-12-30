/**
 * Set budget tips and acl list.
 *
 * @param  parentProgramID $parentProgramID
 * @access public
 * @return void
 */
function setBudgetTipsAndAclList(parentProgramID)
{
    if(parentProgramID != 0)
    {
        $('.budgetSpan').removeClass('hidden');

        parentProgram = PGMList[parentProgramID];
        programBudget = parentProgram.budget;
        PGMBudgetUnit = budgetUnitList[parentProgram.budgetUnit];

        budgetNotes = programBudget != 0 ? (programBudget + PGMBudgetUnit) : future;
        $('.budgetSpan').html(PGMParentBudget + budgetNotes);
        $('.aclBox').html($('#subPGMAcl').html());
    }
    else
    {
        $('.budgetSpan').addClass('hidden');
        $('.aclBox').html($('#PGMAcl').html());
    }
}
