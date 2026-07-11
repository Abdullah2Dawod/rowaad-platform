Add-Type -AssemblyName System.Drawing

function Convert-LogoToDark {
    param([string]$SrcPath, [string]$DstPath)

    $img = [System.Drawing.Bitmap]::new($SrcPath)
    $W = $img.Width
    $H = $img.Height
    Write-Host "Processing: $SrcPath  ($W x $H)"

    $rect = New-Object System.Drawing.Rectangle(0, 0, $W, $H)
    $data = $img.LockBits($rect, [System.Drawing.Imaging.ImageLockMode]::ReadWrite, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)
    $bytes = $data.Stride * $H
    $buf = New-Object byte[] $bytes
    [System.Runtime.InteropServices.Marshal]::Copy($data.Scan0, $buf, 0, $bytes)

    # === ENHANCED DARK MODE COLOR MAPPING ===
    # Strategy: Convert navy R to BRIGHT WHITE-TEAL gradient
    # Brighten teal loop to vibrant cyan
    # Keep highlights ultra-bright

    for ($i = 0; $i -lt $bytes; $i += 4) {
        $b = $buf[$i]
        $g = $buf[$i + 1]
        $r = $buf[$i + 2]
        $a = $buf[$i + 3]

        if ($a -lt 20) { continue }

        $brightness = ($r + $g + $b) / 3.0
        $isBlueish = ($b -gt $r) -and ($b -gt $g - 20)
        $isGreenish = ($g -gt $r + 15) -and ($g -gt 100)

        # DARK NAVY region (the R shape) -> BRIGHT WHITE-LIGHT-TEAL
        if ($brightness -lt 110 -and $isBlueish -and -not $isGreenish) {
            # Map dark navy to bright soft sky-white
            # Output target: #E0F4F7 to #FFFFFF range
            $factor = ($brightness / 110.0)
            $targetR = 255 - [int](40 * $factor)
            $targetG = 255 - [int](20 * $factor)
            $targetB = 255 - [int](10 * $factor)

            $buf[$i]     = [byte]$targetB
            $buf[$i + 1] = [byte]$targetG
            $buf[$i + 2] = [byte]$targetR
        }
        # MID-NAVY (shadows/edges) -> light teal
        elseif ($brightness -lt 170 -and $isBlueish -and -not $isGreenish) {
            $factor = ($brightness / 170.0)
            $targetR = 180 + [int](60 * $factor)
            $targetG = 220 + [int](30 * $factor)
            $targetB = 235 + [int](20 * $factor)

            $buf[$i]     = [byte][Math]::Min(255, $targetB)
            $buf[$i + 1] = [byte][Math]::Min(255, $targetG)
            $buf[$i + 2] = [byte][Math]::Min(255, $targetR)
        }
        # TEAL region -> BRIGHTER VIBRANT TEAL/CYAN
        elseif ($isGreenish -or (($g -gt 90) -and ($b -gt 100) -and ($r -lt $g))) {
            # Boost teal to vibrant cyan
            $newR = [byte][Math]::Min(255, $r + 30)
            $newG = [byte][Math]::Min(255, $g + 50)
            $newB = [byte][Math]::Min(255, $b + 50)
            $buf[$i]     = $newB
            $buf[$i + 1] = $newG
            $buf[$i + 2] = $newR
        }
        # Generic dark pixels (anti-aliasing edges) -> brighten significantly
        elseif ($brightness -lt 150) {
            $boost = [int](200 - $brightness)
            $buf[$i]     = [byte][Math]::Min(255, $b + $boost)
            $buf[$i + 1] = [byte][Math]::Min(255, $g + $boost)
            $buf[$i + 2] = [byte][Math]::Min(255, $r + $boost)
        }
        # Light grays/whites - keep bright
        else {
            # Make highlights pop with extra brightness
            $buf[$i]     = [byte][Math]::Min(255, $b + 15)
            $buf[$i + 1] = [byte][Math]::Min(255, $g + 15)
            $buf[$i + 2] = [byte][Math]::Min(255, $r + 15)
        }
    }

    [System.Runtime.InteropServices.Marshal]::Copy($buf, 0, $data.Scan0, $bytes)
    $img.UnlockBits($data)
    $img.Save($DstPath, [System.Drawing.Imaging.ImageFormat]::Png)
    $img.Dispose()
    Write-Host "Saved: $DstPath"
}

Convert-LogoToDark `
    -SrcPath "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo-symbol.png" `
    -DstPath "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo-symbol-dark.png"

Convert-LogoToDark `
    -SrcPath "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo-transparent.png" `
    -DstPath "D:\Laravel-Projects\rowaad-platform\public\images\rowaad-logo-transparent-dark.png"

Write-Host "Done!"
