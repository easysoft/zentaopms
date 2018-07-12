/**
  * Load product Lines. 
  *
  * @param  $rootID
  * @access public
  * @return void
  */
function loadProductLines(rootID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + rootID + '&viewtype=line' + '&branch=0' + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#lineIdBox').load(link, function()
    {
        $(this).find('select').chosen()
    });
}
