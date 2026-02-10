<?php

//return most recent image file from user folder
function getLatestProfileImage($userFolder)
{
    //if no user dir stop
    if (!is_dir($userFolder)) {
        return null;
    }
    //array diff to remove dots
    $files = array_diff(scandir($userFolder, SCANDIR_SORT_DESCENDING), array('.', '..'));

    //no image ;<
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

//get latest user profile image path
function getLatestUserProfileImage($username)
{
    if (empty($username)) {
        return null;
    }

    $userFolder = dirname(__DIR__) . DIRECTORY_SEPARATOR . "media" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $username;

    if (!is_dir($userFolder)) {
        return null;
    }

    $latestImage = getLatestProfileImage($userFolder);

    if ($latestImage) {
        return "../media/upload/" . $username . "/" . $latestImage;
    }

    return null;
}
