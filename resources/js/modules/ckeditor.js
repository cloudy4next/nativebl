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
