const colors = ['#CFE7FE', '#DCEFF9', '#E0EBFC', '#DBE9FA', '#D4EEE9', '#E4DFF7', '#E7EBF6'];

/**
 * 获取模型颜色。
 * Get model colors.
 *
 * @access public
 * @return array
 */
function getModelColors()
{
    const model      = $(`#canvas_${model3cKey}`).closest('.model-canvas').data('model');
    let modelColors  = colors;
    const colorGroup = {};
    if(colorGroup[`color${model}`] && Array.isArray(colorGroup[`color${model}`])) modelColors = colorGroup[`color${model}`];

    return modelColors;
}

/**
 * 初始模型画布。
 * Initialize the model canvas.
 *
 * @access public
 * @return void
 */
window.initThinkCanvas = function()
{
    const canvas = $(`#canvas_${model3cKey}`)[0];
    const ctx    = canvas.getContext('2d');
    const image  = new Image();
    const {model, mode} = $(`#canvas_${model3cKey}`).closest('.model-canvas').data();

    if(mode == 'view')
    {
        let $mainContent = $('#mainContent');
        $mainContent.addClass('load-indicator').addClass('loading').css('height', Math.max(200, Math.floor($(window).height() - $('#header').outerHeight() - $('#mainMenu').outerHeight())));
        $('#main').removeClass('load-indicator');
    }

    image.src    = `data/thinmory/thumbnail/init${model}.png`;
    image.onload = function()
    {
        const size = getCanvasSize(image.width, image.height);
        canvas.width = size.width;
        canvas.height = size.height;
        ctx.drawImage(image, 0, 0, size.width, size.height);

        const imageData = ctx.getImageData(0, 0, size.width, size.height);
        const pixels    = binarizeImage(imageData);
        ctx.putImageData(pixels, 0, 0);

        const binarizeImageUrl = canvas.toDataURL();
        colorRegions(binarizeImageUrl).then((data) => {
            const coloredRegions = data.regions;
            const pixelData      = data.pixelData;

            displayColoredImage(data.image);

            if(mode == 'preview')
            {
                renderRegionInput(coloredRegions);

                /* 点击画布外区域取消高亮。*/
                /* Click on the area outside the canvas to unhighlight.*/
                $(document).on('click', function(event)
                {
                    const rect    = canvas.getBoundingClientRect();
                    const canvasX = rect.left;
                    const canvasY = rect.top;
                    const mouseX  = event.clientX;
                    const mouseY  = event.clientY;

                    /* 恢复整个画布的原始状态。 */
                    ctx.putImageData(pixelData, 0, 0);

                    /* 检测鼠标位置是否在特定区域内。 */
                    /* Check if the mouse position is within a specific area. */
                    if(canvasX > 0 && mouseX >= canvasX && mouseX <= (canvasX + canvas.width) && mouseY >= canvasY && mouseY <= (canvasY + canvas.height))
                    {
                        handleCanvasClick(mouseX, mouseY, canvas, ctx, coloredRegions);
                    }
                });
            }
            else
            {
                afterFinish();
                renderRegionTitles(coloredRegions);
                coloredRegions.forEach((region, index) => {
                    calculateCardPosition(region, index);
                });
            }
        });
    };
}

function afterFinish()
{
    setTimeout(function()
    {
        $('#mainContent').css('height', '').removeClass('loading').removeClass('load-indicator');
        $('#main').addClass('load-indicator');
    }, 200);
};

/**
 * 计算卡片位置。
 * Calculate card position.
 *
 * @param  object region
 * @param  number index
 * @access public
 * @return void
 */
