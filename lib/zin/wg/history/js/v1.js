
window.getFileActions = function(file)
{
    let fileActions = [];

    let canPreview       = false;
    let officeTypes      = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
    let isOfficeFile     = officeTypes.includes(file.extension);
    let previewExtension = 'txt|jpg|jpeg|gif|png|bmp|mp4';
    if(previewExtension.includes(file.extension)) canPreview = true;
    if(libreOfficeTurnon && isOfficeFile)         canPreview = true;
    if(canPreview)
    {
        let previewAction = {icon: 'eye', title: previewLang, url: previewLink.replace('{id}', file.id).replace('\\', ''), className: 'text-primary', target: '_blank'};
        if(!isOfficeFile)
        {
            previewAction['data-toggle'] = 'modal';
            previewAction['data-size'] = 'lg';
            delete previewAction.target;
        }
        fileActions.push(previewAction);
    }

    fileActions.push({icon: 'download', title: downloadLang, url: downloadLink.replace('{id}', file.id).replace('\\', ''), className: 'text-primary', target: '_blank'});
    return fileActions;
}
