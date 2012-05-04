require("htmltotext")
require("utf8sub")

local storyboard = require "storyboard"
local scene      = storyboard.newScene()

local function backButtonRelease( self, event )
    storyboard.gotoScene("index", "slideRight", 100)
end

-- 第一创建的时候才把代码放在这里，以后重新进入就不会调用createScene了
-- 所以逻辑代码应该放到enterScene中
function scene:createScene(event)
  local screenGroup = self.view
  
  local backgroundImg = display.newImage("background.png") 
  backgroundImg.xOrigin = display.contentWidth/2 
  backgroundImg.yOrigin = display.contentHeight/2
  screenGroup:insert(backgroundImg)
end

function scene:enterScene( event )
  local screenGroup = self.view

  display.setStatusBar( display.HiddenStatusBar ) 

  --import the scrolling classes
  local scrollView = require("scrollView")

  -- Setup a scrollable content group
  local scrollView = scrollView.new{ top=display.screenOriginY, bottom=display.screenOriginY }
  screenGroup:insert(scrollView)

  local fontSize = 18
  local textHeight =  fontSize+10
  local startX = 10
  local startY = 10
  local lineLimit = math.floor(display.contentWidth / fontSize) 
  local output = ""
  local i = 0 

  -- display bug title
  local title = "#" .. myBugs[currentBugID].id .. " " .. myBugs[currentBugID].title
  while string.len(output) < string.len(title) do
    output = output .. utf8sub(title, i*lineLimit+1, lineLimit) .. "\n"
    i = i+1
  end
  local titleText   = display.newText(output, startX, startY, system.defaultFontBold, fontSize)
  titleText:setTextColor(0, 0, 0)
  scrollView:insert(titleText)
  startY = startY + (i-1)*textHeight

  -- display bug basic information
  output = ""
  i = 0
  local steps = "步骤：" .. HTML_ToText(myBugs[currentBugID].steps) 
  local steps = string.gsub(steps, "\n", "")
  while string.len(output) < string.len(steps) do
    output = output .. utf8sub(steps, i*lineLimit+1, lineLimit) .. "\n"
    i = i+1
  end
  local stepsText = display.newText(output, startX, startY + 2*textHeight,  system.defaultFont, 14)
  stepsText:setTextColor(0, 0, 0)
  scrollView:insert(stepsText)
  
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