function calculateCardPosition(region, index)
{
    const h         = 180; // 卡片高度
    const minW      = 180; // 最小宽度
    const maxW      = 280; // 最大宽度
    const d         = 8;   // 间距
    const rows      = Object.values(region.rows);
    const rowsCount = rows.length;
    const positions = [];

    let r = 0;
    while (r < rowsCount)
    {
        let totalWidth    = 0;
        let validStartRow = -1;

        for(let i = r; i < r + h && i < rowsCount; i++)
        {
            const rowsData = rows[i];
            let isValid    = false;

            for(let j = 0; j < rowsData.length; j++)
            {
                const width = rowsData[j][1] - rowsData[j][0];
                if(width >= minW)
                {
                    totalWidth += width;
                    isValid     = true;
                    break;
                }
            }
            if(isValid) validStartRow = i;
        }

        if(validStartRow != -1)
        {
            let {left, right} = getMaxLeftAndMinRight(rows, validStartRow, h);
            right   -= 16;
            let rowN = Math.floor((right - left + d) / (minW + d));
            let w    = (right - left + d) / rowN;

            for(let i = 0; i < rowN; i++)
            {
                let blockLeft  = left + i * (w + d);
                let blockTop   = rows[validStartRow][0][2];
                let blockWidth = Math.min(w, maxW);
                positions.push([blockLeft, blockTop, blockWidth]);
            }
            r += h;
        }
        else
        {
            r++;
        }
    }

    let isFirst  = true;
    let firstTop = 0;
    positions.forEach((item) => {
        if(isFirst)
        {
            isFirst  = false;
            firstTop = item[1];
        }
        else
        {
            if(item[1] > firstTop) item[1] += d;
        }
    });
    const cards = $(`#runResult .card.in_area-${index}`);
    if(cards.length <= positions.length)
    {
        cards.each((i, card) => {
            $(card).css({
                left: `${positions[i][0]}px`,
                top: `${positions[i][1]}px`,
                width: `${positions[i][2]}px`,
                height: `${h}px`,
            });
        });
        $('.card').removeClass('hidden');
    }
    else
    {
        // TODO: 处理卡片数量超过位置数量的情况
    }
}

/**
 * 判断在同一行。
 * Judge on the same line.
 *
 * @param  array  currentItem
 * @param  array  nextItem
 * @param  number offset
 * @access public
 * @return boolean
 */
function isSameLine(currentItem, nextItem, offset)
{
    return currentItem[1] == nextItem[1] || Math.abs(currentItem[1] - nextItem[1]) < offset;
}

function groupPositions(data, offset)
{
    const groupedData = new Map();

    /* 分组数据 */
    /* Group data. */
    data.forEach(item => {
        const key = item[2];
        if (!groupedData.has(key)) groupedData.set(key, []);
        groupedData.get(key).push(item);
    });

    /* 合并临近的行 */
    /* Merge adjacent rows. */
    const mergedGroups = [];
    let currentGroup   = null;
    let lastItem       = null;

    for(let [key, items] of groupedData)
    {
        if(!currentGroup || !isSameLine(lastItem, items[items.length - 1], offset))
        {
            if(currentGroup) mergedGroups.push(currentGroup);
            currentGroup = {key, items};
        }
        else
        {
            currentGroup.items.push(...items);
        }
        lastItem = items[items.length - 1];
    }

    if(currentGroup) mergedGroups.push(currentGroup);

    return mergedGroups;
}

/**
 * 使用偏移量计算位置。
 * Calculate positions with offset.
 *
 * @param  array  groups
 * @access public
 * @return array
 */
function calcPositionsWithOffset(groups)
{
    const result = [];
    let yOffset  = 0;

    groups.forEach(group => {
        const items = group.items;
        items.forEach(item => {
            result.push([item[0], item[1] + yOffset, item[2]]);
        });
        yOffset += 10;
    });

    return result;
}

/**
 * 获取最大左侧坐标和最小右侧坐标。
 * Get maxLeft and minRight.
 *
 * @param  array  rows
 * @param  number start
 * @param  number h
 * @access public
 * @return object
 */
