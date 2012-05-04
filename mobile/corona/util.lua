-- util.lua
--
-- Version 1.0 
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

-- set some global values for width and height of the screen
local screenW, screenH = display.contentWidth, display.contentHeight
local viewableScreenW, viewableScreenH = display.viewableContentWidth, display.viewableContentHeight
local screenOffsetW, screenOffsetH = display.contentWidth -  display.viewableContentWidth, display.contentHeight - display.viewableContentHeight

--------------------------------------------
-- Wrap text
function wrap(str, limit, indent, indent1)
  indent = indent or ""
  indent1 = indent1 or indent
  limit = limit or 72
  local here = 1-#indent1
  return indent1..str:gsub("(%s+)()(%S+)()",
                          function(sp, st, word, fi)
                            if fi-here > limit then
                              here = st - #indent
                              return "\n"..indent..word
                            end
                          end)
end

function explode(div,str)
  if (div=='') then return false end
  local pos,arr = 0,{}
  -- for each divider found
  for st,sp in function() return string.find(str,div,pos,true) end do
    table.insert(arr,string.sub(str,pos,st-1)) -- Attach chars left of current divider
    pos = sp + 1 -- Jump past current divider
  end
  table.insert(arr,string.sub(str,pos)) -- Attach chars right of last divider
  return arr
end


function wrappedText(str, limit, size, font, color, indent, indent1)
	str = explode("\n", str)
	size = tonumber(size) or 12
	color = color or {255, 255, 255}
	font = font or "Helvetica"	

	--apply line breaks using the wrapping function
	local i = 1
	local strFinal = ""
    while i <= #str do
		strW = wrap(str[i], limit, indent, indent1)
		strFinal = strFinal.."\n"..strW
		i = i + 1
	end
	str = strFinal
	
	--search for each line that ends with a line break and add to an array
	local pos, arr = 0, {}
	for st,sp in function() return string.find(str,"\n",pos,true) end do
		table.insert(arr,string.sub(str,pos,st-1)) 
		pos = sp + 1 
	end
	table.insert(arr,string.sub(str,pos)) 
			
	--iterate through the array and add each item as a display object to the group
	local g = display.newGroup()
	local i = 1
    while i <= #arr do
		local t = display.newText( arr[i], 0, 0, font, size )    
		t:setTextColor( color[1], color[2], color[3] )
		t.x = math.floor(t.width/2)
		t.y = math.floor((size*1.3)*(i-1))
		g:insert(t)
		i = i + 1
	end
	return g
end

--[[ 

-- USAGE: 
local myText = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc euismod justo sapien, at sollicitudin lacus. Quisque vestibulum commodo felis id posuere."
local myTextObject = wrappedText(myText, 46)
--local myTextObject = wrappedText(myText, 46, 16)
--local myTextObject = wrappedText(myText, 46, 16, {255, 0, 0})
local myGroup = display.newGroup()
myGroup:insert( myTextObject )

]]--


--------------------------------------------
-- XML Parser
function parseargs(s)
  local arg = {}
  string.gsub(s, "(%w+)=([\"'])(.-)%2", function (w, _, a)
    arg[w] = a
  end)
  return arg
end
    
