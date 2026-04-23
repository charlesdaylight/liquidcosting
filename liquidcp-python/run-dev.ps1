Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$python = Join-Path $projectRoot '.venv\Scripts\python.exe'

if (-not (Test-Path $python)) {
    throw "Virtual environment not found at $python"
}

Push-Location $projectRoot
try {
    & $python -m uvicorn liquidcp.app.main:app --host 127.0.0.1 --port 8001 --reload
} finally {
    Pop-Location
}
