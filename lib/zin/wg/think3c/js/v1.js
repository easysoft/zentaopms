$(document).ready(function()
{
    const canvas    = $('#canvas')[0];
    const ctx       = canvas.getContext('2d');
    const image     = new Image();
    const maxWidth  = $('.think-model-content').width() || 500;
    const maxHeight = maxWidth;

    image.src = modelImg;

    /* 检查图片是否已加载完成，如未完成，手动触发处理逻辑。*/
    /* Check if the image has been loaded completely. If not, manually trigger the processing logic. */
    if(image.complete && image.naturalWidth != 0) image.onload();
});

/**
 * 将图像二值化。
 * Binarize the image.
 *
 * @param  object imageData
 * @access public
 * @return object
 */
function binarizeImage(imageData)
{
    const threshold = 200;
    let pixels      = imageData.data;
    for(let i = 0; i < pixels.length; i += 4)
    {
        const brightness = (pixels[i] + pixels[i + 1] + pixels[i + 2]) / 3;
        const color      = brightness > threshold ? 255 : 0; // 255 为白色，0 为黑色
        pixels[i] = pixels[i + 1] = pixels[i + 2] = color;
    }
    imageData.data = pixels;
    return imageData;
}
