const easyMDE = new EasyMDE({
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
  previewRender: function(plainText, preview) {
    return easyMDE.markdown(plainText);
  }
});
