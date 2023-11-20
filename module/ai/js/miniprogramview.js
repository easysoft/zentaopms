/**
 * Open disable mini program dialog.
 */
function openDisableDialog()
{
    const $modal = $('#disable-miniprogram');
    $modal.modal('show', 'fit');
}

/**
 * Open delete mini program dialog.
 */
function openDeleteDialog()
{
    const $modal = $('#delete-miniprogram');
    $modal.modal('show', 'fit');
}

/**
 * Open publish mini program dialog.
 */
function openPublishDialog()
{
    const $modal = $('#publish-miniprogram');
    $modal.modal('show', 'fit');
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
 * Change mini program `published` value.
 * @param {'0'|'1'} published
 */
function publishMiniProgram(published)
{
    window.location.href = createLink('ai', 'publishMiniProgram', `appID=${miniProgramID}&published=${published}`);
}
