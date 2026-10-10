window.artifactUploadFile = null;
window.artifactExists = false;

/* 前端检测到文件上传后即触发重名校验：显示覆盖/重命名处理区。 */
window.artifactOnFileAdd = function(info)
{
    window.artifactUploadFile = info && info.file ? info.file : info;
    window.showArtifactExistsBlock(window.artifactUploadFile);
};

window.artifactField = function(name)
{
    return $('#artifactUploadForm input[name="' + name + '"]').val() || '';
};

/* 保存按钮提交前：已弹处理区则按覆盖/重命名提交，否则校验文件后直接上传。 */
window.checkArtifactBeforeUpload = function()
{
    if(window.artifactExists)
    {
        window.handleArtifactExists(window.artifactUploadFile);
        return false;
    }

    if(!window.artifactUploadFile)
    {
        zui.Messager.show({type: 'danger', content: window.artifactField('artifactNameRequired'), time: 2000});
        return false;
    }
    return true;
};

/* 上传失败回调：后端返回同名跳过时，弹覆盖/重命名处理区。 */
window.artifactOnFail = function(result)
{
    if(!result || result.result !== 'exists') return;

    /* 重命名后仍撞名：在文件名下方红字提示，保持处理区可继续修改。 */
    if(window.artifactExists)
    {
        $('#artifactNameError').text(window.artifactField('artifactRenameExists')).removeClass('hidden');
        $('#artifactRenameForm').removeClass('hidden');
        return false;
    }

    window.showArtifactExistsBlock(window.artifactUploadFile);
    return false;
};

window.showArtifactExistsBlock = function(file)
{
    const name = file ? (file.name || '') : '';
    const dotIndex = name.lastIndexOf('.');
    const defaultRename = dotIndex > 0 ? name.slice(0, dotIndex) + '(1)' + name.slice(dotIndex) : name + '(1)';
    $('#artifactNewName').val(defaultRename);
    $('#artifactNameError').addClass('hidden');
    $('input[name=artifactHandleType][value=overwrite]').prop('checked', true);
    $('#artifactRenameForm').addClass('hidden');
    $('#artifactExistsBlock').removeClass('hidden');
    window.artifactExists = true;
};

window.toggleArtifactRenameForm = function()
{
    const handleType = $('input[name=artifactHandleType]:checked').val();
    $('#artifactRenameForm').toggleClass('hidden', handleType !== 'rename');
    $('#artifactNameError').addClass('hidden');
};

window.handleArtifactExists = function(file)
{
    const handleType = $('input[name=artifactHandleType]:checked').val();
    if(handleType === 'overwrite')
    {
        window.submitArtifactUpload(file.name, 'true');
        return;
    }

    const newName = ($('#artifactNewName').val() || '').trim();
    if(!newName)
    {
        $('#artifactNameError').text(window.artifactField('artifactNameRequired')).removeClass('hidden');
        return;
    }

    window.submitArtifactUpload(newName);
};

window.submitArtifactUpload = function(fileName, overwrite)
{
    if(!window.artifactUploadFile) return;
    const formData = new FormData();
    formData.append('file', window.artifactUploadFile, fileName);
    formData.append('name', fileName);
    if(overwrite) formData.append('overwrite', overwrite);

    const url = window.artifactField('artifactFormUrl') || $('#artifactUploadForm').attr('action');
    if(!url) return;
    $.ajaxSubmit({url: url, data: formData, onFail: window.artifactOnFail});
};

window.cancelArtifactUpload = function()
{
    zui.Modal.hide();
};
