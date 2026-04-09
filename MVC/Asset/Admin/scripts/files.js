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

export function getFileBucketUrl(filename){
    if (filename.startsWith('http')) {
        return filename;
    }
    
    return `../../../API/files/${filename}`; 
}
