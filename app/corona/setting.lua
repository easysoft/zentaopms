local scene      = storyboard.newScene()

-- zentao vars
local allConfigs  = {} -- read all configs from config.json
local currentName = ""
local currentUrl  = "http://"

-- page elements
local backgroundImg
local nameField, urlField
local savaButton, resetButton, backButton

local function fieldHandler( event )
  if ( "began" == event.phase ) then
    -- This is the "keyboard has appeared" event
    -- In some cases you may want to adjust the interface when the keyboard appears.
  elseif ( "ended" == event.phase ) then

    -- This event is called when the user stops editing a field: for example, when they touch a different field
  elseif ( "submitted" == event.phase ) then
    -- This event occurs when the user presses the "return" key (if available) on the onscreen keyboard
    -- Hide keyboard
    native.setKeyboardFocus( nil )
  end
end

local function saveButtonRelease( self, event )

  if string.len(nameField.text) > 0 and string.len(urlField.text) > 0 then
    currentName = nameField.text
    if string.find(urlField.text, "http://") then
      currentUrl = urlField.text
    else 
      currentUrl = currentUrl .. urlField.text .. '/'
    end

    local userData = json.encode({name = currentName, url = currentUrl})
    local path = system.pathForFile( "config.txt", system.DocumentsDirectory )
    local file = io.open(path, "w+")
    local result = file:write(userData)
    if result then
      native.showAlert("提示", "保存成功", { "OK" })
    end
    io.close( file )
  else
    native.showAlert("提示", "请正确填写!", { "OK" })
  end

  return true
end

local function backButtonRelease( self, event )
    storyboard.gotoScene(true, "login", "slideRight", 200)
end


function scene:createScene( event )
  local screenGroup = self.view

  backgroundImg = display.newImage("background.png")
  backgroundImg.xOrigin = display.contentWidth/2 + display.screenOriginX
  backgroundImg.yOrigin = display.contentHeight/2 + display.screenOriginY
  screenGroup:insert(backgroundImg)

end

function scene:enterScene( event )
  storyboard.purgeScene("login")

  local screenGroup = self.view

  screenGroup:insert(navBar)
  screenGroup:insert(navHeader)
  
  -- newText
  nameText = display.newText("名称:", display.contentWidth/6, display.contentHeight / 3, native.systemFontBold, 18)
  nameText:setTextColor(0,0,0)
  screenGroup:insert(nameText)

  urlText  = display.newText(" " .. "  Url:", display.contentWidth/6, display.contentHeight*13/ 30 , native.systemFontBold, 18)
  urlText:setTextColor(0,0,0)
  screenGroup:insert(urlText)
  
  -- newTextField
  nameField  = native.newTextField(display.contentWidth/6 + nameText.width + 10, display.contentHeight / 3, display.contentWidth/2, 40)
  nameField.text = currentName
  screenGroup:insert(nameField)

  urlField   = native.newTextField(display.contentWidth/6 + urlText.width + 10, display.contentHeight *13/30, display.contentWidth/2, 40)
  urlField.text = currentUrl
  urlField.inputType = "url"
  screenGroup:insert(urlField)
 
  -- Button
  saveButton = ui.newButton{
    default = "button.png",
    over = "button_over.png",
    onRelease = saveButtonRelease,
    size = 24,
    text = "保存",
    emboss = true
  }
  saveButton.xOrigin = display.contentWidth/4
  saveButton.yOrigin = display.contentHeight*3/4
  local tmpWidth = saveButton.width
  saveButton.width  = math.floor(display.contentWidth/3)
  saveButton.height = math.floor(saveButton.width * saveButton.height / tmpWidth)
  screenGroup:insert(saveButton)

  resetButton = ui.newButton{
    default = "button.png",
    over = "button_over.png",
    onRelease = resetButtonRelease,
    size = 24,
    text = "重置",
    emboss = true
  }
  resetButton.xOrigin = display.contentWidth*3/4
  resetButton.yOrigin = display.contentHeight*3/4
  tmpWidth = resetButton.width
  resetButton.width = display.contentWidth/3
  resetButton.height = math.floor(resetButton.width * resetButton.height / tmpWidth)
  screenGroup:insert(resetButton)

  backButton = ui.newButton{
    default = "backButton.png",
    over = "backButton_over.png",
    onRelease = backButtonRelease,
    size = 16,
    emboss = true
  }
  backButton.xOrigin = display.contentWidth  - backButton.width/2 + display.screenOriginX
  backButton.yOrigin = display.contentHeight - backButton.height/2 - display.screenOriginY
  screenGroup:insert(backButton)

end

function scene:exitScene()
end

function scene:destroyScene(event)
end

scene:addEventListener("createScene",  scene)
scene:addEventListener("enterScene",   scene)
scene:addEventListener("exitScene",    scene)
scene:addEventListener("destroyScene", scene)

return scene
