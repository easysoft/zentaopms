/**
 * 格式化坐标标签数据。
 * Format coordinate label data.
 *
 * @param  object params
 * @access public
 * @return array
 */
window.formatSeriesLabel = function(params)
{
    return params?.value?.toFixed(2);
}
