// Quill editor setup

import Quill from "quill";

import ImageUploader from "quill-image-uploader";
import QuillImageDropAndPaste from 'quill-image-drop-and-paste'
import ImageResize from 'quill-image-resize-module--fix-imports-error'

Quill.register("modules/imageUploader", ImageUploader);
Quill.register('modules/imageDropAndPaste', QuillImageDropAndPaste)
Quill.register('modules/imageResize', ImageResize)

const quillToolbarOptions = [
    ["bold", "italic", "underline", "strike"], // toggled buttons
    ["blockquote", "code-block"],
    [{header: 1}, {header: 2}], // custom button values
    [{list: "ordered"}, {list: "bullet"}],
    [{script: "sub"}, {script: "super"}], // superscript/subscript
    [{indent: "-1"}, {indent: "+1"}], // outdent/indent
    [{direction: "rtl"}], // text direction
    [{size: ["small", false, "large", "huge"]}], // custom dropdown
    [{header: [1, 2, 3, 4, 5, 6, false]}],
    ["link", "clean", "image"],
];
document.addEventListener("DOMContentLoaded", function () {

    const quillId = document.getElementById('editor');

    if (quillId) {

        const quill = new Quill(document.getElementById('editor'), {
            modules: {
                toolbar: quillToolbarOptions,
                imageUploader: {
                    upload: (file) => {
                        return new Promise((resolve, reject) => {
                            uploadImageToServer(file, (err, res) => {
                                if (err) {
                                    alert(err.error);
                                    console.error("Error:", err);
                                    reject("Upload failed");
                                    return;
                                }
                                const imagePath = window.base_url + '/' + res.path;
                                const range = quill.getSelection();
                                quill.insertEmbed(range.index, 'image', imagePath);
                                resolve(imagePath);
                            });
                        });
                    }
                },
                imageDropAndPaste: {
                    // add a custom image handler
                    handler: imageHandler,
                },
                imageResize: {
                    displayStyles: {
                        backgroundColor: 'black',
                        border: 'none',
                        color: 'white'
                    },
                    modules: ['Resize', 'DisplaySize', 'Toolbar']
                }
            },
            theme: "snow",
        });

        // Editor Change Event
        quill.on('editor-change', function (eventName, ...args) {
            document.getElementById('editor-html').value = quill.root.innerHTML;
        });

        document.getElementById('editor-html').value = quill.root.innerHTML;
    }

    /**
     * Do something to our dropped or pasted image
     * @param.imageDataUrl {string} - image's dataURL
     * @param.type {string} - image's mime type
     * @param.imageData {ImageData} - provided more functions to handle the image
     *   - imageData.toBlob() {function} - convert image to a BLOB Object
     *   - imageData.toFile(filename?: string) {function} - convert image to a File Object. filename is optional, it will generate a random name if the original image didn't have a name.
     *   - imageData.minify(options) {function)- minify the image, return a promise
     *      - options.maxWidth {number} - specify the max width of the image, default is 800
     *      - options.maxHeight {number} - specify the max height of the image, default is 800
     *      - options. Quality {number} - specify the quality of the image, default is 0.8
     */
    function imageHandler(imageDataUrl, type, imageData) {

        const blob = imageData.toBlob()
        const file = imageData.toFile()

        // generate a form data
        const formData = new FormData()

        // append blob data
        formData.append('quill_image', blob)

        // or just append the file
        formData.append('quill_image', file)

        uploadImageToServer(file, (err, res) => {
            if (err) return;
            let index = (quill.getSelection() || {}).index || quill.getLength();
            const imagePath = window.base_url + '/' + res.path;
            quill.insertEmbed(index, 'image', imagePath);
        });
    }

    function uploadImageToServer(file, callback) {
        const formData = new FormData();
        formData.append("quill_image", file);

        fetch(window.base_url + '/editor-file-upload', {
            method: "POST",
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        })
            .then((response) => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    throw new Error('Network response was not ok.');
                }
                return response.json();
            })
            .then((result) => {
                callback(null, result);
            })
            .catch((error) => {
                callback(error);
            });
    }

});
