@ECHO OFF
SET BIN_TARGET=%~dp0\"../vendor/siciarek/symfony-utils-bundle/bin/selenium"\selenium
php "%BIN_TARGET%" %*
