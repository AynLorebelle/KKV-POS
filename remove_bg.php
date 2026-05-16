<?php
/**
 * Background remover — replaces the logo's cream background with transparency.
 * Uses flood-fill tolerance-based color replacement.
 */

$inputPath  = __DIR__ . '/public/images/kkv logo.png';
$outputPath = __DIR__ . '/public/images/kkv logo.png'; // overwrite in place

if (!file_exists($inputPath)) {
    die("❌ Source file not found: $inputPath\n");
}

// Load image
$src = imagecreatefrompng($inputPath);
if (!$src) die("❌ Failed to load image.\n");

$width  = imagesx($src);
$height = imagesy($src);

// Create RGBA output canvas
$out = imagecreatetruecolor($width, $height);
imagealphablending($out, false);
imagesavealpha($out, true);
$transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
imagefill($out, 0, 0, $transparent);

// Copy original pixels
imagecopy($out, $src, 0, 0, 0, 0, $width, $height);
imagealphablending($out, false);
imagesavealpha($out, true);

// Sample background colour from top-left corner
$bgRgb = imagecolorat($src, 3, 3);
$bgR   = ($bgRgb >> 16) & 0xFF;
$bgG   = ($bgRgb >>  8) & 0xFF;
$bgB   = ($bgRgb      ) & 0xFF;
echo "Background colour sampled: rgb($bgR, $bgG, $bgB)\n";

// Tolerance — how similar to the bg colour a pixel must be to get erased
$tolerance = 28;

// Iterative flood-fill from all 4 corners
function floodFill($img, $out, $x, $y, $bgR, $bgG, $bgB, $tolerance, $width, $height) {
    $stack = [[$x, $y]];
    $visited = [];

    while (!empty($stack)) {
        [$cx, $cy] = array_pop($stack);
        $key = "$cx,$cy";
        if (isset($visited[$key])) continue;
        if ($cx < 0 || $cy < 0 || $cx >= $width || $cy >= $height) continue;

        $visited[$key] = true;

        $rgb = imagecolorat($img, $cx, $cy);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >>  8) & 0xFF;
        $b = ($rgb      ) & 0xFF;

        $dist = sqrt(pow($r - $bgR, 2) + pow($g - $bgG, 2) + pow($b - $bgB, 2));
        if ($dist > $tolerance) continue;

        // Make pixel transparent
        imagesetpixel($out, $cx, $cy, imagecolorallocatealpha($out, 0, 0, 0, 127));

        $stack[] = [$cx + 1, $cy];
        $stack[] = [$cx - 1, $cy];
        $stack[] = [$cx, $cy + 1];
        $stack[] = [$cx, $cy - 1];
    }
}

// Flood fill from all 4 corners
floodFill($src, $out, 0,          0,           $bgR, $bgG, $bgB, $tolerance, $width, $height);
floodFill($src, $out, $width - 1, 0,           $bgR, $bgG, $bgB, $tolerance, $width, $height);
floodFill($src, $out, 0,          $height - 1, $bgR, $bgG, $bgB, $tolerance, $width, $height);
floodFill($src, $out, $width - 1, $height - 1, $bgR, $bgG, $bgB, $tolerance, $width, $height);

// Save
imagealphablending($out, false);
imagesavealpha($out, true);

if (imagepng($out, $outputPath, 9)) {
    echo "✅ Background removed! Saved to: $outputPath\n";
    echo "   File size: " . number_format(filesize($outputPath)) . " bytes\n";
} else {
    echo "❌ Failed to save.\n";
}

imagedestroy($src);
imagedestroy($out);
