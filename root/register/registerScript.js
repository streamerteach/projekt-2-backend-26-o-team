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
            msgDiv.style.color = json.success ? 'green' : 'red';
            msgDiv.textContent = json.message;
            if (json.success) {
                form.reset();
            }
        })
        .catch(err => {
            msgDiv.style.color = 'red';
            msgDiv.textContent = 'Network or server error.';
            console.error(err);
        });
});