local scene      = storyboard.newScene()

local zentaoImg
local accountText, passwordText, zentaoRootText
local accountField, passwordField, zentaoRootField
local loginButton
local fontSize = 14

local userData = {}

local zentaoRoot  = "http://" 
local configAPI   = ""
local account     = "" 
local password    = "" 
local md5Password = ""
local mixPassword = ""

-- global vars
loginAPI   = ""
sessionAPI = ""
myTodoAPI  = ""
myBugAPI   = ""
myTaskAPI  = ""

config  = {}
session = {}

navBar    = nil
navHeader = nil

local function loginButtonRelease( self, event )
 if string.len(zentaoRootField.text) > 0 and string.len(accountField.text) > 0 and string.len(passwordField.text) > 0 then
   account  = accountField.text
   password = passwordField.text
   if string.find(zentaoRootField.text, "http://") then
     zentaoRoot = zentaoRootField.text
   else 
     zentaoRoot = zentaoRoot .. zentaoRootField.text
   end

   if string.find(zentaoRoot, '/', string.len("http://")+1) == nil then
     zentaoRoot = zentaoRoot .. '/'
   end
   configAPI  = zentaoRoot .. "?mode=getconfig"

   local response = {}
   -- get the config
   local result = http.request {url = configAPI, method = "GET", sink = ltn12.sink.table(response) }

   if table.getn(response) == 0 then
     native.showAlert("提示", "网络错误,请查看网络或重新设置!", {"我知道了"} )
   else
     for i, val in pairs(response) do
       config = json.decode(val)
     end

     -- set APIs
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

     -- get the session
     http.request{url = sessionAPI, method = "GET", sink = ltn12.sink.table(session)}

     if table.getn(session) == 0 then
       native.showAlert("提示", "网络错误,请查看网络或重新设置!", {"我知道了"} )
     else
       session = json.decode(table.concat(session))
       session.data = json.decode(session.data)

       -- user login
       local response2 = {}
       md5Password = crypto.digest(crypto.md5, password) 
       mixPassword = crypto.digest(crypto.md5, crypto.digest(crypto.md5, password) .. session.data.rand)

       loginAPI = loginAPI .. "&account=" .. account .. "&password=" .. mixPassword .. "&" .. session.data.sessionName .. "=" .. session.data.sessionID
       http.request{url = loginAPI, method = "GET", sink = ltn12.sink.table(response2) }

       if json.decode(table.concat(response2)).status == "failed" then
         native.showAlert("登录失败", "网络问题，或用户信息设置错误！", {"我知道了"}) 
       else
         local userData = json.encode({zentaoRoot = zentaoRoot, account = account, md5Password = md5Password})
         local path = system.pathForFile( "config.txt", system.DocumentsDirectory )
         local file = io.open(path, "w+")
         local result = file:write(userData)
         io.close( file )

         storyboard.gotoScene(true, "index", "slideLeft", 100)
       end
     end

   end
 else
   native.showAlert("提示", "请输入完整的用户信息!", {"我知道了"} )
 end

 return true
end

function scene:createScene( event )
  local screenGroup = self.view

  zentaoImg = display.newImage("zentao.png") 
  zentaoImg.xOrigin = display.contentWidth/2 
  zentaoImg.yOrigin = display.contentHeight/2
  screenGroup:insert(zentaoImg)
end

-- Called immediately after scene has moved onscreen:
function scene:enterScene(event)
  local screenGroup = self.view

  navBar = display.newImage("navBar.png")
  navBar.xOrigin = display.contentWidth/2 + display.screenOriginX
  navBar.yOrigin = navBar.yOrigin + display.screenOriginY
  screenGroup:insert(navBar)

  navHeader = display.newText("禅道", 0, 0, native.systemFontBold, 18)
  navHeader:setTextColor(255, 255, 255)
  navHeader.xOrigin = display.contentWidth/2
  navHeader.yOrigin = navBar.yOrigin
  screenGroup:insert(navHeader)

  local objectX = display.contentWidth /8 
  local objectY = display.contentWidth / 3
  local textFieldWidth  = math.floor(display.contentWidth*2/3)
  local textFieldHeight = 38

  -- account
  accountText  = display.newText("用户名:", objectX, objectY, native.systemFontBold, fontSize)
  accountText:setTextColor(0,0,0)
  screenGroup:insert(accountText)
  objectY = objectY + accountText.height

  accountField  = native.newTextField(objectX, objectY, textFieldWidth, textFieldHeight)
  accountField.text = account
  screenGroup:insert(accountField)
  objectY = objectY + accountField.height

  -- password
  passwordText = display.newText("密码:",  objectX, objectY , native.systemFontBold, fontSize)
  passwordText:setTextColor(0,0,0)
  screenGroup:insert(passwordText)
  objectY = objectY + passwordText.height

  passwordField = native.newTextField(objectX, objectY, textFieldWidth, textFieldHeight)
  passwordField.text = password
  passwordField.isSecure = true
  screenGroup:insert(passwordField)
  objectY = objectY + passwordField.height

  -- zentaoRoot   
  zentaoRootText = display.newText("访问地址:", objectX, objectY, native.systemFontBold, fontSize)
  zentaoRootText:setTextColor(0,0,0)
  screenGroup:insert(zentaoRootText)
  objectY = objectY + zentaoRootText.height

  zentaoRootField = native.newTextField(objectX, objectY, textFieldWidth, textFieldHeight)
  zentaoRootField.text = zentaoRoot
  screenGroup:insert(zentaoRootField)
  objectY = objectY + zentaoRootField.height

  -- login button
  loginButton = ui.newButton
  {
    default = "button.png",
    over = "button_over.png",
    onRelease = loginButtonRelease,
    size = fontSize,
    text = "登录",
    textColor = {0, 0, 0, 165},
    emboss = true
  }
  loginButton.xOrigin = display.contentWidth/2
  loginButton.yOrigin = objectY + loginButton.height
  local tmpWidth = loginButton.width
  loginButton.width  = math.floor(display.contentWidth/4)
  loginButton.height = math.floor(loginButton.width * loginButton.height / tmpWidth)
  screenGroup:insert(loginButton)

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
