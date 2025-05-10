const simplemde = new SimpleMDE({
  element: document.getElementById("editor"),
  spellChecker: true,
  toolbar: [
    "bold", "italic", "strikethrough", "heading", "|", 
    "quote", "code", "|", 
    "unordered-list", "ordered-list", "clean-block", "|",
    "link", "image", "table", "|", 
    "preview"
  ],
  status: false,
  previewRender: function(plainText) {
    return SimpleMDE.prototype.markdown(plainText);
  }
});