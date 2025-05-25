CKEDITOR.plugins.add('addtitle',{
  init: function(editor){
	 
	
	 
	  var cmd = editor.addCommand('addtitle', {
      exec:function(editor){
		
		 var divtitle = editor.getSelection().getStartElement().getParent('div').getAttribute("class");
		  var sel = editor.getSelection().getSelectedText();
		 
		 
		 if(sel.length){
			if(divtitle == 'title') {
                editor.insertHtml(sel); 
		     } else {
				editor.insertHtml('<div class="title"><p>'+sel+'</p></div>'); 
			 }
		} else { 
		alert('Выделите текст  которому нужно задать стиль заголовка');
		}
                 
			 
      }
    });
    cmd.modes = { wysiwyg : 1 };
    editor.ui.addButton('addtitle',{
      label: 'Создать заголовок',
      icon:this.path+'/addtitle.png', // иконка
      command: 'addtitle'
    });
  }
});