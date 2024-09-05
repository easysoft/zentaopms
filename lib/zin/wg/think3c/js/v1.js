$(document).ready(function()
{
    const canvas    = $('#canvas')[0];
    const ctx       = canvas.getContext('2d');
    const image     = new Image();
    const maxWidth  = $('.think-model-content').width() || 500;
    const maxHeight = maxWidth;

    image.onload = function()
    {
        let width  = image.width;
        let height = image.height;

        if(width > maxWidth || height > maxHeight)
        {
            if(width > height)
            {
                height *= maxWidth / width;
                width   = maxWidth;
            }
            else
            {
                width  *= maxHeight / height;
                height  = maxHeight;
            }
        }

        canvas.width  = width;
        canvas.height = height;
        ctx.drawImage(image, 0, 0, width, height);

        const imageData = ctx.getImageData(0, 0, width, height);
        const pixels    = binarizeImage(imageData);
        ctx.putImageData(pixels, 0, 0);
    };
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

/**
 * 获取画布尺寸。
 * Get canvas size.
 *
 * @param  number width
 * @param  number height
 * @access public
 * @return object
 */
function getCanvasSize(width = 0, height = 0)
{
    const maxWidth  = $('.think-model-content').length ? ($('.think-model-content').width() - 50) : 400;
    const maxHeight = maxWidth;

    if(width > maxWidth || height > maxHeight)
    {
        if(width > height)
        {
            height *= maxWidth / width;
            width   = maxWidth;
        }
        else
        {
            width  *= maxHeight / height;
            height  = maxHeight;
        }
    }
    if(!width)
    {
        width  = maxWidth;
        height = maxHeight;
    }

    return {width, height};
}
