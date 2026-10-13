<?php

$destination = dirname(__DIR__).'/public/images/public';

if (! is_dir($destination)) {
    mkdir($destination, 0755, true);
}

foreach (array_slice($argv, 1) as $argument) {
    [$name, $source] = explode('=', $argument, 2);

    if (! preg_match('/^[a-z-]+$/', $name)) {
        throw new RuntimeException('Invalid image name.');
    }

    $original = imagecreatefrompng($source);

    foreach ([1536 => '', 640 => '-640'] as $width => $suffix) {
        $height = (int) round(imagesy($original) * $width / imagesx($original));
        $image = imagescale($original, $width, $height, IMG_BICUBIC_FIXED);
        $path = $destination.'/'.$name.'-v1'.$suffix.'.webp';

        if (! imagewebp($image, $path, 82)) {
            throw new RuntimeException('Could not encode '.$path);
        }

        imagedestroy($image);
        echo basename($path).' '.filesize($path)." bytes\n";
    }

    imagedestroy($original);
}
