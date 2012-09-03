--- global vars
storyboard = require "storyboard"
http       = require("socket.http")
crypto     = require("crypto")
ltn12      = require("ltn12")
url        = require("socket.url")
json       = require("dkjson")
ui         = require("ui")

local scene = storyboard.newScene()

display.setStatusBar(display.HiddenStatusBar)

local path = system.pathForFile("config.txt", system.DocumentsDirectory)
local file = io.open(path, "r")

if file ~= nil then
  userData = file:read("*a")
  io.close(file)
  if userData then
    userData    = json.decode(userData)
    zentaoRoot  = userData.zentaoRoot
    account     = userData.account
    md5Password = userData.md5Password
    configAPI   = zentaoRoot .. "?mode=getconfig"

    storyboard.gotoScene("autoLogin", "slideRight", 1)
  end
else
  storyboard.gotoScene("login", "slideRight", 1)
end
