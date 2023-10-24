window.updateUserAvatar = function(dialog)
{
    const newSrc = $(dialog).find('.avatar-img').prop('src');
    const code = $('#toolbar').find('.avatar-img').dataset('code');
    $('.avatar-img[data-code="' + code + '"]').prop('src', newSrc);
};
