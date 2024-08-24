/**
 * 修改引用题目。
 * Change quote title.
 *
 * @param  event
 * @access public
 * @return void
 */
window.changeQuoteTitle = function(event)
{
    let fields          = [];  //  所有的多列填空题的配置项
    let requiredFields  = [];  //  多列填空题必填的标题id
    let citationItems   = [];  //  选择列picker组件的下拉项
    let isMulticolumn   = false;
    let multicolumnStep = false

    const quoteQuestions = $('.options-quote-title').data().quoteQuestions;
    const quoteTitle     = $('input[name="options[quoteTitle]').val();

    $.each(quoteQuestions, function(index, item) {
        const options = JSON.parse(item.options);
        if(quoteTitle && item.id == quoteTitle && options.questionType === 'multicolumn')
        {
            multicolumnStep = true;
            requiredFields  = options.requiredCols;
            fields          = options.fields;
        }
    });

    /* 渲染选择列的下拉项。Renders a drop-down item for a select column.*/
    $.each(requiredFields, function(index, key) {
        const keyValue = parseInt(key);
        citationItems.push({key: keyValue, value:keyValue, text: fields[keyValue]});
    })

}
