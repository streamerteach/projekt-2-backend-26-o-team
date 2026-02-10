<?php
$commentFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "guestbook_comments.txt";
$commentsLimit = 5;

//handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    // only process if both name and comment are provided and not empty
    if (!empty($name) && !empty($comment)) {
        $time = date('d-m-Y H:i:s');

        //format: time|name|comment
        $entry = $time . '|' . $name . '|' . str_replace("\n", ' ', $comment) . PHP_EOL;

        file_put_contents($commentFile, $entry, FILE_APPEND | LOCK_EX);

        // redirect to prevent form resubmission on page reload
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

//read comments new first
$comments = [];
if (file_exists($commentFile)) {
    $allComments = array_reverse(
        file($commentFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    );
    //limit to 10 most recent comments
    $comments = array_slice($allComments, 0, $commentsLimit);
}
