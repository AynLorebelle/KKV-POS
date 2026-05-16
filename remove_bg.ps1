Add-Type -AssemblyName System.Drawing
$bmp = New-Object System.Drawing.Bitmap("c:\xampp\htdocs\CAVAN_FINAL_PROJECT\public\images\kkv logo.png")
$color = $bmp.GetPixel(0, 0)
$bmp.MakeTransparent($color)
$bmp.Save("c:\xampp\htdocs\CAVAN_FINAL_PROJECT\public\images\kkv logo transparent.png", [System.Drawing.Imaging.ImageFormat]::Png)
$bmp.Dispose()
