(function () {
    const profileOwnerId = Number(document.body.dataset.profileOwnerId || 0);
    const currentUserRole = Number(document.body.dataset.currentUserRole || 0);

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

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
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

                if (replyContainer.innerHTML.trim() !== '') {
                    replyContainer.innerHTML = '';
                    const comment = comments.find(c => c.id == commentId);
                    button.textContent = `Show ${comment.reply_count} repl${comment.reply_count > 1 ? 'ies' : 'y'}`;
                    return;
                }

                const replies = await loadReplies(commentId);
                if (!replies.length) {
                    replyContainer.innerHTML = '<small>No replies.</small>';
                    return;
                }

                replyContainer.innerHTML = '';
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

    async function fetchLikeState() {
        const res = await fetch(`../scripts/profile_like.php?profile_owner_id=${profileOwnerId}`);
        const data = await res.json();
        if (data.error) {
            console.error('Like state error', data.error);
            return;
        }
        const likeCount = document.getElementById('profileLikeCount');
        const status = document.getElementById('profileVoteStatus');

        likeCount.textContent = data.likes;
        status.textContent = data.user_vote === 1 ? 'You liked your profile.' :
            data.user_vote === -1 ? 'You disliked your profile.' : 'You have not voted yet.';

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

    async function init() {
        document.getElementById('profileLikeBtn').addEventListener('click', () => setProfileVote('like'));
        document.getElementById('profileDislikeBtn').addEventListener('click', () => setProfileVote('dislike'));

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

        await fetchLikeState();
        await loadProfileComments();
    }

    document.addEventListener('DOMContentLoaded', init);
})();