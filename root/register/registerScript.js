document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.target;
    const msgDiv = document.getElementById('registerMessage');
    msgDiv.textContent = '';
    const data = new FormData(form);
    fetch('./register.php', {
        method: 'POST',
        body: data
    })
        .then(r => r.json())
        .then(json => {
            msgDiv.classList.remove('message-success', 'message-error');
            msgDiv.classList.add(json.success ? 'message-success' : 'message-error');
            msgDiv.textContent = json.message;
            if (json.success) {
                form.reset();
            }
        })
        .catch(err => {
            msgDiv.classList.remove('message-success');
            msgDiv.classList.add('message-error');
            msgDiv.textContent = 'Network or server error.';
            console.error(err);
        });
});

//could be moved to scripts but im lazy