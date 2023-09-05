window.setCheckedCookie = function() {
    var checkeds = [];
    var $checkboxes = $('#mainContent .main-table tbody>tr input[type="checkbox"][name^=caseIDList]:checked');
    $checkboxes.each(function() {
        checkeds.push($(this).val());
    });
    $.cookie('checkedItem', checkeds.join(','), {expires: config.cookieLife, path: config.webRoot});
};

/**
 * Get checked items.
 *
 * @access public
 * @return array
 */
function getCheckedItems()
{
    var checkedItems = [];
    $('#caseForm [name^=caseIDList]:checked').each(function(index, ele)
    {
        checkedItems.push($(ele).val());
    });
    return checkedItems;
};

/**
 * Confirm batch delete cases.
 *
 * @param  string $actionLink
 * @access public
 * @return void
 */
function confirmBatchDelete(actionLink)
{
    if(confirm(batchDelete)) setFormAction(actionLink);
    return false;
}

$(function()
{
    if($('#caseList thead th.c-title').width() < 150) $('#caseList thead th.c-title').width(150);

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();

    $('#importToLib').on('click', function()
    {
        var caseIdList = '';
        $("input[name^='caseIDList']:checked").each(function()
        {
            caseIdList += $(this).val() + ',';
            $('#caseIdList').val(caseIdList);
        });
    });

    $('input[name^="showAutoCase"]').click(function()
    {
        var showAutoCase = $(this).is(':checked') ? 1 : 0;
        $.cookie('showAutoCase', showAutoCase, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
});

var runCase = false;
/**
 * Define triggerModal hidden event.
 *
 * @access public
 * @return void
 */
function triggerHidden()
{
    $('#triggerModal').on('hidden.zui.modal', function()
    {
        if(runCase == true) window.location.reload();
    });
}

//sort js
var DtSort = {};

DtSort.defaultConfig = {
    idAttrName: 'data-id',
    parentAttrName: 'data-parent',
    productAttrName: 'data-product',
    orderAttrName: 'data-order',
    nestPathAttrName: 'data-nest-path',
    isSceneAttrName: 'data-is-scene',
    moveBuffer: 2,
    canMove: function(source, sourceMgr){return true;},
    canAccept: function(source, target,sameLevel, sourceMgr, targetMgr){return true;},
    finish: function(source, target, sameLevel , sourceMgr, targetMgr){},
    movingClass: 'tr-moving',
    acceptableClass: 'tr-acceptable',
    indicatorYOffset: 0.5
};

DtSort.Table = function(options)
{
    this.options = $.extend({},DtSort.defaultConfig, options);

    this.indicator = undefined;

    this.sourceRow = undefined;
    this.targetRow = undefined;

    this.mousePress = false;

    if(this.options.container == undefined)
        return;

    this.$container = $(this.options.container);

    this.overRow = undefined;
    this.downPosition = undefined;
    this.rowList = [];

    this.init();

    var that = this;
    //解决表格展开折叠按钮单击导致checkbox自动选中功能，临时先放在这里
    this.$container.find("tr").on("click",'.table-nest-toggle', function(e)
    {
        e.stopPropagation();
        var table = $('#caseForm').data('zui.table');
        var $row = $(e.currentTarget).closest("tr");
        var dataId = $row.attr("data-id");
        table.toggleNestedRows(dataId,undefined,true);
        that.measure();
    })
}

DtSort.Table.prototype.init = function()
{
    var that = this;

    $(document).on("mousedown", this.options.container,  function(e){
        that.mousePress = true;
        that.mouseDown(DtSort.tools.mousePos(e));
    });

    $(document).on("mousemove", this.options.container,  function(e){
        that.mouseMove(DtSort.tools.mousePos(e));
    });

    $(document).on("mouseup", function(e){
        that.mouseUp(DtSort.tools.mousePos(e));
        that.mousePress = false;
    });

    $(window).resize(function(){
        that.measure();
    })

    that.measure();
}

DtSort.Table.prototype.measure = function()
{
    this.rowList = [];

    var $rows = this.$container.find("tr");
    for(var i=0;i<$rows.length;i++)
    {
        $row = $($rows[i]);

        var rowIndex = i;
        var id = $row.attr(this.options.idAttrName);
        var parent = $row.attr(this.options.parentAttrName);
        var product = $row.attr(this.options.productAttrName);
        var order = $row.attr(this.options.orderAttrName);
        var nestPath = $row.attr(this.options.nestPathAttrName);
        var isScene = $row.attr(this.options.isSceneAttrName);

        var pos = DtSort.tools.elementPos($rows[i]);
        var size = DtSort.tools.elementSize($rows[i]);

        if($row.hasClass("table-nest-hide") && i>0)
        {
            size = {w:this.rowList[0].boundary.w, h:this.rowList[0].boundary.h};
        }

        if(isScene == 1 && nestPath == undefined)
            nestPath = "," + id + ",";

        this.rowList.push({
            id: id,
            index: i,
            parent: parent,
            product: product,
            order: order,
            nestPath: nestPath,
            isScene: isScene,
            boundary: {x:pos.x, y:pos.y, w:size.w, h:size.h},
            $dom: $row
        });
    }
}

DtSort.Table.prototype.getNextEle = function(currentIndex)
{
    if(currentIndex+1 >= this.rowList.length) return -1;
    for(var i=currentIndex+1; i<this.rowList.length;i++)
    {
        if(this.rowList[i].boundary.y > 0) return i;
    }
}

DtSort.Table.prototype.pick = function(pos)
{
    if(pos == undefined) return;
    if(this.rowList.length == 0) return undefined;

    var hitIndex = -1;

    var x = this.rowList[0].boundary.x;
    var y = this.rowList[0].boundary.y;

    for(var i=0; i<this.rowList.length;i++)
    {
        if(this.rowList[i].$dom.hasClass("table-nest-hide")) continue;

        var boundary = this.rowList[i].boundary;
        var maxH = boundary.y+boundary.h;
        var nextIndex = this.getNextEle(i);
        if(nextIndex != -1)
        {
            maxH = this.rowList[nextIndex].boundary.y;
        }
        if(boundary.y <= pos.y && pos.y < maxH)
        {
            hitIndex = i;
            break;
        }
    }

    if(hitIndex < 0) return undefined;

    var hitRow = this.rowList[hitIndex];
    if(this.rowList[hitIndex].isScene == 1)
    {
        var hitRows = [hitRow]
        for(var i=hitIndex+1; i<this.rowList.length;i++)
        {
            if(this.rowList[i].nestPath != undefined && this.rowList[i].nestPath.indexOf(hitRow.nestPath) ==0)
                hitRows.push(this.rowList[i]);
        }
        return new DtSort.NestRow(this,hitRows);
    }
    else
    {
        return new DtSort.NormalRow(this,hitRow);
    }
}

DtSort.Table.prototype.mouseDown = function(pos)
{
    this.downPosition = pos;
};

DtSort.Table.prototype.mouseMove = function(pos)
{
    if(this.downPosition == undefined) return ;
    var result = this.pick(this.sourceRow == undefined ? this.downPosition : pos);

    if(this.mousePress == false || DtSort.tools.distance(this.downPosition,pos)<=this.options.moveBuffer)
    {
        if(this.overRow != undefined) this.overRow.removeCanMoveIndicator();

        this.overRow = result;

        if(this.overRow != undefined && this.options.canMove(result.getRow(),result)) this.overRow.setCanMoveIndicator();
    }
    else
    {
        if(this.overRow != undefined) this.overRow.removeCanMoveIndicator();
        this.overRow = undefined;

        this.$container.css("cursor","move");
        this.$container.addClass("none-select");

        if(this.indicator != undefined)
        {
            var sourceRowPos = this.getRowPos(this.sourceRow.getRow());
            var yOffset = this.options.indicatorYOffset * this.sourceRow.getRow().boundary.h;
            var x = sourceRowPos.x;
            var y = pos.y + yOffset;
            this.indicator.$dom.css({position:"absolute", top:y+"px", left:x+"px"});
        }

        if(this.sourceRow == undefined)
        {
            this.sourceRow = result;
            this.sourceRow.setMovingCss();
            //make drag indicator
            this.setSourceIndicator(pos);
        }
        else
        {
            if(result == undefined)
            {
                if(this.targetRow != undefined) this.targetRow.removeAcceptCss();
                this.targetRow = undefined;
            }
            else
            {
                if(this.sourceRow.include(result.getRow()))
                {
                    if(this.targetRow != undefined)
                    {
                        this.targetRow.removeAcceptCss();
                        this.targetRow = undefined;
                    }
                    return;
                }

                var isSameParent = this.sourceRow.getRow().parent == result.getRow().parent;
                var acceptable = this.options.canAccept(this.sourceRow.getRow(),result.getRow(),isSameParent,this.sourceRow,result);
                if(acceptable == true)
                {
                    if(this.targetRow != undefined) this.targetRow.removeAcceptCss();
                    this.targetRow = result;
                    this.targetRow.setAcceptCss();
                }
                else
                {
                    if(this.targetRow != undefined) this.targetRow.removeAcceptCss();
                    this.targetRow = undefined;
                }
            }
        }
    }
};

DtSort.Table.prototype.mouseUp = function(pos)
{
    this.sourceRow && this.sourceRow.removeMovingCss();
    this.targetRow && this.targetRow.removeAcceptCss();
    this.$container.css("cursor","pointer");
    this.$container.removeClass("none-select");

    this.removeSourceIndicator();

    if(this.mousePress == true && this.sourceRow != undefined && this.targetRow != undefined)
    {
        var isSameParent = this.sourceRow.getRow().parent == this.targetRow.getRow().parent;
        this.options.finish(this.sourceRow.getRow(),this.targetRow.getRow(),isSameParent,this.sourceRow,this.targetRow,function(afterCommand){});
    }

    this.sourceRow = undefined;
    this.targetRow = undefined;
    this.downPosition = undefined;
};

DtSort.Table.prototype.getRowPos = function(row)
{
    var x = this.rowList[0].boundary.x;
    var y = this.rowList[1].boundary.y;

    for(var i=0;i<this.rowList.length;i++)
    {
        if(this.rowList[i].$dom.hasClass("table-nest-hide")) continue;

        if(this.rowList[i] == row) break;

        y += this.rowList[i].boundary.h;
    }

    return {x:x, y:y};
}

DtSort.Table.prototype.setSourceIndicator = function(pos)
{
    var row = this.sourceRow.getRow();

    var $cloneRow = row.$dom.clone();
    $cloneRow.css("opacity",1);
    var $table = this.$container.closest("table").clone();
    $table.empty();
    $table.find("thead tr").css("visibility","hidden");

    var $busCells = row.$dom.find("td");
    var $copyCells = $cloneRow.find("td");
    for(var i=0;i<$busCells.length;i++)
    {
        $($copyCells[i]).width($($busCells[i]).width());
    }

    $table.append($cloneRow);

    $root = $("<div class='main-table' style='position:absolute'></div>");
    $root.height(row.boundary.h);
    $root.width(row.boundary.w);
    $root.append($table);
    $root.css("opacity",0.75);
    $root.css("z-index",99999);

    $cloneRow.css({"background":"#FFFFFF"});
    $root.addClass("none-select");
    $table.css("cursor","move");

    //var yOffset = this.options.indicatorYOffset * row.boundary.h;
    //$root.css({position: "absolute", top: rowPos.y + yOffset + "px", left: rowPos.x + "px"});

    this.indicator = {$dom:$root};

    $("body").append($root);
}

DtSort.Table.prototype.removeSourceIndicator = function()
{
    this.indicator && this.indicator.$dom.remove();
    this.indicator = undefined;
}

DtSort.RowBase = function(table){ this.table = table;}

DtSort.RowBase.prototype.setCanMoveIndicator = function(){ this.table.$container.css("cursor","move");}

DtSort.RowBase.prototype.removeCanMoveIndicator = function(){ this.table.$container.css("cursor","pointer");}

DtSort.NestRow = function(table, rows)
{
    DtSort.RowBase.call(this,table);
    this.rows = rows;
};

DtSort.NestRow.prototype = Object.create(DtSort.RowBase.prototype);

DtSort.NestRow.prototype.getIndex = function(){ return this.rows[0].index;}

DtSort.NestRow.prototype.getRow = function(){ return this.rows[0];}

DtSort.NestRow.prototype.include = function(row)
{
    for(var one of this.rows)
    {
        if(one.id == row.id) return true;
    }

    return false;
}

DtSort.NestRow.prototype.setMovingCss = function()
{
    for(var one of this.rows)
    {
        one.$dom.addClass(this.table.options.movingClass);
    }
}

DtSort.NestRow.prototype.removeMovingCss = function()
{
    for(var one of this.rows)
    {
        one.$dom.removeClass(this.table.options.movingClass);
    }
}

DtSort.NestRow.prototype.setAcceptCss = function(){ this.rows[0].$dom.addClass(this.table.options.acceptableClass);}

DtSort.NestRow.prototype.removeAcceptCss = function(){ this.rows[0].$dom.removeClass(this.table.options.acceptableClass);}

DtSort.NestRow.prototype.toString = function(){ return langRowIndex + ' ' + this.getIndex() + ' [' + langNestTotal + ': '+ this.rows.length +']'}

DtSort.NormalRow = function(table, row)
{
    DtSort.RowBase.call(this,table);
    this.row = row;
};

DtSort.NormalRow.prototype = Object.create(DtSort.RowBase.prototype);

DtSort.NormalRow.prototype.getIndex = function()
{
    return this.row.index;
}

DtSort.NormalRow.prototype.getRow = function(){ return this.row;}
DtSort.NormalRow.prototype.include = function(row){ return this.row.id == row.id;}
DtSort.NormalRow.prototype.setMovingCss = function(){ this.row.$dom.addClass(this.table.options.movingClass);}
DtSort.NormalRow.prototype.removeMovingCss = function(){ this.row.$dom.removeClass(this.table.options.movingClass);}
DtSort.NormalRow.prototype.setAcceptCss = function(){ this.row.$dom.addClass(this.table.options.acceptableClass);}
DtSort.NormalRow.prototype.removeAcceptCss = function(){ this.row.$dom.removeClass(this.table.options.acceptableClass);}
DtSort.NormalRow.prototype.toString = function(){ return langRowIndex + ' ' + this.getIndex() + ' [' + langNormal + ']';}

DtSort.tools = {
    elementSize: function(el)
    {
        let w = el.clientWidth || el.offsetWidth;
        let h = el.clientHeight || el.offsetHeight;

        return { w: w, h: h }
    },
    elementPos: function(el)
    {
        if (el.parentNode === null || el.style.display == 'none')
        {
            return false;
        }
        var parent = null;
        var pos = [];
        var box;
        if (el.getBoundingClientRect)
        {
            // IE
            box = el.getBoundingClientRect();
            var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
            var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
            return {
                x: box.left + scrollLeft,
                y: box.top + scrollTop
            };
        }
        else if (document.getBoxObjectFor)
        {
            box = document.getBoxObjectFor(el);
            var borderLeft = (el.style.borderLeftWidth) ? parseInt(el.style.borderLeftWidth) : 0;
            var borderTop = (el.style.borderTopWidth) ? parseInt(el.style.borderTopWidth) : 0;
            pos = [box.x - borderLeft, box.y - borderTop];
        }
        else
        {
            // safari & opera
            pos = [el.offsetLeft, el.offsetTop];
            parent = el.offsetParent;
            if (parent != el)
            {
                while (parent)
                {
                    pos[0] += parent.offsetLeft;
                    pos[1] += parent.offsetTop;
                    parent = parent.offsetParent;
                }
            }
            if (ua.indexOf('opera') != -1 || (ua.indexOf('safari') != -1 && el.style.position == 'absolute'))
            {
                pos[0] -= document.body.offsetLeft;
                pos[1] -= document.body.offsetTop;
            }
        }
        if (el.parentNode)
        {
            parent = el.parentNode;
        } else {
            parent = null;
        }
        while (parent && parent.tagName != 'BODY' && parent.tagName != 'HTML')
        {
            // account for any scrolled ancestors
            pos[0] -= parent.scrollLeft;
            pos[1] -= parent.scrollTop;
            if (parent.parentNode)
            {
                parent = parent.parentNode;
            }
            else
            {
                parent = null;
            }
        }
        return {
            x: pos[0],
            y: pos[1]
        };
    },
    mousePos: function(event)
    {
        var e = event || window.event;
        var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
        var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
        var x = e.pageX || e.clientX + scrollX;
        var y = e.pageY || e.clientY + scrollY;

        return { x: x, y: y };
    },
    distance(p1, p2)
    {
        var dx = p1.x - p2.x;
        var dy = p1.y - p2.y;

        return Math.pow((dx * dx + dy * dy), 0.5);
    }
};

DtSort.sort = function(options)
{
    var dtSort = new DtSort.Table(options);
    return dtSort;
};


//tree js
$(function()
{
    $('#caseTableList').on('click', '.c-id a,.c-name a,.c-actions a', function(e)
    {
        e.stopPropagation();
    });

    $('#caseTableList').on('click', '.row-case', function(e)
    {
        var $row = $(this);
        $row.toggleClass('checked');

        updateChildrenCheckboxes($row);
        updatePrarentCheckbox($row);
    });

    function updateChildrenCheckboxes($row)
    {
        var rowID     = $row.data('id');
        var isChecked = $row.hasClass('checked');

        if ($row.hasClass('has-nest-child'))
        {
            $('#caseTableList tr[data-nest-parent="'+rowID+'"]').each(function(){
                $(this).toggleClass('checked', isChecked);
                $(this).find('input:checkbox').prop('checked', isChecked);

                updateChildrenCheckboxes($(this));
            });
        }
    }

    /* Update parent checkbox */
    function updatePrarentCheckbox($row)
    {
        var rowID    = $row.data('id');
        var parentID = $row.attr('data-nest-parent');
        var $parent  = $('#caseTableList>tr[data-id="' + parentID + '"]');
        if(parentID && parentID !== '0')
        {
            var isAllChecked = true;
            $('#caseTableList tr[data-nest-parent="'+parentID+'"]').each(function(){
                if (!$(this).hasClass('checked'))
                {
                    isAllChecked = false;
                }
            });
            $parent.toggleClass('checked', isAllChecked);
            $parent.find('input:checkbox').prop('checked', isAllChecked);
            updatePrarentCheckbox($parent);
        }
    }

    //only scene
    $('input[name^="onlyScene"]').click(function(){
        var onlyScene = $(this).is(':checked') ? 1 : 0;
        $.cookie('onlyScene', onlyScene, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    $("input[name^=caseIDList]").change(function(){
        var trList = $("#caseTableList").find("tr");
        var selectedCaseNum = 0;
        for(var i=0; i<trList.length; i++)
        {
            var $tr     = $(trList[i]);
            var isScene = $tr.attr("data-is-scene");
            if(isScene == "1") continue;
            var $cbx = $tr.find(".checkbox-primary").find("input");
            if($cbx.is(':checked') == true) selectedCaseNum ++;
        }

        var group = $(".table-actions").find(".btn-group:first");
        if(selectedCaseNum > 0)
        {
            group.show();
        }
        else
        {
            group.hide();
        }
    });
});
