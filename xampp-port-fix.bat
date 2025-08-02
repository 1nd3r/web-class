@echo off
echo XAMPP Port Fix and File Copy for Emmanuel Wonder Portfolio
echo ==========================================================

echo.
echo Step 1: Stopping XAMPP services...
taskkill /f /im httpd.exe 2>nul
taskkill /f /im mysqld.exe 2>nul

echo.
echo Step 2: Creating Emmanuel Wonder portfolio directory in XAMPP...
if not exist "C:\xampp\htdocs\emmanuelwonder" mkdir "C:\xampp\htdocs\emmanuelwonder"

echo.
echo Step 3: Copying portfolio files to XAMPP...
xcopy "*.html" "C:\xampp\htdocs\emmanuelwonder\" /Y /Q
xcopy "css\*" "C:\xampp\htdocs\emmanuelwonder\css\" /Y /S /Q
xcopy "js\*" "C:\xampp\htdocs\emmanuelwonder\js\" /Y /S /Q
xcopy "images\*" "C:\xampp\htdocs\emmanuelwonder\images\" /Y /S /Q 2>nul

echo.
echo Step 4: Portfolio files copied successfully!
echo.
echo Now you can:
echo 1. Start XAMPP Control Panel as Administrator
echo 2. Start Apache service
echo 3. Visit: http://localhost/emmanuelwonder/index.html
echo 4. Test validation: http://localhost/emmanuelwonder/validation-demo.html
echo.
echo If port 80 is still blocked, change Apache port to 8080:
echo - Click Config next to Apache in XAMPP
echo - Edit httpd.conf
echo - Change "Listen 80" to "Listen 8080"
echo - Then visit: http://localhost:8080/emmanuelwonder/
echo.
pause 