function getMaxLeftAndMinRight(rows, start, h)
{
    const rowLength = rows[Math.min(start + h - 1, rows.length - 1)]?.length || 0;
    let left        = rows[start][0][0] || 0;
    let right       = rowLength ? rows[Math.min(start + h - 1, rows.length - 1)][rowLength - 1][1] : 0;
    for(let i = start; i < start + h; i++)
    {
        if(rows[i])
        {
            const rowData  = rows[i];
            const maxLeft  = rowData[0][0];
            const minRight = rowData[rowData.length - 1][1];

            left  = Math.max(left, maxLeft);
            right = Math.min(right, minRight);
        }
    }
    return {left, right};
}

/**
 * 处理画布点击。
 * handle canvas click.
 *
 * @param  object mouseX
 * @param  object mouseY
 * @param  object canvas
 * @param  object ctx
 * @param  array  coloredRegions
 * @access public
 * @return void
 */
function handleCanvasClick(mouseX, mouseY, canvas, ctx, coloredRegions)
{
    const rect = canvas.getBoundingClientRect();
    const x    = mouseX - rect.left;
    const y    = mouseY - rect.top;

    coloredRegions.forEach((region, index) => {
        let isInRegion = false;
        region.rows.forEach((row, key) => {
            if(parseInt(y) === key && row) isInRegion = row.some((item) => (x >= item[0] && x <= item[1]));
        });

        if(isInRegion)
        {
            if($(`#input_${model3cKey}_${index}`).length) $(`#input_${model3cKey}_${index}`)[0].focus();
            renderHighlightedRegion(index, coloredRegions[index], ctx);
        }
    });
}

$(document).on('blur', '.model-canvas input', function()
{
    $(this).attr('title', $(this).val());
});

/**
 * 渲染高亮的区域。
 * Render highlighted region.
 *
 * @param  number index
 * @param  object region
 * @param  object ctx
 * @access public
 * @return void
 */
function renderHighlightedRegion(index, region, ctx)
{
    const color    = colors[index % colors.length];
    let regionPath = new Path2D();
    region.rows.forEach((row, top) => {
        row.forEach((item) => {
            regionPath.rect(item[0], item[2], item[1] - item[0] + 1, 1);
        });
    });
    ctx.fillStyle = color;
    ctx.fill(regionPath);
}

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
    if(model3cKey == 'link') return {width: 420, height: 400};

    const mode = $(`#canvas_${model3cKey}`).closest('.model-canvas').data('mode');
    if(mode == 'view')  return {width: 2400, height: 2400};

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

            /* 将rows[currentY] 设为一个数组，以便存储多个区域段， 格式为：[left, right, top]。*/
            /* Set rows [CurrentY] as an array to store multiple regional segments. */
            if (!region.rows[currentY]) region.rows[currentY] = [];
            region.rows[currentY].push([Infinity, -Infinity, currentY]);
            let currentRow = region.rows[currentY][region.rows[currentY].length - 1];
            currentRow[0] = Math.min(currentRow[0], currentX);
            currentRow[1] = Math.max(currentRow[1], currentX);

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
    const mode    = $(`#canvas_${model3cKey}`).closest('.model-canvas').data('mode');
    const minArea = mode == 'view' ? 600 : 100;
    for(let y = 0; y < height; y++)
    {
        for(let x = 0; x < width; x++)
        {
            const index = (y * width * 4) + (x * 4);
            if(pixels[index] === 255 && !visited[y + ',' + x])
            {
                const region = getRegionInfo(pixels, visited, x, y, width, height);

                /* 过滤掉小区域。 */
                /* Filter out small regions. */
                if(region.area > minArea)
                {
                    region.rows.forEach((row, key) => {
                        region.rows[key] = processRegionRows(row);
                    });
                    regions.push(region);
                }
            }
        }
    }

    return regions;
}

/**
 * 多维数据扁平化。
 * Flatten data.
 *
 * @param  array  data
 * @access public
 * @return array
 */
