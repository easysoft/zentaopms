const colors = ['#CFE7FE', '#DCEFF9', '#E0EBFC', '#DBE9FA', '#D4EEE9', '#E4DFF7', '#E7EBF6'];
$(document).ready(function()
{
    const canvas = $('#canvas')[0];
    const ctx    = canvas.getContext('2d');
    const image  = new Image();

    image.src    = modelImg;
    image.onload = function()
    {
        const size = getCanvasSize(image.width, image.height);
        canvas.width = size.width;
        canvas.height = size.height;
        ctx.drawImage(image, 0, 0, size.width, size.height);

        const imageData  = ctx.getImageData(0, 0, size.width, size.height);
        const pixels     = binarizeImage(imageData);
        ctx.putImageData(pixels, 0, 0);

        const binarizeImageUrl = canvas.toDataURL();
        colorRegions(binarizeImageUrl).then((data) => {
            displayColoredImage(data);
        });
    };

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

/**
 * 获取区域信息。
 * Get region info.
 *
 * @param  object  pixels
 * @param  boolean visited
 * @param  number  x
 * @param  number  y
 * @param  number  width
 * @param  number  height
 * @access public
 * @return object
 */
function getRegionInfo(pixels, visited, x, y, width, height)
{
    const region = {area: 0, rows: [],  minX: Infinity, minY: Infinity, maxX: -Infinity, maxY: -Infinity};

    /* 创建栈数据结构。*/
    /* Create a stack data structure.*/
    const stack = [[x, y]];
    while(stack.length > 0)
    {
        const [currentX, currentY] = stack.pop();

        const index = (currentY * width * 4) + (currentX * 4);
        if(pixels[index] === 255 && !visited[currentY + ',' + currentX])
        {
            visited[currentY + ',' + currentX] = true;
            region.area++;

            /* 将rows[currentY] 设为一个数组，以便存储多个区域段。*/
            /* Set rows [CurrentY] as an array to store multiple regional segments. */
            if(!region.rows[currentY]) region.rows[currentY] = [];
            region.rows[currentY].push({ left: Infinity, right: -Infinity });
            let currentRow = region.rows[currentY][region.rows[currentY].length - 1];
            currentRow.left = Math.min(currentRow.left, currentX);
            currentRow.right = Math.max(currentRow.right, currentX);
            if(currentRow.top === undefined) currentRow.top = currentY;

            region.minX = Math.min(region.minX, currentX);
            region.minY = Math.min(region.minY, currentY);
            region.maxX = Math.max(region.maxX, currentX);
            region.maxY = Math.max(region.maxY, currentY);

            if (currentX > 0) stack.push([currentX - 1, currentY]);
            if (currentX < width - 1) stack.push([currentX + 1, currentY]);
            if (currentY > 0) stack.push([currentX, currentY - 1]);
            if (currentY < height - 1) stack.push([currentX, currentY + 1]);
        }
    }

    return region;
}

/**
 * 查找区域。
 * Find regions.
 *
 * @param  object pixels
 * @param  number width
 * @param  number height
 * @access public
 * @return array
 */
function findRegions(pixels, width, height)
{
    const visited = {};
    const regions = [];

    for(let y = 0; y < height; y++)
    {
        for(let x = 0; x < width; x++)
        {
            const index = (y * width * 4) + (x * 4);
            if(pixels[index] === 255 && !visited[y + ',' + x])
            {
                const region = getRegionInfo(pixels, visited, x, y, width, height);
                regions.push(region);
            }
        }
    }

    return regions;
}

/**
 * 处理区域行数据。
 * Process region rows data.
 *
 * @param  array  data
 * @access public
 * @return array
 */
function processRegionRows(data)
{
    if(!data?.length) return [];

    /* 按照 left 的大小对数组进行升序排序。 */
    /* Sort the array in ascending order according to the size of left. */
    data.sort((a, b) => a.left - b.left);

    let result      = [];
    let currentItem = {left: data[0].left, right: data[0].right, top: data[0].top};

    for(let i = 1; i < data.length; i++)
    {
        if(data[i].left === currentItem.right + 1 && data[i].top === currentItem.top)
        {
            currentItem.right = data[i].right;
        }
        else if(data[i].right === currentItem.left - 1 && data[i].top === currentItem.top)
        {
            currentItem.left = data[i].left;
        }
        else
        {
            result.push({left: currentItem.left, right: currentItem.right, top: currentItem.top});
            currentItem = {left: data[i].left, right: data[i].right, top: data[i].top};
        }
    }

    result.push({left: currentItem.left, right: currentItem.right, top: currentItem.top});
    return result;
}

/**
 * 绘制区域输入框标签。
 * Draw a region input.
 *
 * @param  object canvas
 * @param  object region
 * @param  number colorIndex
 * @param  string area
 * @access public
 * @return void
 */
function drawRegionInput(canvas, region, colorIndex, area)
{
    region.minX -= 10;
    region.maxY -= 10;
    const centerX   = (region.minX + region.maxX) / 2;
    const centerY   = (region.minY + region.maxY) / 2;
    const boxWidth  = 60;
    const boxHeight = 32;

    let left = centerX - boxWidth / 2;
    let top  = centerY - boxHeight / 2;
    if([0, 4, 5].includes(colorIndex))  top -= 40;
    if(colorIndex == 1) left -= 30;
    if(colorIndex == 2) left += 30;
    if(colorIndex == 4) left -= 50;
    if(colorIndex == 5) left += 50;

    const styles = {
        position: 'absolute',
        left: `${left}px`,
        top: `${top}px`,
        width: `${boxWidth}px`,
        height: `${boxHeight}px`,
        padding: '5px',
        fontSize: '16px',
        color: '#000',
        backgroundColor: colors[colorIndex % colors.length],
    };
}

/**
 * 图像着色。
 * Color the image.
 *
 * @param  string binaryImageData
 * @access public
 * @return object
 */
function colorRegions(binaryImageData)
{
    const img = new Image();
    img.src = binaryImageData;

    return new Promise((resolve, reject) => {
        img.onload = function()
        {
            const canvas = document.createElement('canvas');
            const ctx    = canvas.getContext('2d');

            canvas.width  = img.width;
            canvas.height = img.height;

            ctx.imageSmoothingEnabled = true;
            ctx.drawImage(img, 0, 0);

            const pixels = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
            let regions  = findRegions(pixels, canvas.width, canvas.height);

            ctx.globalCompositeOperation = 'source-over';

            /* 过滤掉小区域。 */
            /* Filter out small regions. */
            const minArea = 90;
            regions       = regions.filter(r => r.area >= minArea);

            regions.forEach((region, colorIndex) => {
                /* 保存当前状态。 */
                /* Save current state. */
                ctx.save();

                ctx.fillStyle = colors[colorIndex % colors.length];

                /* 遍历区域所有行，进行颜色填充。 */
                /* Traverse all rows in the region and fill them with colors. */
                region.rows.forEach((row, key) => {
                    if(row)
                    {
                        row = processRegionRows(row);
                        row.forEach((item) => {
                            ctx.fillRect(item.left, key, item.right - item.left + 1, 1);
                        });
                    }
                });
            });

            resolve(canvas.toDataURL());
        };
    });
}

/**
 * 展示着色的图像。
 * Display colored image.
 *
 * @param  string coloredImageData
 * @access public
 * @return void
 */
function displayColoredImage(coloredImageData)
{
    const canvas = $('#canvas')[0];
    const ctx    = canvas.getContext('2d');
    const img    = new Image();

    img.src    = coloredImageData;
    img.onload = function(){ctx.drawImage(img, 0, 0);};
}
