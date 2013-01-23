local scene      = storyboard.newScene()

local function backButtonRelease( self, event )
    storyboard.gotoScene(true, "login", "zoomInOut", 200)
end

local viewController = require("viewController")
local mainView, tabView, currentScreen, tabBar

local function loadScreen(newScreen)
  if currentScreen then
    currentScreen:cleanUp()
  end
  currentScreen = require(newScreen).createScreen()
  tabView:insert(currentScreen)

  return true
end

-- Handler that gets notified when the exit alert closes
local function exitListener(event)
  if "clicked" == event.action then
    local i = event.index
    if 1 == i then
      os.exit()
    elseif 2 == i then 
    end
  end
end

local function showScreen(event)
  local t = event.target
  local phase = event.phase

  if phase == "ended" then 
    if t.id == 1 then
      loadScreen("myTodo")
    elseif t.id == 2 then
      loadScreen("myTask")
    elseif t.id == 3 then
      loadScreen("myBug")
    elseif t.id == 4 then
      native.showAlert("提示", "要退出吗？", {"确定", "取消"}, exitListener)
    end
    tabBar.selected(t)
  end

  return true
end


function scene:createScene( event )
  local screenGroup = self.view
  
  local backgroundImg = display.newImage("background.png") 
  backgroundImg.xOrigin = display.contentWidth/2 
  backgroundImg.yOrigin = display.contentHeight/2
  screenGroup:insert(backgroundImg)

  local screenGroup = self.view
  tabView = display.newGroup()	
  screenGroup:insert(tabView)

  loadScreen("myTodo")

  tabBar = viewController.newTabBar{
    background = "tabBar.png",
    tabs = {"Todo", "任务", "Bug", "退出"}, 
    onRelease = showScreen 
  }
  screenGroup:insert(tabBar)

  tabBar.selected()

  return true
end

function scene:enterScene( event )
  storyboard.removeScene('login')
  storyboard.removeScene('viewTodo')
  storyboard.removeScene('viewTask')
  storyboard.removeScene('viewBug')
end

function scene:exitScene()
end

function scene:destroyScene(event)
end

scene:addEventListener("createScene", scene)
scene:addEventListener("enterScene", scene)
scene:addEventListener("exitScene", scene)
scene:addEventListener("destroyScene", scene)

return scene