function flattenData(data)
{
    const result = [];
    const stack  = data.slice();

    while (stack.length > 0)
    {
        const item = stack.pop();
        if(typeof item === 'object' && item !== null && Object.prototype.toString.call(item) === '[object Array]')
        {
            for(let i = item.length - 1; i >= 0; i--)
            {
                stack.push(item[i]);
            }
        }
        else
        {
            result.push(item);
        }
    }

    return result;
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
    data.sort((a, b) => a[0] - b[0]);

    let result      = [];
    let currentItem = [data[0][0], data[0][1], data[0][2]];

    for(let i = 1; i < data.length; i++)
    {
        if(data[i][0] === currentItem[1] + 1 && data[i][2] === currentItem[2])
        {
            currentItem[1] = data[i][1];
        }
        else if(data[i][1] === currentItem[0] - 1 && data[i][2] === currentItem[2])
        {
            currentItem[0] = data[i][0];
        }
        else
        {
            result.push([currentItem[0], currentItem[1], currentItem[2]]);
            currentItem = [data[i][0], data[i][1], data[i][2]];
        }
    }

    result.push([currentItem[0], currentItem[1], currentItem[2]]);
    return result;
}

/**
 * 绘制区域输入框。
 * Draw a region input.
 *
 * @param  object canvas
 * @param  object region
 * @param  number colorIndex
 * @param  string clientLang
 * @param  array blocks
 * @access public
 * @return void
 */
window.drawRegionInput = function(canvas, region, colorIndex, clientLang, blocks)
{
    region.minX += 30;
    region.maxY -= 10;
    const centerX   = (region.minX + region.maxX) / 2;
    const centerY   = (region.minY + region.maxY) / 2;
    const boxWidth  = 60;
    const boxHeight = 32;
    const disabled  = $(`#canvas_${model3cKey}`).closest('.model-canvas').data('disabled');

    let left = centerX - boxWidth / 2;
    let top  = centerY - boxHeight / 2;
    if(colorIndex == 1) left -= 20;
    if(colorIndex == 2) left += 30;
    if(['zh-cn', 'zh-tw'].includes(clientLang))
    {
        if([0, 4, 5].includes(colorIndex)) top -= 40;
        if(colorIndex == 4) left -= 50;
        if(colorIndex == 5) left += 60;
    }
    else
    {
        if([0, 4, 5].includes(colorIndex))
        {
            top -= 30;
            if(colorIndex == 4) left += $(window).width() > 1366 ? -50 : 50;
            if(colorIndex == 5) left += $(window).width() > 1366 ? 60 : -60;
        }
    }

    const styles = {
        position: 'absolute',
        left: `${left}px`,
        top: `${top}px`,
        width: `${boxWidth}px`,
        height: `${boxHeight}px`,
        padding: '5px',
        fontSize: '16px',
        color: '#000',
        backgroundColor: 'transparent',
    };

    /* 按照索引顺序设置area属性为a~z。 */
    /* Set the area attribute to a~z in index order. */
    const block = String.fromCharCode(97 + colorIndex);
    const input = document.createElement('input');
    const value = blocks[colorIndex] || block;
    input.type        = 'text';
    input.value       = value;
    input.title       = value;
    input.id          = `input_${model3cKey}_${colorIndex}`;
    input.name        = `blocks[]`;
    input.placeholder = blockName;
    input.disabled    = disabled;
    input.setAttribute('data-index', colorIndex);
    input.setAttribute('data-block', value);
    Object.assign(input.style, styles);

    canvas.parentNode.appendChild(input);
}

/**
 * 绘制区域标题。
 * Draw a region title.
 *
 * @param  object canvas
 * @param  object region
 * @param  number colorIndex
 * @param  array blocks
 * @access public
 * @return void
 */
