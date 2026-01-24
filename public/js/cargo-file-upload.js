document.addEventListener('DOMContentLoaded', function() {
    // Предпросмотр главной фотографии
    const photoInput = document.getElementById('photo');
    const photoPreviewContainer = document.getElementById('photo-preview-container');

    if (photoInput && photoPreviewContainer) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreviewContainer.innerHTML = `<img id="photo-preview" src="${e.target.result}" alt="Предпросмотр" style="max-width: 100%; max-height: 200px; object-fit: contain;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Предпросмотр дополнительных файлов
    const filesInput = document.getElementById('files');
    const filesPreview = document.getElementById('files-preview');
    const filesPreviewContainer = document.getElementById('files-preview-container');

    if (filesInput && filesPreview && filesPreviewContainer) {
        filesInput.addEventListener('change', function(e) {
            filesPreview.innerHTML = '';

            const files = Array.from(e.target.files);

            if (files.length > 0) {
                filesPreviewContainer.style.display = 'block';

                files.forEach((file, index) => {
                    const div = document.createElement('div');
                    div.className = 'col-md-2 mb-2';

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            div.innerHTML = `
                                <div class="card">
                                    <img src="${e.target.result}"
                                         class="card-img-top"
                                         style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-truncate d-block" title="${file.name}">${file.name}</small>
                                        <small class="text-muted">${formatFileSize(file.size)}</small>
                                    </div>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        let iconClass = 'fas fa-file text-muted';
                        if (file.type === 'application/pdf') {
                            iconClass = 'fas fa-file-pdf text-danger';
                        } else if (file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                            iconClass = 'fas fa-file-word text-primary';
                        } else if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
                            iconClass = 'fas fa-file-excel text-success';
                        }

                        div.innerHTML = `
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="${iconClass} fa-2x"></i>
                                    <p class="mt-2 mb-0 small text-truncate" title="${file.name}">${file.name}</p>
                                    <small class="text-muted">${formatFileSize(file.size)}</small>
                                </div>
                            </div>
                        `;
                    }

                    filesPreview.appendChild(div);
                });
            } else {
                filesPreviewContainer.style.display = 'none';
            }
        });
    }
});

// Форматирование размера файла
function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
