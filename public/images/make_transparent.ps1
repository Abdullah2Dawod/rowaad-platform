Add-Type -AssemblyName System.Drawing

$srcPath = "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo.png"
$dstPath = "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo-transparent.png"

$img = [System.Drawing.Bitmap]::new($srcPath)
$result = New-Object System.Drawing.Bitmap($img.Width, $img.Height, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)

# Lock bits for fast processing
$rect = New-Object System.Drawing.Rectangle(0, 0, $img.Width, $img.Height)
$srcData = $img.LockBits($rect, [System.Drawing.Imaging.ImageLockMode]::ReadOnly, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)
$dstData = $result.LockBits($rect, [System.Drawing.Imaging.ImageLockMode]::WriteOnly, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)

$bytes = $srcData.Stride * $img.Height
$srcBuffer = New-Object byte[] $bytes
$dstBuffer = New-Object byte[] $bytes

[System.Runtime.InteropServices.Marshal]::Copy($srcData.Scan0, $srcBuffer, 0, $bytes)

# Process: For each BGRA pixel, if it's near white, make alpha = 0; if light, reduce alpha
for ($i = 0; $i -lt $bytes; $i += 4) {
    $b = $srcBuffer[$i]
    $g = $srcBuffer[$i + 1]
    $r = $srcBuffer[$i + 2]
    # Check if pixel is whitish (background)
    $minVal = [Math]::Min([Math]::Min($r, $g), $b)
    $maxVal = [Math]::Max([Math]::Max($r, $g), $b)
    if ($minVal -gt 235 -and ($maxVal - $minVal) -lt 20) {
        # Fully transparent
        $dstBuffer[$i] = 0
        $dstBuffer[$i + 1] = 0
        $dstBuffer[$i + 2] = 0
        $dstBuffer[$i + 3] = 0
    } elseif ($minVal -gt 200 -and ($maxVal - $minVal) -lt 30) {
        # Partial transparency for soft edges
        $alpha = [byte](255 - $minVal)
        $dstBuffer[$i] = $b
        $dstBuffer[$i + 1] = $g
        $dstBuffer[$i + 2] = $r
        $dstBuffer[$i + 3] = $alpha * 4
        if ($dstBuffer[$i + 3] -gt 255) { $dstBuffer[$i + 3] = 255 }
    } else {
        $dstBuffer[$i] = $b
        $dstBuffer[$i + 1] = $g
        $dstBuffer[$i + 2] = $r
        $dstBuffer[$i + 3] = 255
    }
}

[System.Runtime.InteropServices.Marshal]::Copy($dstBuffer, 0, $dstData.Scan0, $bytes)
$img.UnlockBits($srcData)
$result.UnlockBits($dstData)

$result.Save($dstPath, [System.Drawing.Imaging.ImageFormat]::Png)
$img.Dispose()
$result.Dispose()

Write-Host "Saved: $dstPath"
