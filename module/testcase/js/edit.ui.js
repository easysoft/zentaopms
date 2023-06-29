function loadLibModules()
{
    const libID = $('#lib').val();
    const link = createLink('tree', 'ajaxGetOptionMenu', 'libID=' + libID + '&viewtype=caselib&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=true');

    $('#moduleIdBox').load(link);
}
