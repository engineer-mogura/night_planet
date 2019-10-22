@echo off
: by rootlinks co., Ltd.
 
set folder=%1
set depth=%2
 
if not defined folder goto usage
if not defined depth goto usage
 
:treedir
if %2==1 (
tree %1 | findstr /r /c:"^.:" /c:"^„¥" /c:"^„¤"
)
 
if %2==2 (
tree %1 | findstr /r /c:"^.:" /c:"^„¥" /c:"^„   „¥" /c:"^„   „¤" /c:"^„¤" /c:"^    „¥" /c:"^    „¤"
)
 
if %2==3 (
tree %1 | findstr /r /c:"^.:" /c:"^„¥" /c:"^„   „¥" /c:"^„   „¤" /c:"^„¤" /c:"^    „¥" /c:"^    „¤" /c:"^„   „   „¥" /c:"^„   „   „¤" /c:"^„       „¤" /c:"\„       „¥" /c:"^    „   „¥" /c:"^    „   „¤" /c:"^        „¥" /c:"^        „¤"
)
goto end
 
:usage
echo usage: treedir.bat folder depth
:end