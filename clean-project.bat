@echo off
echo ======================================================
echo   LAUNDRYBIZ PROJECT CLEANUP (SIAP JUAL/DISTRIBUSI)
echo ======================================================
echo.
set /p confirm="Apakah Anda yakin ingin membersihkan project ini? (Y/n): "
if /i "%confirm%" neq "Y" goto end

echo.
echo [1/5] Menghapus folder vendor dan node_modules...
if exist "vendor" rmdir /s /q "vendor"
if exist "node_modules" rmdir /s /q "node_modules"

echo [2/5] Menghapus file sensitif (.env)...
if exist ".env" del /f /q ".env"

echo [3/5] Membersihkan storage logs dan sessions...
if exist "storage\logs\*.log" del /f /q "storage\logs\*.log"
if exist "storage\framework\sessions\*" del /f /q "storage\framework\sessions\*"
if exist "storage\framework\views\*" del /f /q "storage\framework\views\*"
if exist "storage\framework\cache\data\*" rmdir /s /q "storage\framework\cache\data"

echo [4/5] Menghapus symbolic link public/storage...
if exist "public\storage" rmdir /s /q "public\storage"

echo [5/5] Menghapus folder .git dan metadata editor...
if exist ".git" rmdir /s /q ".git"
if exist ".idea" rmdir /s /q ".idea"
if exist ".vscode" rmdir /s /q ".vscode"

echo.
echo ======================================================
echo   SELESAI! Project Anda sekarang bersih dan siap di-ZIP.
echo   Jangan lupa pastikan .env.example sudah benar.
echo ======================================================
pause

:end
exit
