document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.getElementById('uploadForm');
    const uploadMessage = document.getElementById('uploadMessage');

    const profileImagePreview = document.getElementById('profileImagePreview'); //newest
    const oldProfileImagePreview = document.getElementById('oldProfileImagePreview'); //second newest

    uploadForm.addEventListener('submit', function (e) {
        e.preventDefault(); //do not reload saar

        const formData = new FormData(uploadForm);

        fetch('../scripts/uploadImage.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {

                uploadMessage.classList.remove('success', 'error');
                uploadMessage.style.display = 'block';

                if (data.success) {
                    uploadMessage.classList.add('success');
                    uploadMessage.textContent = data.message;

                    //newest image uploaded
                    if (profileImagePreview && data.filepath) {
                        profileImagePreview.src = data.filepath + '?t=' + Date.now(); //cache bust
                    }

                    //second latest image
                    if (oldProfileImagePreview && data.secondLatestImage) {
                        oldProfileImagePreview.src = data.secondLatestImage + '?t=' + Date.now();
                        oldProfileImagePreview.style.display = 'block';
                    }

                    uploadForm.reset();

                } else {
                    uploadMessage.classList.add('error');
                    uploadMessage.textContent = data.message;
                }
            })
            .catch(error => { //iam eror
                uploadMessage.classList.remove('success');
                uploadMessage.classList.add('error');
                uploadMessage.style.display = 'block';
                uploadMessage.textContent = 'Error: ' + error.message;
            });
    });
});
