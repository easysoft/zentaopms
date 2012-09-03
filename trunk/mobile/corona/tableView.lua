-- tableView.lua, Table View Library
--
-- Version 1.3
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
 
--properties
 local screenW, screenH = display.contentWidth, display.contentHeight
local viewableScreenW, viewableScreenH = display.viewableContentWidth, display.viewableContentHeight
local screenOffsetW, screenOffsetH = display.contentWidth -  display.viewableContentWidth, display.contentHeight - display.viewableContentHeight

local currentTarget, detailScreen, velocity, currentDefault, currentOver, prevY
local startTime, lastTime, prevTime = 0, 0, 0
 
--methods
 
function showHighlight(event)
    local timePassed = system.getTimer() - startTime
 
    if timePassed > 100 then 
        print("highlight")
        currentDefault.isVisible = false
        currentOver.isVisible = true
        Runtime:removeEventListener( "enterFrame", showHighlight )
    end
end
  
function newListItemHandler(self, event) 
        local t = currentTarget --could use self.target.parent possibly
        local phase = event.phase
        print("touch: ".. phase)
 
        local default = self.default
        local over = self.over
        local top = self.top
        local bottom = self.bottom
        local upperLimit, bottomLimit = top, screenH - currentTarget.height - bottom

		local result = true        
        
        if( phase == "began" ) then
            -- Subsequent touch events will target button even if they are outside the contentBounds of button
            display.getCurrentStage():setFocus( self )
            self.isFocus = true

            startPos = event.y
            prevPos = event.y                                       
            delta, velocity = 0, 0
            if currentTarget.tween then transition.cancel(currentTarget.tween) end

            Runtime:removeEventListener("enterFrame", scrollList ) 
            Runtime:addEventListener("enterFrame", moveCat)

			-- Start tracking velocity
			Runtime:addEventListener("enterFrame", trackVelocity)
 
            if over then
                currentDefault = default
                currentOver = over
                startTime = system.getTimer()
                Runtime:addEventListener( "enterFrame", showHighlight )
            end
             
        elseif( self.isFocus ) then
 
            if( phase == "moved" ) then     
  
                Runtime:removeEventListener( "enterFrame", showHighlight )
                if over then 
                    default.isVisible = true
                    over.isVisible = false
                end
  
                delta = event.y - prevPos
                prevPos = event.y
                if ( t.y > upperLimit or t.y < bottomLimit ) then 
                    t.y  = t.y + delta/2
                else
                    t.y = t.y + delta       
                end
                    
            elseif( phase == "ended" or phase == "cancelled" ) then 

				lastTime = event.time
 
                local dragDistance = event.y - startPos
                --velocity = delta 
				Runtime:removeEventListener("enterFrame", moveCat)
	 			Runtime:removeEventListener("enterFrame", trackVelocity)
                Runtime:addEventListener("enterFrame", scrollList )             

                local bounds = self.contentBounds
                local x, y = event.x, event.y
                local isWithinBounds = bounds.xMin <= x and bounds.xMax >= x and bounds.yMin <= y and bounds.yMax >= y
        
                -- Only consider this a "click", if the user lifts their finger inside button's contentBounds
                if isWithinBounds and (dragDistance < 10 and dragDistance > -10 ) then
					velocity = 0
                    result = self.onRelease(event)
                end
 
                -- Allow touch events to be sent normally to the objects they "hit"
                display.getCurrentStage():setFocus( nil )
                self.isFocus = false
 
                if over then 
                    default.isVisible = true
                    over.isVisible = false
                    Runtime:removeEventListener( "enterFrame", showHighlight )
                end 
            end
        end
        
        return result
end
 
