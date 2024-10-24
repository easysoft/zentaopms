/**
 * 修改引用题目。
 * Change quote title.
 *
 * @access public
 * @return void
 */
window.changeQuoteTitle = function()
{
    let fields          = [];  //  所有的多列填空题的配置项
    let requiredFields  = [];  //  多列填空题必填的标题id
    let citationItems   = [];  //  选择列picker组件的下拉项
    let isMulticolumn   = false;
    let multicolumnStep = false

    const quoteQuestions = $('.options-quote-title').data('quoteQuestions');
    const quoteTitle     = $('input[name="options[quoteTitle]"]').val();
    const selectColumn   = $('.options-quote-title').data('selectColumn');
    const citation       = $('.quote-citation').data('citation');
}