function collect(s)
  local stack = {}
  local top = {}
  table.insert(stack, top)
  local ni,c,label,xarg, empty
  local i, j = 1, 1
  while true do
    ni,j,c,label,xarg, empty = string.find(s, "<(%/?)([%w:]+)(.-)(%/?)>", i)
    if not ni then break end
    local text = string.sub(s, i, ni-1)
    if not string.find(text, "^%s*$") then
      table.insert(top, text)
    end
    if empty == "/" then  -- empty element tag
      table.insert(top, {label=label, xarg=parseargs(xarg), empty=1})
    elseif c == "" then   -- start tag
      top = {label=label, xarg=parseargs(xarg)}
      table.insert(stack, top)   -- new level
    else  -- end tag
      local toclose = table.remove(stack)  -- remove top
      top = stack[#stack]
      if #stack < 1 then
        error("nothing to close with "..label)
      end
      if toclose.label ~= label then
        error("trying to close "..toclose.label.." with "..label)
      end
      table.insert(top, toclose)
    end
    i = j+1
  end
  local text = string.sub(s, i)
  if not string.find(text, "^%s*$") then
    table.insert(stack[#stack], text)
  end
  if #stack > 1 then
    error("unclosed "..stack[stack.n].label)
  end
  return stack[1]
end

------------------------------

function aLink(e)
	local t = e.target
	if (t.link) then
			print(e.target.link)
			system.openURL( e.target.link )
	else
		print("no link found")
	end
end

--------------------------------------------		
-- Helper function for newLink utility function below
local function newLinkHandler( self, event )
	local result = true
	local default = self[1]
	local over = self[2]
	local t = self.parent
	local phase = event.phase
	print ("newButtonHandler: "..phase)	
	
	if over then
		linkCurrentDefault = default
		linkCurrentRollover = over
	end
	
	local function showHighlight (event)
		local timePassed = event.time - startTime
		print("timePassed: "..timePassed)
	
		if timePassed > 100 then 
			print("highlight")
			linkCurrentDefault.isVisible = false
			linkCurrentRollover.isVisible = true
			Runtime:removeEventListener( "enterFrame", showHighlight )
		end
	end
	
	if "began" == phase then
        t.startPos = event.y
        t.prevPos = event.y                                       
        t.delta, t.velocity = 0, 0
        if t.tween then transition.cancel(t.tween) end
	    Runtime:removeEventListener("enterFrame", t )  	 			

		self.prevTime = 0
		self.prevY = 0
		
		if over then
			startTime = event.time
			Runtime:addEventListener( "enterFrame", showHighlight )
		end
			
		local onPress = self._onPress
		if onPress then
			result = onPress( event )
		end

        -- Subsequent touch events will target button even if they are outside the contentBounds of button
        display.getCurrentStage():setFocus( self )
        self.isFocus = true

       elseif self.isFocus  then
       
			if "moved" == phase then
			    local bottomLimit = screenH - t.height - t.bottom
			
			    t.delta = event.y - t.prevPos
			    t.prevPos = event.y
			    if ( t.y > t.top or t.y < bottomLimit ) then 
			        t.y  = t.y + t.delta/2
			    else
			        t.y = t.y + t.delta       
			    end
				
				--Track velocity while the user is moving the view
				local timePassed = event.time - t.prevTime
				t.prevTime = t.prevTime + timePassed
				if t.prevY then 
					t.velocity = (t.y - t.prevY)/timePassed 
				end
				t.prevY = t.y
			
				if over then
					Runtime:removeEventListener( "enterFrame", showHighlight )
					-- the over image should only be visible while the finger is within button's contentBounds
					default.isVisible = true --not isWithinBounds
					over.isVisible = false --isWithinBounds
				end
			elseif "ended" == phase or "cancelled" == phase then 
			    local dragDistance = event.y - t.startPos
				t.lastTime = event.time
			
			    Runtime:addEventListener("enterFrame", t )  	 			
			
				local bounds = self.contentBounds
				local x,y = event.x,event.y
				local isWithinBounds = 
					bounds.xMin <= x and bounds.xMax >= x and bounds.yMin <= y and bounds.yMax >= y
			
				if over then 
					Runtime:removeEventListener( "enterFrame", showHighlight )
					default.isVisible = true
					over.isVisible = false
				end
			
				-- Only consider this a "click", if the user lifts their finger inside button's contentBounds
				if isWithinBounds and (dragDistance < 10 and dragDistance > -10 ) then
					result = self._onRelease( event )
				end

                -- Allow touch events to be sent normally to the objects they "hit"
                display.getCurrentStage():setFocus( nil )
                self.isFocus = false
			end
			
	end

	return result
end


function newLink( params )
	local button
	
	if params.default and params.onRelease then
		button = display.newGroup()
		local default = display.newImage( params.default )
		button:insert( default, true )

		if params.over then
			local over = display.newImage( params.over )
			over.isVisible = false
			button:insert( over, true )
		end
		
		if type( params.onPress ) == "function" then
			button._onPress = params.onPress
		end
		if type( params.onRelease ) == "function" then
			button._onRelease = params.onRelease
		end
		
		button.buttonID = params.buttonID 

		-- Set button as a table listener by setting a table method and adding the button as its own table listener for "touch" events
		button.touch = newLinkHandler
		button:addEventListener( "touch", button )	
	end

	if params.x then
		button.x = params.x
	end
	if params.y then
		button.y = params.y
	end

	return button
end
