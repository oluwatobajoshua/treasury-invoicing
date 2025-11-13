@echo off
REM Notification Testing Helper Script
REM This script helps monitor the application during testing

echo.
echo ========================================
echo  Travel Request System - Test Monitor
echo ========================================
echo.
echo Server: http://localhost:8765
echo Status: RUNNING
echo.
echo ========================================
echo  Quick Actions
echo ========================================
echo.
echo 1. Open Application in Browser
echo 2. View Error Logs
echo 3. View Debug Logs  
echo 4. Clear Cache
echo 5. Check Database
echo 6. Exit
echo.

:menu
set /p choice="Enter your choice (1-6): "

if "%choice%"=="1" (
    echo Opening browser...
    start http://localhost:8765
    goto menu
)

if "%choice%"=="2" (
    echo.
    echo === Last 20 Error Log Entries ===
    if exist logs\error.log (
        powershell -Command "Get-Content logs\error.log -Tail 20"
    ) else (
        echo No error log found.
    )
    echo.
    pause
    goto menu
)

if "%choice%"=="3" (
    echo.
    echo === Last 20 Debug Log Entries ===
    if exist logs\debug.log (
        powershell -Command "Get-Content logs\debug.log -Tail 20"
    ) else (
        echo No debug log found.
    )
    echo.
    pause
    goto menu
)

if "%choice%"=="4" (
    echo Clearing cache...
    php bin\cake.php cache clear_all
    echo Cache cleared!
    echo.
    pause
    goto menu
)

if "%choice%"=="5" (
    echo Checking database connection...
    php bin\cake.php schema_cache build --connection default
    echo Database check complete!
    echo.
    pause
    goto menu
)

if "%choice%"=="6" (
    echo Exiting...
    exit
)

echo Invalid choice. Please try again.
goto menu
