/**
* jQuery.colorize
* Copyright (c) 2008-2009 Eric Karimov - ekarim57(at)gmail(dot)com | http://franca.exofire.net/jq/
* Dual licensed under MIT and GPL.
* Date: 9/15/2009
*
* @projectDescription Table colorize using jQuery.
* http://franca.exofire.net/jq/colorize
*
* @author Eric Karimov, contributor Aymeric Augustin
* @version 2.0.0
*/

jQuery.fn.colorize = function(params) {
	options = {
		altColor: '#ECF6FC',
		bgColor: '#fff',
		hoverColor: '#BCD4EC',
		hoverClass:'',
		hiliteColor: 'yellow',
		hiliteClass:'',
		oneClick: false,
		hover:'row',
		click:'row',
		banColumns: [],
		banRows:[],
		banDataClick:false,
		ignoreHeaders:true,
		nested:false
	};
	jQuery.extend(options, params);

	var colorHandler = {

		addHoverClass: function(){
			this.origColor = this.style.backgroundColor;
			this.style.backgroundColor='';
			jQuery(this).addClass(options.hoverClass);
		},

		addBgHover:function (){
			this.origColor = this.style.backgroundColor;
			this.style.backgroundColor= options.hoverColor;
		},

		removeHoverClass: function(){
			jQuery(this).removeClass(options.hoverClass);
			this.style.backgroundColor=this.origColor;
		},

		removeBgHover: function(){
			  this.style.backgroundColor=this.origColor;
		},

		checkHover: function() {
			if(checkRowBan(this)) return;
			if (!this.onfire) this.hover();
		},

		checkHoverOut: function() {
			if (!this.onfire) this.removeHover();
		},

		highlight: function() {
			if(options.hiliteClass.length>0 || options.hiliteColor != 'none')
			{
				if(checkRowBan(this)) return;
				this.onfire = true;

				if(options.hiliteClass.length>0){
					this.style.backgroundColor='';
					jQuery(this).addClass(options.hiliteClass).removeClass(options.hoverClass);
				}
				else if (options.hiliteColor != 'none') {
			         this.style.backgroundColor= options.hiliteColor;
					if(options.hoverClass.length>0) jQuery(this).removeClass(options.hoverClass);
				}
			}
		},
		stopHighlight: function() {
		    this.onfire = false;
			this.style.backgroundColor = (this.origColor)?this.origColor:'';
			jQuery(this).removeClass(options.hiliteClass).removeClass(options.hoverClass);
		}
	}


	 function  processCells (cells, idx, func) {
		var colCells = getColCells(cells, idx);

		jQuery.each(colCells, function(index, cell2) {
			func.call(cell2);
		});

	    function getColCells (cells, idx) {
			var arr = [];
			for (var i = 0; i < cells.length; i++) {
				if (cells[i].cellIndex == idx)
					arr.push(cells[i]);
			}
			return arr;
		}
	}

	function processAdapter(cells, cell, func) {
		processCells(cells, cell.cellIndex, func);
	}



  var clickHandler = {
	toggleColumnClick : function (cells) {
		var func = (!this.onfire) ? colorHandler.highlight : colorHandler.stopHighlight;
		processAdapter(cells, this, func);
	},

	toggleRowClick: function(cells) {
		row = jQuery(this).parent().get(0);
		if (!row.onfire)
			colorHandler.highlight.call(row);
		else
			colorHandler.stopHighlight.call(row);
	},

     oneClick : function (clicked){
			if(clicked != null) {
				   if (this.isRepeatClick())
				   {
					   this.stopHilite();
					   this.cancel ();
				   }
				   else{
					   this.stopHilite();
					   this.hilite();
				   }
			   }
			   else{
				   this.hilite();
			   }
      },

	   oneColumnClick : function (cells) {
	       var indx = this.cellIndex;
		   function repeat (){
		   	  return (cells.clicked == indx);
		   }
		   Column.handleClick (this, cells, indx, repeat);
	   },

	    oneRowClick  : function (cells) {
	           var row = jQuery(this).parent().get(0);
	           var indx = row.rowIndex;
	           function repeat (){
	                 return (cells.rowClicked == indx);
	            }
	           Row.handleClick (this, cells, row.rowIndex, repeat);
	    },

	    oneColumnRowClick : function (cells) {

				   var indx = this.cellIndex;
				   var row = jQuery(this).parent().get(0);

				   function isRepeatColumn(){
					   return (cells.clicked == indx && cells.rowClicked  == row.rowIndex) ;
				   }

				   function isRepeatRow(){
					   return (cells.rowClicked  == row.rowIndex && this.cellIndex == cells.clicked) ;
				   }

			    Column.handleClick (this, cells,indx, isRepeatColumn);
				Row.handleClick (this, cells,row.rowIndex, isRepeatRow);
           }
	 }

	var Column ={

	      init: function(cell, cells, indx){
			  this.cell = cell;
			  this.cells = cells;
		 	  this.indx = indx;
		  },

		  handleClick: function(cell, cells, indx, func){
              this.init(cell, cells, indx);
              this.isRepeatClick = func;
              clickHandler.oneClick.call (this, cells.clicked);
		  },
	     stopHilite : function(){
	        processCells(this.cells, this.cells.clicked, colorHandler.stopHighlight);
	    },
	    hilite : function(){
	        processAdapter(this.cells, this.cell, colorHandler.highlight);
	        this.cells.clicked  = this.indx;
	    },
	    cancel: function(){
	         this.cells.clicked = null;
	     }
	 }

	var Row ={
	      init: function(cell, cells, indx){
		  		this.cell = cell;
		  		this.cells = cells;
		  		this.indx = indx;
		  },
		  handleClick: function(cell, cells, indx, func){
		        this.init(cell, cells, indx);
		        this.isRepeatClick = func;
		        clickHandler.oneClick.call (this, cells.rowClicked);
		  },
	      stopHilite : function(){
	         colorHandler.stopHighlight.call(clickHandler.tbl.rows[this.cells.rowClicked]); // delete the selected row
	     },
	     hilite : function(){
	          var row = jQuery(this.cell).parent().get(0);
			  if(options.hover=='column')  colorHandler.addBgHover.call (row);
	          colorHandler.highlight.call(row); // the current row is set to select
	          this.cells.rowClicked = this.indx; //the current row is recorded

	     },
	     cancel: function(){
	         this.cells.rowClicked = null;
	     }
	 }

    function isDataCell(){
	     return (this.nodeName == 'TD');
    }

	function checkBan() {
		return (jQuery.inArray(this.cellIndex, options.banColumns) != -1) ;
	}

	function checkRowBan(cell){
			if(options.banRows.length>0){
				var row = jQuery(cell).parent().get(0);
				return jQuery.inArray(row.rowIndex, options.banRows) != -1;
			}
			else
				return false;
	}

	function attachHoverHandler(){
		this.hover = optionsHandler.hover;
		this.removeHover = optionsHandler.removeHover;
	}

	function handleColumnHoverEvents(cell, cells){
		attachHoverHandler.call (cell);
		cell.onmouseover = function() {
			if (checkBan.call(this)) return;
			processAdapter(cells, this, colorHandler.checkHover);
		}
		cell.onmouseout = function() {
			if (checkBan.call(this)) return;
			processAdapter(cells, this, colorHandler.checkHoverOut);
		}
	}

	function handleRowHoverEvents(cell, cells){
		row = jQuery(cell).parent().get(0);
		attachHoverHandler.call (row);
		row.onmouseover = colorHandler.checkHover ;
		row.onmouseout = colorHandler.checkHoverOut ;
	}

	function handleRowColHoverEvents(cell, cells){
		handleRowHoverEvents(cell, cells);
		handleColumnHoverEvents(cell, cells);
	}


	var optionsHandler ={
		setHover: function(){
			if(options.hoverClass.length>0){
				this.hover = colorHandler.addHoverClass;
				this.removeHover = colorHandler.removeHoverClass;
			}
			else{
				this.hover = colorHandler.addBgHover;
				this.removeHover = colorHandler.removeBgHover;
			}
		},

		getRowClick : 	function (){
			if(options.oneClick)
				return clickHandler.oneRowClick;
			else
				return clickHandler.toggleRowClick;
		},

		getColumnClick : 	function (){
			if(options.oneClick)
				return clickHandler.oneColumnClick;
			else
				return clickHandler.toggleColumnClick;
		},
		getRowColClick:function(){
			return clickHandler.oneColumnRowClick;
		}
	}

	var handler = {
		clickFunc : getClickHandler(),
		handleHoverEvents :getHoverHandler()
	};

	function getHoverHandler(){
		if(options.hover=='column')
			return handleColumnHoverEvents;
		else if(options.hover=='cross')
			return handleRowColHoverEvents;
		else
			return handleRowHoverEvents;
	}

	function getClickHandler(){
		if(options.click=='column')
			return optionsHandler.getColumnClick();
		else if(options.click =='cross')
			return optionsHandler.getRowColClick();
		else
			return  optionsHandler.getRowClick();
	}

	return this.each(function() {

		if (options.altColor!='none') {
			var odd, even;
			odd = even =(options.ignoreHeaders)? 'tr:has(td)': 'tr';
			if(options.nested){
				odd  +=  ':nth-child(odd)';
				even += ':nth-child(even)';
			}
			else{
				odd+= ':odd';
				even += ':even';
			}
		     jQuery(this).find(even).css('background', options.bgColor);
		     jQuery(this).find(odd).css('background', options.altColor);
        }

		if(options.columns)
			alert("The 'columns' option is deprecated.\nPlease use the 'click' and 'hover' options instead.");

    	if (jQuery(this).find('thead tr:last th').length > 0)
			 var cells = jQuery(this).find('td, thead tr:last th');
		else
			var cells = jQuery(this).find('td,th');

		cells.clicked = null;

		if (jQuery.inArray('last', options.banColumns) != -1){
			if(this.rows.length>0){
				options.banColumns.push(this.rows[0].cells.length-1);
			}
		}

	    optionsHandler.setHover();
		clickHandler.tbl = this;

		jQuery.each(cells, function(i, cell) {
			 handler.handleHoverEvents (this, cells);
			 $(this).bind("click", function(e) {
				if(checkBan.call(this)) return;
			 	if(options.banDataClick && isDataCell.call(this)) return;
				handler.clickFunc.call(this, cells);
			});
		});
	});
 }

