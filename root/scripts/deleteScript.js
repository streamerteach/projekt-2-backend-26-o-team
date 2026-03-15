//deletion logic
document.getElementById('deleteForm').addEventListener('submit', function (e) {
    e.preventDefault();
    if (!confirm('Are you absolutely sure you want to delete your profile? This action cannot be undone.')) {
        return;
    }
    const msg = document.getElementById('deleteMessage');
    msg.textContent = '';
    const form = e.target;
    const data = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        body: data
    })
        .then(r => r.json())
        .then(json => {
            msg.classList.remove('message-success', 'message-error');
            msg.classList.add(json.success ? 'message-success' : 'message-error');
            msg.textContent = json.message;
            if (json.success) {
                //after a brief pause redirect to home/landing page
                setTimeout(() => {
                    window.location.href = '../landingPage/index.php';
                }, 1500);
            }
        })
        .catch(err => {
            msg.classList.remove('message-success');
            msg.classList.add('message-error');
            msg.textContent = 'Network/server error.';
            console.error(err);
        });
});