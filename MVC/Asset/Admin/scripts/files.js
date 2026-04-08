function getAppRoot() {
    const marker = '/MVC/index.php';
    const path = window.location.pathname;
    const markerIndex = path.indexOf(marker);

    return markerIndex === -1 ? '' : path.slice(0, markerIndex);
}

export async function getFullFilepath(filename, defaultFile) {
    if (!filename || filename === 'N/A') {
        return defaultFile;
    }

    const fullFilePath = getFileBucketUrl(filename);
    try {
        const response = await fetch(fullFilePath);
        if (!response.ok) {
            return defaultFile;
        }
        return fullFilePath;
    } catch {
        return defaultFile;
    }
}

export async function openFileDialog(accept = 'image/*') {
    return new Promise((resolve, reject) => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = accept;

        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                resolve(file);
            } else {
                reject(new Error('No file selected'));
            }
        };

        input.click();
    });
}

export function getFileBucketUrl(filename) {
    let normalizedFilename = String(filename).trim();

    if (/^https?:\/\//i.test(normalizedFilename)) {
        normalizedFilename = new URL(normalizedFilename).pathname;
    }

    normalizedFilename = normalizedFilename.replace(/^\/+/, '');

    return `${getAppRoot()}/MVC/index.php?page=admin_file&file=${encodeURIComponent(normalizedFilename)}`;
}
