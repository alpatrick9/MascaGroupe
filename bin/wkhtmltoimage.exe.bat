@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/wemersonjanuario/wkhtmltopdf-windows/bin/32bit/wkhtmltoimage.exe
call "%BIN_TARGET%" %*
