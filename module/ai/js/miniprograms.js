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
