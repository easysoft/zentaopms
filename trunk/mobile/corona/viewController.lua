-- viewController.lua, View Controller Library
--
-- Version 1.0, currently supports tabs
--
-- Copyright (C) 2010 ANSCA Inc. All Rights Reserved.
--
-- Permission is hereby granted, free of charge, to any person obtaining a copy of 
-- this software and associated documentation files (the "Software"), to deal in the 
-- Software without restriction, including without limitation the rights to use, copy, 
-- modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
-- and to permit persons to whom the Software is furnished to do so, subject to the 
-- following conditions:
-- 
-- The above copyright notice and this permission notice shall be included in all copies 
-- or substantial portions of the Software.
-- 
-- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
-- INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR 
-- PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE 
-- FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
-- OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
-- DEALINGS IN THE SOFTWARE.

module(..., package.seeall)

--initial values
local screenW, screenH = display.contentWidth, display.contentHeight
local viewableScreenW, viewableScreenH = display.viewableContentWidth, display.viewableContentHeight
local screenOffsetW, screenOffsetH = display.contentWidth -  display.viewableContentWidth, display.contentHeight - display.viewableContentHeight

function newTabBar(params)
	local background = params.background
	local tabs = params.tabs
	local default = params.default
	local over = params.over
	local onRelease = params.onRelease

	local tabBar = display.newGroup()
	
	if background then
		local tabBG = display.newImage("tabBar.png")
		tabBar:insert(tabBG)
	end

	--check for tab and tab over images specified by the user
	if not default then 
		--if no tab images, use tab1.png, tab2.png, etc.
		default = {}
		for i=1, #tabs do
			table.insert(default, "tab".. i ..".png")	 
		end
	end
	if not over then 
		--if no tab over images, use tab1_over.png, tab2_over.png, etc.
		over = {}
		for i=1, #tabs do
			table.insert(over, "tab".. i .."_over.png")	 
		end
	end
	
	tabBar.y = math.floor(screenH - tabBar.height - display.screenOriginY)
	tabBar.x = 0
		
	--create the tabs
	for i=1, #tabs do 
		local tab = ui.newButton{ 
				default = default[i], 
				over = over[i], 
				onRelease = onRelease,
				text = tabs[i],
                                textColor = {0, 0, 0, 165},
				font = "Helvetica",
				size = 14,
			}
		tabBar:insert(tab)
		
		local numberOfTabs = #tabs
		local tabButtonWidth = tab.width
		local totalButtonWidth = tabButtonWidth * numberOfTabs
		local tabSpacing = (screenW - totalButtonWidth)/(numberOfTabs+1)

		tab.x = tab.width*(i-1) + tabSpacing * i + tab.width*0.5
		tab.y = tab.height*0.5 

		tab.id = i
	end

	tabBar.selected = function(target)
			if not target then target = tabBar[2] end
			if tabBar.highlight then tabBar:remove(tabBar.highlight) end
			
			local highlight = ui.newButton{ 
					default = over[target.id], 
					over = default[target.id], 
					onRelease = onRelease,
					text = tabs[target.id],
					font = "Helvetica",
					size = 14,
				}
			highlight.id = target.id
			tabBar:insert(highlight)
			
			highlight.x = target.x
			highlight.y = target.y 

			tabBar.highlight = highlight
	end
		
	return tabBar
end
