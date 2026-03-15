document.getElementById('profileDataForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const msg = document.getElementById('profileDataMessage');
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
        })
        .catch(err => {
            msg.classList.remove('message-success');
            msg.classList.add('message-error');
            msg.textContent = 'Network/server error.';
            console.error(err);
        });
});