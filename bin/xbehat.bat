@ECHO OFF
SET BIN_TARGET=%~dp0\"../vendor/siciarek/symfony-utils-bundle/bin/behat"\xbehat
php "%BIN_TARGET%" %*
