-- scrollView.lua 
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

local prevTime = 0

function new(params)
	-- setup a group to be the scrolling screen
	local scrollView = display.newGroup()
		
	scrollView.top = params.top or 0
	scrollView.bottom = params.bottom or 0

	function scrollView:touch(event) 
	        local phase = event.phase      
	        print(phase)
	        			        
	        if( phase == "began" ) then
				print(scrollView.y)
	                self.startPos = event.y
	                self.prevPos = event.y                                       
	                self.delta, self.velocity = 0, 0
		            if self.tween then transition.cancel(self.tween) end

	                Runtime:removeEventListener("enterFrame", scrollView ) 

					self.prevTime = 0
					self.prevY = 0

					transition.to(self.scrollBar,  { time=200, alpha=1 } )									

					-- Start tracking velocity
					Runtime:addEventListener("enterFrame", trackVelocity)
	                
	                -- Subsequent touch events will target button even if they are outside the contentBounds of button
	                display.getCurrentStage():setFocus( self )
	                self.isFocus = true
	 
	        elseif( self.isFocus ) then
	 
	                if( phase == "moved" ) then     
					        local bottomLimit = screenH - self.height - self.bottom
	            
	                        self.delta = event.y - self.prevPos
	                        self.prevPos = event.y
	                        if ( self.y > self.top or self.y < bottomLimit ) then 
                                self.y  = self.y + self.delta/2
	                        else
                                self.y = self.y + self.delta   
	                        end
	                        
	                        scrollView:moveScrollBar()

	                elseif( phase == "ended" or phase == "cancelled" ) then 
	                        local dragDistance = event.y - self.startPos
							self.lastTime = event.time
	                        
	                        Runtime:addEventListener("enterFrame", scrollView )  	 			
	                        Runtime:removeEventListener("enterFrame", trackVelocity)
	        	                	        
	                        -- Allow touch events to be sent normally to the objects they "hit"
	                        display.getCurrentStage():setFocus( nil )
	                        self.isFocus = false
	                end
	        end
	        
	        return true
	end
	 
	function scrollView:enterFrame(event)   
		local friction = 0.9
		local timePassed = event.time - self.lastTime
		self.lastTime = self.lastTime + timePassed       

        --turn off scrolling if velocity is near zero
        if math.abs(self.velocity) < .01 then
                self.velocity = 0
	            Runtime:removeEventListener("enterFrame", scrollView )          
				transition.to(self.scrollBar,  { time=400, alpha=0 } )									
        end       

        self.velocity = self.velocity*friction
        
        self.y = math.floor(self.y + self.velocity*timePassed)
        
        local upperLimit = self.top 
	    local bottomLimit = screenH - self.height - self.bottom
        
        if ( self.y > upperLimit ) then
                self.velocity = 0
                Runtime:removeEventListener("enterFrame", scrollView )          
                self.tween = transition.to(self, { time=400, y=upperLimit, transition=easing.outQuad})
				transition.to(self.scrollBar,  { time=400, alpha=0 } )									
        elseif ( self.y < bottomLimit and bottomLimit < 0 ) then 
                self.velocity = 0
                Runtime:removeEventListener("enterFrame", scrollView )          
                self.tween = transition.to(self, { time=400, y=bottomLimit, transition=easing.outQuad})
				transition.to(self.scrollBar,  { time=400, alpha=0 } )									
        elseif ( self.y < bottomLimit ) then 
                self.velocity = 0
                Runtime:removeEventListener("enterFrame", scrollView )          
                self.tween = transition.to(self, { time=400, y=upperLimit, transition=easing.outQuad})        
				transition.to(self.scrollBar,  { time=400, alpha=0 } )									
        end 

        scrollView:moveScrollBar()
        	        
	    return true
	end
	
	function scrollView:moveScrollBar()
		if self.scrollBar then						
			local scrollBar = self.scrollBar
			
			scrollBar.y = -self.y*self.yRatio + scrollBar.height*0.5 + self.top
			
			if scrollBar.y <  5 + self.top + scrollBar.height*0.5 then
				scrollBar.y = 5 + self.top + scrollBar.height*0.5
			end
			if scrollBar.y > screenH - self.bottom  - 5 - scrollBar.height*0.5 then
				scrollBar.y = screenH - self.bottom - 5 - scrollBar.height*0.5
			end
			
		end
	end

	function trackVelocity(event) 	
		local timePassed = event.time - scrollView.prevTime
		scrollView.prevTime = scrollView.prevTime + timePassed
	
		if scrollView.prevY then 
			scrollView.velocity = (scrollView.y - scrollView.prevY)/timePassed 
		end
		scrollView.prevY = scrollView.y
	end			
	    
	scrollView.y = scrollView.top
	
	-- setup the touch listener 
	scrollView:addEventListener( "touch", scrollView )
	
	function scrollView:addScrollBar(r,g,b,a)
		if self.scrollBar then self.scrollBar:removeSelf() end

		local scrollColorR = r or 0
		local scrollColorG = g or 0
		local scrollColorB = b or 0
		local scrollColorA = a or 120
						
		local viewPortH = screenH - self.top - self.bottom 
		local scrollH = viewPortH*self.height/(self.height*2 - viewPortH)		
		local scrollBar = display.newRoundedRect(viewableScreenW-8,0,5,scrollH,2)
		scrollBar:setFillColor(scrollColorR, scrollColorG, scrollColorB, scrollColorA)

		local yRatio = scrollH/self.height
		self.yRatio = yRatio		

		scrollBar.y = scrollBar.height*0.5 + self.top

		self.scrollBar = scrollBar

		transition.to(scrollBar,  { time=400, alpha=0 } )			
	end

	function scrollView:removeScrollBar()
		if self.scrollBar then 
			self.scrollBar:removeSelf() 
			self.scrollBar = nil
		end
	end
	
	function scrollView:cleanUp()
        Runtime:removeEventListener("enterFrame", trackVelocity)
		Runtime:removeEventListener( "touch", scrollView )
		Runtime:removeEventListener("enterFrame", scrollView ) 
		scrollView:removeScrollBar()
	end
	
	return scrollView
end
