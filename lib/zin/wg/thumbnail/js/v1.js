/**
 * 点击触发文件上传事件。
 * Trigger file upload event by clicking.
 *
 * @param {string} thumbnail
 */
window.uploadThumbnail = function()
{
    $('#thumbnail-file').trigger('click');
}

/**
 * 上传图片后，显示缩略图。
 * After uploading the image, display the thumbnail.
 *
 * @param {string} thumbnail
 */
window.changeThumbnail = function()
{
    const file = document.querySelector('#thumbnail-file').files[0];
    const fr   = new FileReader();
    fr.readAsDataURL(file);
    fr.onload = function() {
        $('#thumbnail-img').attr('src', this.result).removeClass('hidden');
        $('#thumbnail-tips').addClass('hidden');
    }
}