window.drawRegionTitle = function(canvas, region, colorIndex, blocks)
{
    const centerX   = (region.minX + region.maxX) / 2;
    const centerY   = (region.minY + region.maxY) / 2;
    const boxWidth  = 120;
    const boxHeight = 48;

    let left   = centerX - boxWidth / 2;
    let top    = centerY - boxHeight / 2;
    let bottom = 0;

    const positionMap = {
        0: {left: left, top: 40},
        1: {left: left - 60, top: top + 300},
        2: {left: left + 70, top: top + 300},
        3: {left: left, top: top - 360},
        4: {left: left + 260, top: top - 650},
        5: {left: left - 260, top: top - 650},
        6: {left: left, top: 0, bottom: 160}
    };

    if(colorIndex in positionMap)
    {
        const {left: newLeft, top: newTop, bottom: newBottom = 'unset'} = positionMap[colorIndex];
        left   = newLeft;
        top    = newTop;
        bottom = newBottom;
    }

    const styles = {
        left: left ? `${left}px` : 'unset',
        top: top ? `${top}px` : 'unset',
        bottom: bottom ? `${bottom}px` : 'unset'
    };

    const block  = String.fromCharCode(97 + colorIndex);
    const $title = document.createElement('div');
    const value  = typeof blocks[colorIndex] == 'string' ? blocks[colorIndex] : block;
    $title.innerHTML = value;
    $title.title     = value;
    $title.id        = `title_${colorIndex}`;
    $title.className = 'w-28 p-1 item-step-title text-clip text-center absolute';
    Object.assign($title.style, styles);

    canvas.parentNode.appendChild($title);
}

/**
 * 渲染区域标题。
 * Render region titles.
 *
 * @param  array regions
 * @access public
 * @return void
 */
function renderRegionTitles(regions)
{
    const {model, blocks} = $(`#canvas_${model3cKey}`).closest('.model-canvas').data();
    let drawTitleFunc = `drawRegionTitleOf${model}`;
    drawTitleFunc     = (drawTitleFunc in window) ? window[drawTitleFunc] : window['drawRegionTitle'];
    const canvas      = $(`#canvas_${model3cKey}`)[0];

    regions.forEach((region, index) => {
        if(!$(`#title_${index}`).length) drawTitleFunc(canvas, region, index, blocks);
    });
}

/**
 * 渲染区域输入框。
 * Render region inputs.
 *
 * @param  array regions
 * @access public
 * @return void
 */
function renderRegionInput(regions)
{
    const {model, blocks, clientLang} = $(`#canvas_${model3cKey}`).closest('.model-canvas').data();
    let drawInputFunc = `drawRegionInputOf${model}`;
    drawInputFunc     = (drawInputFunc in window) ? window[drawInputFunc] : window['drawRegionInput'];
    const canvas      = $(`#canvas_${model3cKey}`)[0];

    regions.forEach((region, index) => {
        if(!$(`#input_${model3cKey}_${index}`).length) drawInputFunc(canvas, region, index, clientLang, blocks);
    });
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
    const canvas = document.createElement('canvas');
    const ctx    = canvas.getContext('2d');
    const img    = new Image();
    img.src = binaryImageData;

    return new Promise((resolve, reject) => {
        img.onload = function()
        {
            canvas.width  = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            const pixels = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
            let regions  = findRegions(pixels, canvas.width, canvas.height);

            ctx.globalCompositeOperation = 'source-over';
            ctx.globalAlpha              = 0.5;

            regions.forEach((region, colorIndex) => {
                /* 遍历区域所有行，进行颜色填充。 */
                /* Traverse all rows in the region and fill them with colors. */
                let regionPath = new Path2D();
                region.rows.forEach((row, key) => {
                    row.forEach((item) => {
                        regionPath.rect(item[0], key, item[1] - item[0] + 1, 1);
                    });
                });
                ctx.fillStyle = colors[colorIndex % colors.length];
                ctx.fill(regionPath);
            });

            const originalPixelData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            originalPixelData.willReadFrequently = true;
            resolve({image: canvas.toDataURL(), regions: regions, pixelData: originalPixelData});
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
    const canvas = $(`#canvas_${model3cKey}`)[0];
    const ctx    = canvas.getContext('2d');
    const img    = new Image();

    img.src    = coloredImageData;
    img.onload = function(){ctx.drawImage(img, 0, 0);};
}
