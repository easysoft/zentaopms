@ECHO OFF
:LOOP
:: SET /P prompts for input and sets the variable
:: to whatever the user types
SET path=
SET suffix=\php.exe
SET /P path=Input the path PHP:
::确定路径是否带有\php.exe
IF EXIST %path%%suffix% GOTO ItemB
IF EXIST %path% GOTO ItemA
ECHO "%path%" is not valid. Please try again.
ECHO.
GOTO Loop
:ItemA
::路径去除\php.exe
if /I %path:~-8% == %suffix% set path=%path:~0,-8%
:ItemB
::完整路径
set path=%path%%suffix%
set TMPFILE=%random%.tmp
for /r %~dp0 %%i in (*.bat) do (
	if exist %TMPFILE% (del /f/q %TMPFILE%)
::排除本文件
	if not %0=="%%i" (
		echo Change %%i
		for /f "tokens=1,2* delims= " %%j in (%%i) do (
::修改文件特定的行
			if exist %%k (if "%%l"=="" (echo %path% %%k>>%TMPFILE%) else (echo %path% %%k %%l>>%TMPFILE%)) else (if "%%k"=="" (echo %%j>>%TMPFILE%)	else if "%%l"=="" (echo %%j %%k>>%TMPFILE%) else (echo %%j %%k %%l>>%TMPFILE%))
		)
		move /y %TMPFILE% "%%i"
	)
)
echo Success!
pause
