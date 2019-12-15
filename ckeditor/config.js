/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function(config) {
  // Define changes to default configuration here. For example:
  // config.language = 'fr';
  // config.uiColor = '#AADC6E';

  config.enterMode = CKEDITOR.ENTER_BR;
  config.shiftEnterMode = CKEDITOR.ENTER_P;
  config.filebrowserImageUploadUrl = "/Php/upload_file.php";

  config.extraPlugins = "mathjax";
  config.mathJaxLib = "/MathJax-master/MathJax.js?config=TeX-AMS_HTML-full";
};