function newListItem(params)
        local data = params.data
        local default = params.default
        local over = params.over
        local onRelease = params.onRelease
        local top = params.top
        local bottom = params.bottom
        local callback = params.callback 
        local id = params.id
 
        local thisItem = display.newGroup()
 
        if params.default then
                default = display.newImage( params.default )
                thisItem:insert( default )
                default.x = default.width*.5 - screenOffsetW
                thisItem.default  = default
        end
        
        if params.over then
                over = display.newImage( params.over )
                over.isVisible = false
                thisItem:insert( over )
                over.x = over.width*.5 - screenOffsetW
                thisItem.over = over 
        end
 
        thisItem.id = id
        thisItem.data = data
        thisItem.onRelease = onRelease          
        thisItem.top = top
        thisItem.bottom = bottom
 
        local t = callback(data)
        thisItem:insert( t )
 
        thisItem.touch = newListItemHandler
        thisItem:addEventListener( "touch", thisItem )
        
        return thisItem
end
 
function newList(params) 
		local textSize = 16
        local data = params.data
        local default = params.default
        local over = params.over
        local onRelease = params.onRelease
        local top = params.top or 20
        local bottom = params.bottom or 48
        local cat = params.cat
        local order = params.order or {}
        local categoryBackground = params.categoryBackground
        local backgroundColor = params.backgroundColor
        local callback = params.callback or function(item)
	                                            local t = display.newText(item, 0, 0, native.systemFontBold, textSize)
	                                            t:setTextColor(255, 255, 255)
	                                            t.x = math.floor(t.width/2) + 20
	                                            t.y = 24 
	                                            return t
			            					end
	 
        --setup the list view                   
        local listView = display.newGroup()
        local prevY, prevH = 0, 0
        

        if cat then         
			local catTable = {}
    
        	--get the implicit categories
        	local prevCat = 0
        	for i=1, #data do
        		if data[i][cat] ~= prevCat then
        			table.insert(catTable, data[i][cat])
        			prevCat = data[i][cat]
        		end
        	end
        	
        	if order then	 
        		--clean up the user provided order table by removing any empty categories
        		local n = 1
        		while n < #order do
		        	if not in_table(order[n], catTable) then
	        			table.remove(order, n)
		        	else 
		        		n = n + 1
		        	end
		        end

        		--add any categories not specified to the user order of categories
        		for i=1, #catTable do
		        	if not in_table(catTable[i], order) then
	        			table.insert(order, catTable[i])
		        	end
		        end
		    else 
				order = catTable
        	end        	

        end      
                
        local j = 1
        local c = {}
        local offset = 12
        while true do
        	local h = order[j]
        	
        	if h then
        		local g = display.newGroup()
        		local b
        		if categoryBackground then 
        			b = display.newImage(categoryBackground, true)
        		else
	        		b = display.newRect(0, 0, screenW, textSize*1.5)
	        		b:setFillColor(0, 0, 0, 100)
        		end
        		g:insert( b )

				local labelShadow = display.newText( h, 0, 0, native.systemFontBold, textSize )
				labelShadow:setTextColor( 0, 0, 0, 128 )
				g:insert( labelShadow, true )
				labelShadow.x = labelShadow.width*.5 + 1 + offset + screenOffsetW*.5
				labelShadow.y = textSize*.8 + 1

        		local t = display.newText(h, 0, 0, native.systemFontBold, textSize)
	            t:setTextColor(255, 255, 255)
                g:insert( t )
                t.x = t.width*.5 + offset + screenOffsetW*.5
                t.y = textSize*.8   
	            
                listView:insert( g )
                g.x = 0
                g.y = prevY + prevH     
	            prevY = g.y
	            prevH = g.height
	            table.insert(c, g)           
	            c[#c].yInit = g.y     
	        end
        	        	
	        --iterate over the data and add items to the list view
	        for i=1, #data do
	        	if data[i][cat] == h then  
 	                local thisItem = newListItem{
	                    data = data[i],
	                    default = default,
	                    over = over,
	                    onRelease = onRelease,
	                    top = top,
	                    bottom = bottom,
	                    callback = callback,
	                    id = i
	                }
	 
	                listView:insert( 1, thisItem )     
	 
	                thisItem.x = 0 + screenOffsetW*.5
	                thisItem.y = prevY + prevH
	 
	                --save the Y and height 
	                prevY = thisItem.y
	                prevH = thisItem.height		
	            end --if	            
	        end --for
	        	        
	    	j = j + 1
	    	
	    	if not order[j] then break end		                        	
        end --while
        
        if backgroundColor then 
        	local bgColor = display.newRect(0, 0, screenW, screenH)
        	bgColor:setFillColor(backgroundColor[1], backgroundColor[2], backgroundColor[3])
	        bgColor.width = listView.width
	        bgColor.height = listView.height
	        bgColor.y = bgColor.height*.5
        	listView:insert(1, bgColor)
	    end
                
        listView.y = top
        listView.top = top
        listView.bottom = bottom
        listView.c = c
        
        currentTarget = listView

		function listView:cleanUp()
			print("tableView cleanUp")
			Runtime:removeEventListener("enterFrame", moveCat )
			Runtime:removeEventListener("enterFrame", scrollList )
            Runtime:removeEventListener( "enterFrame", showHighlight )
 			Runtime:removeEventListener("enterFrame", trackVelocity)
			local i
			for i = listView.numChildren, 1, -1 do
				--test
				listView[i]:removeEventListener("touch", newListItemHandler)
				listView:remove(i)
				listView[i] = nil
			end
		end	
        
        return listView
end
 
function scrollList(event)   
		local friction = 0.9
		local timePassed = event.time - lastTime
		lastTime = lastTime + timePassed       

        --turn off scrolling if velocity is near zero
        if math.abs(velocity) < .01 then
                velocity = 0
                Runtime:removeEventListener("enterFrame", scrollList )
        end       

        velocity = velocity*friction
        
        currentTarget.y = math.floor(currentTarget.y + velocity*timePassed)
        
        moveCat()

        local upperLimit = currentTarget.top 
        local bottomLimit = screenH - currentTarget.height - currentTarget.bottom
        
        if ( currentTarget.y > upperLimit ) then
                velocity = 0
                Runtime:removeEventListener("enterFrame", scrollList )          
                Runtime:addEventListener("enterFrame", moveCat )          
                currentTarget.tween = transition.to(currentTarget, { time=400, y=upperLimit, transition=easing.outQuad})
        elseif ( currentTarget.y < bottomLimit and bottomLimit < 0 ) then 
                velocity = 0
                Runtime:removeEventListener("enterFrame", scrollList )          
                Runtime:addEventListener("enterFrame", moveCat )          
                currentTarget.tween = transition.to(currentTarget, { time=400, y=bottomLimit, transition=easing.outQuad})
        elseif ( currentTarget.y < bottomLimit ) then 
                velocity = 0
                Runtime:removeEventListener("enterFrame", scrollList )          
                Runtime:addEventListener("enterFrame", moveCat )          
                currentTarget.tween = transition.to(currentTarget, { time=400, y=upperLimit, transition=easing.outQuad})        
        end 
                 
        return true
end

function moveCat()
        local upperLimit = currentTarget.top 

		for i=1, #currentTarget.c do
			if( currentTarget.y > upperLimit - currentTarget.c[i].yInit ) then
				currentTarget.c[i].y = currentTarget.c[i].yInit 
			end
			
			if ( currentTarget.y < upperLimit - currentTarget.c[i].yInit ) then
				currentTarget.c[i].y = upperLimit - currentTarget.y
			end
	
			if( i > 1 ) then
				if ( currentTarget.c[i].y < currentTarget.c[i-1].y + currentTarget.c[i].height ) then
					currentTarget.c[i-1].y = currentTarget.c[i].y - currentTarget.c[i].height
				end
			end
		end
		
		return true
end

function trackVelocity(event) 	
	local timePassed = event.time - prevTime
	prevTime = prevTime + timePassed

	if prevY then 
		velocity = (currentTarget.y - prevY)/timePassed 
	end
	prevY = currentTarget.y
end			

--look for an item in a table
function in_table ( e, t )
	for _,v in pairs(t) do
		if (v==e) then return true end
	end
	return false
end

