/**
 * @type {string}
 */
let miniProgramID;

/**
 * Open disable mini program dialog.
 * @param {Event} event
 */
function openDisableDialog(event)
{
    const $modal = $('#disable-miniprogram');
    $modal.modal('show', 'fit');
    miniProgramID = $(event.target).closest('tr').find('.c-id').text();
}

/**
 * Open delete mini program dialog.
 * @param {Event} event
 */
function openDeleteDialog(event)
{
    const $modal = $('#delete-miniprogram');
    $modal.modal('show', 'fit');
    miniProgramID = $(event.target).closest('tr').find('.c-id').text();
}

/**
 * Open publish mini program dialog.
 * @param {Event} event
 */
function openPublishDialog(event)
{
    const $modal = $('#publish-miniprogram');
    $modal.modal('show', 'fit');
    miniProgramID = $(event.target).closest('tr').find('.c-id').text();
}

/**
 * Change mini program `deleted` value.
 * @param {'0'|'1'} deleted
 */
function deleteMiniProgram(deleted)
{
    window.location.href = createLink('ai', 'deleteMiniProgram', `appID=${miniProgramID}&deleted=${deleted}`);
}

/**
 * Publish a mini program.
 */
function publishMiniProgram()
{
    window.location.href = createLink('ai', 'publishMiniProgram', `appID=${miniProgramID}`);
}

/**
 * Unpublish a mini program.
 */
function unpublishMiniProgram()
{
    window.location.href = createLink('ai', 'unpublishMiniProgram', `appID=${miniProgramID}`);
}

/**
 * Export mini program data.
 * @param {Event} event
 */
function exportMiniProgram(event)
{
    const $target = $(event.target);
    const $tr = $target.closest('tr');
    const id = $tr.find('.c-id').text();
    const name = $tr.find('.c-name').text();

    $target.closest('button').attr('disabled', 'disabled');

    const xhr = new XMLHttpRequest();
    xhr.open('GET', createLink('ai', 'exportMiniProgram', `appID=${id}`, 'json'), true);
    xhr.responseType = 'blob';
    xhr.setRequestHeader('Accept', 'application/zip');

    xhr.onload = function()
    {
        if(xhr.status === 200)
        {
            const blob = xhr.response;
            const url = URL.createObjectURL(blob);

            const downloadLink = document.createElement('a');
            downloadLink.href = url;
            downloadLink.download = `${name}.ztapp.zip`;
            downloadLink.click();

            URL.revokeObjectURL(url);
        }
        $target.closest('button').removeAttr('disabled');
    };

    xhr.onerror = function()
    {
        $target.closest('button').removeAttr('disabled');
    };

    xhr.send();
}

$(function()
{
    $('[data-toggle="tooltip"]').tooltip();
});
