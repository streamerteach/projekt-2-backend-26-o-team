<?php
/*
if (isset($_SESSION["loggedin"])) {
    include "../scripts/sessionhandler.php";
} else {
    header("HTTP/1.1 401 Unauthorized");
    print_r($_SESSION);
    echo "You must be logged in to access this page.";
    exit;
}
*/
//for debug and dev only
include "../scripts/sessionhandler.php";
include "../scripts/timeToDate.php";
include "../scripts/databaseConnection.php";

//fetch current user details for display
$userDetails = [];
if (isset($_SESSION['username'])) {
    $conn = create_conn();
$stmt = $conn->prepare("SELECT id, realname, bio, salary, preference, likes, role FROM profiles WHERE username = :u");
    $stmt->execute([':u' => $_SESSION['username']]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="../profile/profileStylesheet.css">
</head>

<body>
    <?php include "../scripts/nav.php"; ?>
    <div id="pageContentCentering">
        <div id="profileBox">

            <?php
            if (isset($_SESSION["username"]))
                print("<div id='user'>Logged in as: <bold style='font-weight:bold;'>" . $_SESSION["username"] . "<bold></div>")
            ?>

                <div class="profileImageDisplay">
                    <img src="../media/upload/<?php echo $_SESSION["username"] ?>_profile.jpg" alt="Profile Image">
                </div>


            <?php if (!empty($userDetails)): ?>
                <div class="profileInfo">
                    <p><strong>Real name:</strong> <?php echo htmlspecialchars($userDetails['realname']); ?></p>
                    <p><strong>Bio:</strong> <?php echo htmlspecialchars($userDetails['bio']); ?></p>
                    <p><strong>Salary:</strong> <?php echo htmlspecialchars($userDetails['salary']); ?></p>
                    <p><strong>Preference:</strong> <?php
                        $map = ['0'=> 'All', '1'=>'Men', '2'=>'Women', '3'=>'Other'];
                        echo $map[$userDetails['preference']] ?? 'Unknown';
                    ?></p>
                    <p><strong>Likes:</strong> <span id="profileLikeCount"><?php echo htmlspecialchars($userDetails['likes'] ?? '0'); ?></span></p>
                    <div id="profileVoteArea">
                        <button id="profileLikeBtn" type="button">Like</button>
                        <button id="profileDislikeBtn" type="button">Dislike</button>
                        <p id="profileVoteStatus" style="font-size:0.9rem; margin:5px 0 0;">You have not voted yet.</p>
                    </div>
                </div>
            <?php endif; ?>

            <div>
                <button type="button" onclick="location.href='../profile/editProfile.php'">Edit Profile</button>
                <button type="button" onclick="location.href='../scripts/logout.php'">Logout</button>
                <button onclick="location.href='../scripts/cube.php'">Cube</button>
            </div>

            <div id="profileCommentsSection">
                <h2>Your Profile Comments</h2>
                <form id="profileCommentForm">
                    <textarea id="profileCommentText" placeholder="Write a comment on your profile" rows="4" cols="50" required></textarea>
                    <input type="hidden" id="profileParentCommentId" value="">
                    <button type="submit">Post Comment</button>
                </form>

                <div id="profileCommentsContainer"></div>
            </div>

        </div>
        <div id="dateTimeBox">
            <form action="../scripts/timeToDate.php" method="get">
                <label for="dateTimeInput">Calculate hours till your date. Input your meeting time</label><br><br>
                <input type="datetime-local" id="dateTimeInput" name="dateTimeInput">
                <input type="submit" value="Calculate">
            </form>
            <div id="output">
            </div>
        </div>

        <script src="../scripts/timeDifference.js"></script>

        <script>
            const profileOwnerId = <?php echo json_encode($userDetails['id'] ?? null); ?>;
            const currentUserRole = <?php echo json_encode($userDetails['role'] ?? 0); ?>;

            async function loadProfileComments() {
                const res = await fetch(`../scripts/profile_comments.php?profile_owner_id=${profileOwnerId}`);
                const data = await res.json();
                if (data.error) {
                    document.getElementById('profileCommentsContainer').innerHTML = `<p>Error loading comments: ${data.error}</p>`;
                    return;
                }
                renderProfileComments(data.comments);
            }

            async function loadReplies(commentId) {
                const res = await fetch(`../scripts/profile_comments.php?parent_comment_id=${commentId}`);
                const data = await res.json();
                return data.replies || [];
            }

            function renderProfileComments(comments) {
                const container = document.getElementById('profileCommentsContainer');
                container.innerHTML = '';

                if (!Array.isArray(comments) || comments.length === 0) {
                    container.innerHTML = '<p>No comments yet on your profile.</p>';
                    return;
                }

                comments.forEach(comment => {
                    const el = document.createElement('div');
                    el.className = 'profile-comment';
                    el.innerHTML = `
                        <div class="comment-meta"><strong>${escapeHtml(comment.author_username || 'Unknown')}</strong> <small>${escapeHtml(comment.created_at)}</small></div>
                        <div class="comment-text">${escapeHtml(comment.content)}</div>
                        <div class="comment-actions">
                            <button type="button" class="reply-button" data-id="${comment.id}">Reply</button>
                            ${comment.reply_count > 0 ? `<button type="button" class="show-replies-button" data-id="${comment.id}">Show ${comment.reply_count} repl${comment.reply_count > 1 ? 'ies' : 'y'}</button>` : ''}
                            ${currentUserRole >= 3 ? `<button type="button" class="comment-delete-btn" data-id="${comment.id}">Delete</button>` : ''}
                        </div>
                        <div id="replies-${comment.id}" class="comment-replies"></div>
                    `;
                    container.appendChild(el);
                });

                container.querySelectorAll('.reply-button').forEach(button => {
                    button.addEventListener('click', () => {
                        document.getElementById('profileParentCommentId').value = button.dataset.id;
                        document.getElementById('profileCommentText').focus();
                    });
                });

                container.querySelectorAll('.show-replies-button').forEach(button => {
                    button.addEventListener('click', async () => {
                        const commentId = button.dataset.id;
                        const replyContainer = document.getElementById(`replies-${commentId}`);
                        if (replyContainer.innerHTML) {
                            replyContainer.innerHTML = '';
                            button.textContent = `Show ${comment.reply_count} repl${comment.reply_count > 1 ? 'ies' : 'y'}`;
                            return;
                        }
                        const replies = await loadReplies(commentId);
                        if (!replies.length) {
                            replyContainer.innerHTML = '<small>No replies.</small>';
                            return;
                        }
                        replies.forEach(reply => {
                            const r = document.createElement('div');
                            r.className = 'comment-reply';
                            r.innerHTML = `
                                <div class="comment-meta"><strong>${escapeHtml(reply.author_username || 'Unknown')}</strong> <small>${escapeHtml(reply.created_at)}</small></div>
                                <div class="comment-text">${escapeHtml(reply.content)}</div>
                            `;
                            replyContainer.appendChild(r);
                        });
                        button.textContent = 'Hide replies';
                    });
                });

                container.querySelectorAll('.comment-delete-btn').forEach(button => {
                    button.addEventListener('click', async () => {
                        const commentId = button.dataset.id;
                        const response = await fetch('../scripts/profile_comments.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'delete', comment_id: commentId })
                        });
                        const result = await response.json();
                        if (result.error) {
                            alert('Error deleting comment: ' + result.error);
                            return;
                        }
                        await loadProfileComments();
                    });
                });
            }

            function escapeHtml(text) {
                const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
                return String(text).replace(/[&<>"']/g, m => map[m]);
            }

            async function fetchLikeState() {
                const res = await fetch(`../scripts/profile_like.php?profile_owner_id=${profileOwnerId}`);
                const data = await res.json();
                if (data.error) {
                    console.error('Like state error', data.error);
                    return;
                }
                document.getElementById('profileLikeCount').textContent = data.likes;
                const status = document.getElementById('profileVoteStatus');
                if (data.user_vote === 1) {
                    status.textContent = 'You liked your profile.';
                } else if (data.user_vote === -1) {
                    status.textContent = 'You disliked your profile.';
                } else {
                    status.textContent = 'You have not voted yet.';
                }
                document.getElementById('profileLikeBtn').disabled = data.user_vote === 1;
                document.getElementById('profileDislikeBtn').disabled = data.user_vote === -1;
            }

            async function setProfileVote(action) {
                const res = await fetch('../scripts/profile_like.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ profile_owner_id: profileOwnerId, action })
                });
                const data = await res.json();
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }
                await fetchLikeState();
                await loadProfileComments();
            }

            document.getElementById('profileLikeBtn').addEventListener('click', () => setProfileVote('like'));
            document.getElementById('profileDislikeBtn').addEventListener('click', () => setProfileVote('dislike'));

            fetchLikeState();

            document.getElementById('profileCommentForm').addEventListener('submit', async event => {
                event.preventDefault();
                const content = document.getElementById('profileCommentText').value.trim();
                if (!content) return;

                const parentCommentId = document.getElementById('profileParentCommentId').value || null;

                const response = await fetch('../scripts/profile_comments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ profile_owner_id: profileOwnerId, parent_comment_id: parentCommentId, content })
                });
                const json = await response.json();
                if (json.error) {
                    alert('Error: ' + json.error);
                    return;
                }

                document.getElementById('profileCommentText').value = '';
                document.getElementById('profileParentCommentId').value = '';
                await loadProfileComments();
            });

            loadProfileComments();
        </script>
</body>

</html>