document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.getElementById('uploadForm');
    const uploadMessage = document.getElementById('uploadMessage');
    const profileImagePreview = document.getElementById('profileImagePreview');

    uploadForm.addEventListener('submit', function (e) {
        //do not reload the page
        e.preventDefault();

        const formData = new FormData(uploadForm);

        fetch('../scripts/uploadImage.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json()) //jasonify :()
            .then(data => {
                uploadMessage.classList.remove('success', 'error');
                uploadMessage.style.display = 'block';

                if (data.success) {
                    uploadMessage.classList.add('success');
                    uploadMessage.textContent = data.message;

                    // update the image preview if element exists
                    if (profileImagePreview) {
                        profileImagePreview.src = data.filepath;
                    } else {
                        // If no image element exists yet, create one
                        const container = document.querySelector('.profileImagePreviewContainer');
                        if (container) {
                            const img = document.createElement('img');
                            img.id = 'profileImagePreview';
                            img.src = data.filepath;
                            img.alt = 'Profile Image';
                            container.appendChild(img);
                        }
                    }

                    // clear the file input
                    uploadForm.reset();
                } else {
                    uploadMessage.classList.add('error');
                    uploadMessage.textContent = data.message;
                }
            })
            //i am eror
            .catch(error => {
                uploadMessage.classList.remove('success');
                uploadMessage.classList.add('error');
                uploadMessage.style.display = 'block';
                uploadMessage.textContent = 'Error: ' + error.message;
            });
    });
});

