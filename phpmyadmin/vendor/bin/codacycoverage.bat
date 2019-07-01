@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../codacy/coverage/bin/codacycoverage
php "%BIN_TARGET%" %*
