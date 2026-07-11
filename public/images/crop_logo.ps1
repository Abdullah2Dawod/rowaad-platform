Add-Type -AssemblyName System.Drawing

$srcPath = "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo-transparent.png"
$dstPath = "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo-symbol.png"

$img = [System.Drawing.Bitmap]::new($srcPath)
$W = $img.Width
$H = $img.Height
Write-Host "Original: $W x $H"

# Step 1: Auto-detect symbol bounds by scanning non-transparent pixels in TOP HALF
$rect = New-Object System.Drawing.Rectangle(0, 0, $W, $H)
$data = $img.LockBits($rect, [System.Drawing.Imaging.ImageLockMode]::ReadOnly, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)
$bytes = $data.Stride * $H
$buf = New-Object byte[] $bytes
[System.Runtime.InteropServices.Marshal]::Copy($data.Scan0, $buf, 0, $bytes)
$img.UnlockBits($data)

# Scan only top 65% of image (above the text)
$scanLimit = [int]($H * 0.65)
$minX = $W
$maxX = 0
$minY = $H
$maxY = 0

for ($y = 0; $y -lt $scanLimit; $y++) {
    for ($x = 0; $x -lt $W; $x++) {
        $idx = ($y * $data.Stride) + ($x * 4)
        $alpha = $buf[$idx + 3]
        # If pixel is sufficiently opaque (part of logo content)
        if ($alpha -gt 30) {
            if ($x -lt $minX) { $minX = $x }
            if ($x -gt $maxX) { $maxX = $x }
            if ($y -lt $minY) { $minY = $y }
            if ($y -gt $maxY) { $maxY = $y }
        }
    }
}

Write-Host "Detected symbol bounds: x=$minX..$maxX y=$minY..$maxY"

# Step 2: Add generous padding around detected bounds
$padX = [int](($maxX - $minX) * 0.08)
$padY = [int](($maxY - $minY) * 0.08)

$cropX = [Math]::Max(0, $minX - $padX)
$cropY = [Math]::Max(0, $minY - $padY)
$cropW = [Math]::Min($W - $cropX, ($maxX - $minX) + ($padX * 2))
$cropH = [Math]::Min($H - $cropY, ($maxY - $minY) + ($padY * 2))

Write-Host "Crop region: x=$cropX y=$cropY w=$cropW h=$cropH"

# Step 3: Crop with high quality
$cropped = New-Object System.Drawing.Bitmap($cropW, $cropH, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)
$cropped.SetResolution($img.HorizontalResolution, $img.VerticalResolution)

$graphics = [System.Drawing.Graphics]::FromImage($cropped)
$graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
$graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
$graphics.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
$graphics.CompositingQuality = [System.Drawing.Drawing2D.CompositingQuality]::HighQuality

$srcRect = New-Object System.Drawing.Rectangle($cropX, $cropY, $cropW, $cropH)
$dstRect = New-Object System.Drawing.Rectangle(0, 0, $cropW, $cropH)
$graphics.DrawImage($img, $dstRect, $srcRect, [System.Drawing.GraphicsUnit]::Pixel)
$graphics.Dispose()

$cropped.Save($dstPath, [System.Drawing.Imaging.ImageFormat]::Png)
$img.Dispose()
$cropped.Dispose()

Write-Host "Saved: $dstPath"
$result = Get-Item $dstPath
Write-Host "Final size: $($result.Length) bytes"
