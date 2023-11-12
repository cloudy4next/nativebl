import ClassicEditor from '../plugins/ckeditor5/build/ckeditor';


const ready = (callback) => {
    if (document.readyState !== "loading") callback();
    else document.addEventListener("DOMContentLoaded", callback);
};

ready(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const editorElement = document.querySelector('#editor');
    let editor;
    if (editorElement) {
        ClassicEditor
            .create(editorElement, {
                minHeight: '200px',
                ckfinder: {
                    uploadUrl: `${window.base_url}/editor-file-upload?_token=${csrfToken}`,
                }
            })
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '200px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.log(`error`, error);
            });
    }
});

// Replace oembed to Iframe
document.addEventListener('DOMContentLoaded', function() {
    // Get all oembed elements
    const oembedElements = document.querySelectorAll('oembed');

    // Loop through each oembed element
    oembedElements.forEach(function(oembedElement) {
        let videoId;
// Get the URL from the oembed element
        let videoUrl = oembedElement.getAttribute('url');

        // Convert the YouTube URL to an embed URL, if necessary
        if (videoUrl.includes('youtu.be')) {
            videoId = videoUrl.split('/').pop();
            videoUrl = 'https://www.youtube.com/embed/' + videoId;
        } else if (videoUrl.includes('watch?v=')) {
            videoId = videoUrl.split('watch?v=')[1];
            videoUrl = 'https://www.youtube.com/embed/' + videoId;
        }

        // Add any URL parameters back onto the embed URL
        const urlParams = videoUrl.split('?')[1];
        if (urlParams) {
            videoUrl += '?' + urlParams;
        }

        // Create the iframe element
        const iframeElement = document.createElement('iframe');
        iframeElement.setAttribute('src', videoUrl);
        iframeElement.setAttribute('frameborder', '0');
        // iframeElement.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
        iframeElement.setAttribute('allowfullscreen', 'true');

        // Replace the oembed element with the new iframe element
        oembedElement.parentNode.replaceChild(iframeElement, oembedElement);
    });
});
