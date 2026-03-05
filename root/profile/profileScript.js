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
            msg.style.color = json.success ? 'green' : 'red';
            msg.textContent = json.message;
        })
        .catch(err => {
            msg.style.color = 'red';
            msg.textContent = 'Network/server error.';
            console.error(err);
        });
});