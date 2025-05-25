
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'insert' },
		{ name: 'others' },
		{ name: 'basicstyles', groups: [ 'basicstyles','btgrid','layoutmanager','shortcode','cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list','addtitle', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'links' },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'tools' },
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },

	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';


//config.extraPlugins = 'addtimestamp'; 
  //config.toolbar_Full.push(['addtimestamp']);

config.extraPlugins = 'xml,ajax,justify,btgrid,textselection,layoutmanager,basewidget,addtitle,shortcode';


	// Set the most common block elements.
config.format_tags = 'p;h1;h2;h3';
//config.format_h2 = { element: 'h2', attributes: { 'class': 'contentTitle1' } };
// Se the most common block elements.
        config.protectedSource.push(/<(style)[^>]*>.*<\/style>/ig);
        config.protectedSource.push(/<(script)[^>]*>.*<\/script>/ig);// разрешить теги <script>
        config.protectedSource.push(/<(i)[^>]*>.*<\/i>/ig);// разрешить теги <i>
        config.protectedSource.push(/<!--dev-->[\s\S]*<!--\/dev-->/g);
        config.allowedContent = true; /* all tags */
        config.basicEntities = false; // запрет преобразования символов
        config.autoParagraph = false; // Запрет оборачивание блоков
        config.protectedSource.push(/<i[^>]*><\/i>/g);
        config.protectedSource.push(/<span[^>]*><\/span>/g);
	
	// Simplify the dialog windows.
	//config.removeDialogTabs = 'image:advanced;link:advanced';
};



