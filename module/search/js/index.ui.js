function searchWords()
{
    const $target = $(event.target).closest('button');
    const $form = $target.closest('#mainContent').find('form');
    const type = $target.data('type') || $form.find('input[name=type]').val();
    const form = new FormData();
    form.append('type[]', type);
    form.append('words', $form.find('input[name=words]').val());

    postAndLoadPage($.createLink('search', 'index'), form);
}

function clearWords()
{
    $('#clearWords').addClass('hidden').closest('.input-group').find('input[name=words]').val('');
}

function toggleClearWords()
{
    $(event.target).closest('.input-group').find('#clearWords').toggleClass('hidden', $(event.target).val() == '');
}
