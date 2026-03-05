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
            msg.style.color = json.success ? 'green' : 'red';
            msg.textContent = json.message;
            if (json.success) {
                //after a brief pause redirect to home/landing page
                setTimeout(() => {
                    window.location.href = '../landingPage/index.php';
                }, 1500);
            }
        })
        .catch(err => {
            msg.style.color = 'red';
            msg.textContent = 'Network/server error.';
            console.error(err);
        });
});