<?php
include "../scripts/sessionhandler.php";
include "../scripts/databaseConnection.php";

if (!isset($_SESSION['username'])) {
    header('Location: ../login/index.php');
    exit;
}

$requestedUser = $_GET['user'] ?? null;
if (!$requestedUser) {
    http_response_code(400);
    echo "<p>Missing user parameter.</p>";
    exit;
}

$pdo = create_conn();
$stmt = $pdo->prepare('SELECT id, username, realname, zipcode, bio, salary, preference, email, likes, role FROM profiles WHERE username = :user');
$stmt->execute([':user' => $requestedUser]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo "<p>User not found.</p>";
    exit;
}

$prefMap = [
    0 => 'All',
    1 => 'Men',
    2 => 'Women',
    3 => 'Other'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile - <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="../profile/profileStylesheet.css">
</head>
<body>
    <?php include "../scripts/nav.php"; ?>

    <div id="pageContentCentering">
        <div id="profileBox">
            <h1>Profile: <?php echo htmlspecialchars($user['username']); ?></h1>

            <div class="profileImageDisplay">
                <img src="../media/upload/<?php echo htmlspecialchars($user['username']); ?>_profile.jpg" alt="Profile Image"
                     onerror="this.onerror=null; this.src='../media/Default.jpg';">
            </div>

            <div class="profileInfo">
                <p><strong>Real name:</strong> <?php echo htmlspecialchars($user['realname']); ?></p>
                <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($user['bio'] ?? 'No bio provided')); ?></p>
                <p><strong>Zip code:</strong> <?php echo htmlspecialchars($user['zipcode'] ?? 'Not specified'); ?></p>
                <p><strong>Salary:</strong> <?php echo htmlspecialchars($user['salary'] ?? 'Not specified'); ?></p>
                <p><strong>Preference:</strong> <?php echo htmlspecialchars($prefMap[$user['preference']] ?? 'Not specified'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'Not specified'); ?></p>
                <p><strong>Likes:</strong> <?php echo htmlspecialchars($user['likes'] ?? '0'); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role'] ?? 'Unknown'); ?></p>
            </div>

            <div class="profileActions">
                <button type="button" onclick="window.history.back();">Back</button>
                <button type="button" onclick="location.href='../homePage/index.php';">Home</button>
            </div>

            <div id="profileCommentsSection">
                <h2>Comments</h2>
                <form id="commentForm">
                    <textarea id="commentText" placeholder="Write your comment..." rows="4" cols="50" required></textarea>
                    <input type="hidden" id="parentCommentId" value="">
                    <button type="submit">Post Comment</button>
                </form>
                <div id="commentsContainer"></div>
            </div>

        </div>
    </div>

    <script>
        const profileOwnerId = <?php echo (int)$user['id']; ?>;
        const profileOwnerUsername = <?php echo json_encode($user['username']); ?>;

        async function fetchComments() {
            const response = await fetch(`../scripts/profile_comments.php?profile_owner_id=${profileOwnerId}`);
            const json = await response.json();
            if (json.error) {
                console.error(json.error);
                return;
            }
            renderComments(json.comments);
        }

        async function fetchReplies(parentCommentId) {
            const response = await fetch(`../scripts/profile_comments.php?parent_comment_id=${parentCommentId}`);
            const json = await response.json();
            if (json.error) {
                console.error(json.error);
                return [];
            }
            return json.replies || [];
        }

        function renderComments(comments) {
            const container = document.getElementById('commentsContainer');
            container.innerHTML = '';

            if (comments.length === 0) {
                container.innerHTML = '<p>No comments yet. Be the first to comment!</p>';
                return;
            }

            comments.forEach(comment => {
                const commentEl = document.createElement('div');
                commentEl.className = 'profile-comment';
                commentEl.innerHTML = `
                    <div class="comment-meta"><strong>${escapeHtml(comment.author_username || 'Unknown')}</strong> <small>${escapeHtml(comment.created_at)}</small></div>
                    <div class="comment-text">${escapeHtml(comment.content)}</div>
                    <div class="comment-actions">
                        <button class="comment-reply-btn" data-comment-id="${comment.id}">Reply</button>
                        ${comment.reply_count > 0 ? `<button class="comment-show-replies-btn" data-comment-id="${comment.id}">Show ${comment.reply_count} repl${comment.reply_count > 1 ? 'ies' : 'y'}</button>` : ''}
                    </div>
                    <div id="replies-${comment.id}" class="comment-replies"></div>
                `;

                container.appendChild(commentEl);
            });

            // Attach event listeners
            container.querySelectorAll('.comment-reply-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const parentId = btn.getAttribute('data-comment-id');
                    document.getElementById('parentCommentId').value = parentId;
                    document.getElementById('commentText').focus();
                });
            });

            container.querySelectorAll('.comment-show-replies-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const parentId = btn.getAttribute('data-comment-id');
                    const repliesContainer = document.getElementById(`replies-${parentId}`);
                    if (repliesContainer.innerHTML.trim() !== '') {
                        repliesContainer.innerHTML = '';
                        btn.textContent = `Show ${comments.find(c => c.id == parentId).reply_count} repl${comments.find(c => c.id == parentId).reply_count > 1 ? 'ies' : 'y'}`;
                        return;
                    }
                    const replies = await fetchReplies(parentId);
                    if (replies.length === 0) {
                        repliesContainer.innerHTML = '<small>No replies yet.</small>';
                        return;
                    }
                    repliesContainer.innerHTML = '';
                    replies.forEach(reply => {
                        const replyEl = document.createElement('div');
                        replyEl.className = 'comment-reply';
                        replyEl.innerHTML = `
                            <div class="comment-meta"><strong>${escapeHtml(reply.author_username || 'Unknown')}</strong> <small>${escapeHtml(reply.created_at)}</small></div>
                            <div class="comment-text">${escapeHtml(reply.content)}</div>
                        `;
                        repliesContainer.appendChild(replyEl);
                    });
                    btn.textContent = 'Hide replies';
                });
            });
        }

        function escapeHtml(text) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        document.getElementById('commentForm').addEventListener('submit', async event => {
            event.preventDefault();
            const content = document.getElementById('commentText').value.trim();
            const parentCommentId = document.getElementById('parentCommentId').value || null;
            if (!content) return;

            const response = await fetch('../scripts/profile_comments.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ profile_owner_id: profileOwnerId, profile_owner_username: profileOwnerUsername, parent_comment_id: parentCommentId, content })
            });
            const json = await response.json();
            if (json.error) {
                alert('Error: ' + json.error);
                return;
            }

            document.getElementById('commentText').value = '';
            document.getElementById('parentCommentId').value = '';
            await fetchComments();
        });

        // Validation aid
        if (!profileOwnerId || profileOwnerId <= 0) {
            console.error('profileOwnerId is invalid:', profileOwnerId, 'profileOwnerUsername:', profileOwnerUsername);
        }


        fetchComments();
    </script>
</body>
</html>
