 
local storyboard = require( "storyboard" )
local scene = storyboard.newScene()

loginAPI   = ""
sessionAPI = ""
myTodoAPI  = ""
myBugAPI   = ""
myTaskAPI  = ""

config  = {}
session = {}

function autoLogin()
  local response = {}
  -- 获取config
  local result = http.request {url = configAPI, method = "GET", sink = ltn12.sink.table(response) }

  if table.getn(response) == 0 then
    native.showAlert("提示", "网络错误,请查看网络或重新设置!", {"我知道了"} )
  else
    for i, val in pairs(response) do
      config = json.decode(val)
    end

    -- 设置API地址
    if config.requestType == "GET" then
      loginAPI   = zentaoRoot .. "?m=user&f=login" 
      sessionAPI = zentaoRoot .. "?m=api&f=getSessionID&t=json"

      myTodoAPI     = zentaoRoot .. "?m=my&f=todo&t=json"
      myTaskAPI     = zentaoRoot .. "?m=my&f=task&t=json"
      myBugAPI      = zentaoRoot .. "?m=my&f=bug&t=json"
    elseif config.requestType == "PATH_INFO" then
      loginAPI      = zentaoRoot .. "user-login.json?a=1" 
      sessionAPI    = zentaoRoot .. "api-getsessionid.json?a=1"

      myTodoAPI     = zentaoRoot .. "my-todo.json?a=1"
      myTaskAPI     = zentaoRoot .. "my-task.json?a=1"
      myBugAPI      = zentaoRoot .. "my-bug.json?a=1"
    end

    -- 获取session
    http.request{url = sessionAPI, method = "GET", sink = ltn12.sink.table(session)}

    if table.getn(session) == 0 then
      native.showAlert("提示", "网络错误,请查看网络或重新设置!", {"我知道了"} )
    else
      session = json.decode(table.concat(session))
      session.data = json.decode(session.data)

      -- 用户登录
      local response2 = {}

      mixPassword = crypto.digest(crypto.md5, md5Password .. session.data.rand)
      loginAPI = loginAPI .. "&account=" .. account .. "&password=" .. mixPassword .. "&" .. session.data.sessionName .. "=" .. session.data.sessionID
      http.request{url = loginAPI, method = "GET", sink = ltn12.sink.table(response2) }

      if json.decode(table.concat(response2)).status == "failed" then
        native.showAlert("登录失败", "网络问题，或用户信息设置错误！", {"我知道了"}) 
      else
        storyboard.gotoScene(true, "index", "slideLeft", 100)
      end

    end

  end
end


-- Called when the scene's view does not exist:
function scene:createScene( event )
  local screenGroup = self.view

  zentaoImg = display.newImage("zentao.png") 
  zentaoImg.xOrigin = display.contentWidth/2 
  zentaoImg.yOrigin = display.contentHeight/2
  screenGroup:insert(zentaoImg)

end

-- Called immediately after scene has moved onscreen:
function scene:enterScene( event )
  timer.performWithDelay(2000,  autoLogin)
end

-- Called when scene is about to move offscreen:
function scene:exitScene( event )
  local group = self.view
end

-- Called prior to the removal of scene's "view" (display group)
function scene:destroyScene( event )
  local group = self.view
end
 
scene:addEventListener( "createScene", scene )
scene:addEventListener( "enterScene", scene )
scene:addEventListener( "exitScene", scene )
scene:addEventListener( "destroyScene", scene )
 
return scene

