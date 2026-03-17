(function () {
    const profileOwnerId = Number(document.body.dataset.profileOwnerId || 0);
    const profileOwnerUsername = document.body.dataset.profileOwnerUsername || '';
    const currentUserRole = Number(document.body.dataset.currentUserRole || 0);

    async function fetchComments() {
        const response = await fetch(`../scripts/profile_comments.php?profile_owner_id=${profileOwnerId}`);
        const json = await response.json();
        //hello jason here
        if (json.error) {
            console.error(json.error);
            return;
        }
        renderComments(json.comments);
    }

    async function fetchLikeState() {
        const response = await fetch(`../scripts/profile_like.php?profile_owner_id=${profileOwnerId}`);
        const json = await response.json();
        if (json.error) {
            console.error('Like state error', json.error);
            return;
        }
        const likeCountEl = document.getElementById('profileLikeCount');
        const voteStatusEl = document.getElementById('voteStatus');

        likeCountEl.textContent = json.likes;
        const userVote = json.user_vote || 0;

        voteStatusEl.textContent = userVote === 1 ? 'You liked this profile.' :
            userVote === -1 ? 'You disliked this profile.' : 'You have not voted yet.';

        updateVoteButtons(userVote);
    }

    function updateVoteButtons(userVote) {
        document.getElementById('likeBtn').disabled = (userVote === 1);
        document.getElementById('dislikeBtn').disabled = (userVote === -1);
    }

    async function setVote(action) {
        const response = await fetch('../scripts/profile_like.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ profile_owner_id: profileOwnerId, profile_owner_username: profileOwnerUsername, action })
        });
        const json = await response.json();
        if (json.error) {
            alert('Error: ' + json.error);
            return;
        }
        await fetchLikeState();
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

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        //i did not come up with this return statement. found it no stack
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function renderComments(comments) {
        const container = document.getElementById('commentsContainer');
        container.innerHTML = '';

        if (!Array.isArray(comments) || comments.length === 0) {
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
                    ${currentUserRole >= 3 ? `<button class="comment-delete-btn" data-comment-id="${comment.id}">Delete</button>` : ''}
                </div>
                <div id="replies-${comment.id}" class="comment-replies"></div>
            `;
            container.appendChild(commentEl);
        });

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
                    const comment = comments.find(c => c.id == parentId);
                    btn.textContent = `Show ${comment.reply_count} repl${comment.reply_count > 1 ? 'ies' : 'y'}`;
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

        container.querySelectorAll('.comment-delete-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const commentId = btn.getAttribute('data-comment-id');
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
                await fetchComments();
            });
        });
    }

    async function init() {
        const commentForm = document.getElementById('commentForm');
        if (!commentForm) return;

        commentForm.addEventListener('submit', async event => {
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

        document.getElementById('likeBtn').addEventListener('click', () => setVote('like'));
        document.getElementById('dislikeBtn').addEventListener('click', () => setVote('dislike'));

        if (!profileOwnerId || profileOwnerId <= 0) {
            console.error('profileOwnerId is invalid:', profileOwnerId, 'profileOwnerUsername:', profileOwnerUsername);
        }

        await fetchLikeState();
        await fetchComments();
    }

    document.addEventListener('DOMContentLoaded', init);
})();