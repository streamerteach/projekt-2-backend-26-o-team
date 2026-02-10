<?php

//return most recent image file from user folder
function getLatestProfileImage($userFolder)
{
    if (!is_dir($userFolder)) {
        return null;
    }
    //array diff to remove dots
    $files = array_diff(scandir($userFolder, SCANDIR_SORT_DESCENDING), array('.', '..'));

    if (empty($files)) {
        return null;
    }

    //return the most recent file
    foreach ($files as $file) {
        $filePath = $userFolder . DIRECTORY_SEPARATOR . $file;
        if (is_file($filePath)) {
            return $file;
        }
    }

    return null;
}
//lazy copy of above for second latest
function getSecondLatestProfileImage($userFolder)
{
    if (!is_dir($userFolder)) {
        return null;
    }
    //same as above
    $files = array_diff(scandir($userFolder, SCANDIR_SORT_DESCENDING), array('.', '..'));

    if (empty($files)) {
        return null;
    }

    //return the second most recent
    $count = 0;
    foreach ($files as $file) {
        $filePath = $userFolder . DIRECTORY_SEPARATOR . $file;
        if (is_file($filePath)) {
            $count++;
            if ($count === 2) {
                return $file;
            }
        }
    }

    return null;
}

//get latest user profile image path
function getUserImagePath($username)
{
    if (!$username) return null;

    $folder = dirname(__DIR__) . "/media/upload/" . $username;

    if (!is_dir($folder)) return null;

    $files = array_filter(scandir($folder), function ($f) use ($folder) {
        return $f !== '.' && $f !== '..' && is_file($folder . '/' . $f);
    });

    if (empty($files)) return null;

    usort($files, function ($a, $b) use ($folder) {
        return filemtime($folder . '/' . $b) - filemtime($folder . '/' . $a);
    });

    return "../media/upload/$username/" . $files[0];
}

function getUserSecondImagePath($username)
{
    if (!$username) return null;

    $folder = dirname(__DIR__) . "/media/upload/" . $username;

    if (!is_dir($folder)) return null;

    $files = array_filter(scandir($folder), function ($f) use ($folder) {
        return $f !== '.' && $f !== '..' && is_file($folder . '/' . $f);
    });

    if (count($files) < 2) return null;

    usort($files, function ($a, $b) use ($folder) {
        return filemtime($folder . '/' . $b) - filemtime($folder . '/' . $a);
    });

    return "../media/upload/$username/" . $files[1];
}
