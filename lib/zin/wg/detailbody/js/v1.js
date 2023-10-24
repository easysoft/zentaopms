$(() =>
{
    const $formActions = $('form.detail-body + .form-actions');
    if($formActions.length)
    {
        $detailBody = $formActions.prev();
        $detailBody.append($formActions);
    }
});
