require("htmltotext")
require("utf8sub")

local storyboard = require "storyboard"
local scene      = storyboard.newScene()

local function backButtonRelease( self, event )
    storyboard.gotoScene("index", "slideRight", 100)
end

function scene:createScene(event)
  local screenGroup = self.view
  
  local backgroundImg = display.newImage("view_background.png") 
  backgroundImg.xOrigin = display.contentWidth/2 
  backgroundImg.yOrigin = display.contentHeight/2
  screenGroup:insert(backgroundImg)
end

function scene:enterScene( event )
  local screenGroup = self.view

  --import the scrolling classes
  local scrollView = require("scrollView")

  -- Setup a scrollable content group
  local scrollView = scrollView.new{top=display.screenOriginY, bottom=display.screenOriginY}
  screenGroup:insert(scrollView)

  local fontSize = 18
  local textHeight =  fontSize+10
  local startX = 10
  local startY = 10
  local lineLimit = math.floor(display.contentWidth / fontSize) 
  local output = ""
  local i = 0 

  -- display task name
  local name = "#" .. myTasks[currentTaskID].id .. " " .. myTasks[currentTaskID].name
  while string.len(output) < string.len(name) do
    output = output .. utf8sub(name, i*lineLimit+1, lineLimit) .. "\n"
    i = i+1
  end
  local nameText = display.newText(output, startX, startY, system.defaultFontBold, fontSize)
  nameText:setTextColor(0, 0, 0)
  scrollView:insert(nameText)
  startY = startY + (i-1)*textHeight

  output = ""
  i = 0
  local desc = HTML_ToText(myTasks[currentTaskID].desc) 
  local desc = string.gsub(desc, "\n", "")
  while string.len(output) < string.len(desc) do
    output = output .. utf8sub(desc, i*lineLimit+1, lineLimit) .. "\n"
    i = i+1
  end
  local descText = display.newText(output, startX, startY + textHeight, system.defaultFont, 14)
  descText:setTextColor(0, 0, 0)
  scrollView:insert(descText)
  startY = startY + (i-1)*textHeight
  
  backButton = ui.newButton{
    default = "backButton.png",
    over = "backButton_over.png",
    onRelease = backButtonRelease,
    size = 16,
    emboss = true
  }
  backButton.xOrigin = display.contentWidth - backButton.width/2
  backButton.yOrigin = display.contentHeight - backButton.height/2 - display.screenOriginY
  screenGroup:insert(backButton)

  scrollView:addScrollBar()
end

function scene:exitScene( event )
end

function scene:destroyScene( event )
end

scene:addEventListener("createScene",  scene )
scene:addEventListener("enterScene",   scene )
scene:addEventListener("exitScene",    scene )
scene:addEventListener("destroyScene", scene )

return scene
