<?php

$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo "✅ The 'public/storage' link already exists.";
} else {
    if (symlink($target, $link)) {
        echo "✅ Symlink created successfully: public/storage → storage/app/public";
    } else {
        echo "❌ Failed to create symlink. Your hosting may not allow symlinks.";
    }
}
