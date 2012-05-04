module(..., package.seeall)

-- global vars
myTasks = {}
currentTaskID = nil

-- local vars
local scene      = storyboard.newScene()
local fontSize = 14

local function reLoginButtonRelease(self, event)
    storyboard.gotoScene("login", "slideRight", 100)
end

local function view(event) 
  _G.currentTaskID = event.target.id
  storyboard.gotoScene(true, "viewTask", "slideLeft", 100)
  return true
end

function createScreen()
  local screenGroup = display.newGroup()

  -- 获取tasks
  local response = {}
  myTaskAPI = myTaskAPI .. "&" ..  session.data.sessionName .. "=" .. session.data.sessionID
  http.request{url = myTaskAPI, method = "GET", sink = ltn12.sink.table(response) } -- type(response) = table
  
  if table.getn(response) == 0 then
    native.showAlert("提示", "网络错误,请查看网络或重新设置!", {"我知道了"} )
  else
    response = json.decode(table.concat(response)) --type(response) = table ,type(response.data) = string
    if crypto.digest(crypto.md5, response.data) ~= response.md5 then
      native.showAlert("提示", "数据传输不完整", {"我知道了"}) 
    else

      response.data = json.decode(response.data) --type(tasks.data) = table, type(tasks.data.tasks) = table

      -- tableView
      if table.getn(response.data.tasks) == 0 then
        local noTaskText = display.newText("您当前没有任务！" , 10, (display.contentHeight - 35)/2, native.systemFont, fontSize)
        noTaskText.xOrigin = display.contentWidth / 2
        noTaskText:setTextColor(0, 0, 0)
        screenGroup:insert(noTaskText)
      else
        local tableView = require("tableView")
        local myList
        local listData = {}

        for i, val in pairs(response.data.tasks) do
          listData[i] = "#" .. val.id .. " " .. val.name:sub(1,40)

          if not val.desc then
            val.desc = ""
          end

          myTasks[i] = val
        end
        _G.myTasks = myTasks

        myList = tableView.newList{
          data      = listData, -- listData下标必须从1开始，否则出错
          default   = "listItemBg.png",
          over      = "listItemBg_over.png",
          onRelease = view,
          top       = topBoundary,
          bottom    = bottomBoundary,
          backgroundColor = { 255, 255, 255 },
          callback        = function(row) 
            local t = display.newText(row, 0, 0, native.systemFont, fontSize)
            t:setTextColor(0, 0, 0)
            t.x = math.floor(t.width/2) + 12
            t.y = 30
            return t
          end
        }
        screenGroup:insert(myList)
      end
    end
  end

  function screenGroup:cleanUp()
    screenGroup:removeSelf()
  end
  
  local navBar = display.newImage("navBar.png")
  navBar.xOrigin = display.contentWidth/2 + display.screenOriginX
  navBar.yOrigin = navBar.yOrigin + display.screenOriginY
  screenGroup:insert(navBar)
  
  local reLoginButton = ui.newButton{
    default = "reLoginButton.png",
    over = "reLoginButton_over.png",
    onRelease = reLoginButtonRelease,
    text = "重新登录",
    size = 12,
    emboss = true
  }
  reLoginButton.xOrigin = display.contentWidth - reLoginButton.width/2 + display.screenOriginX
  reLoginButton.yOrigin = navBar.height/2 + display.screenOriginY 
  screenGroup:insert(reLoginButton)

  local navHeader = display.newText("我的任务", 0, 0, native.systemFont, 18)
  navHeader:setTextColor(255, 255, 255)
  navHeader.xOrigin = display.contentWidth/2
  navHeader.yOrigin = navBar.yOrigin
  screenGroup:insert(navHeader)

  return screenGroup
end
