CKEDITOR.plugins.add( 'shortcode', {
    icons: 'shortcode',
    init: function( editor ) {
        editor.addCommand( 'shortcode', new CKEDITOR.dialogCommand( 'shortcodeDialog' ) );
        editor.ui.addButton( 'shortcode', {
            label: 'Вставить ShortCode',
            icon:this.path+'/shortcode.png', // иконка
            command: 'shortcode',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add( 'shortcodeDialog', this.path + 'dialogs/shortcode.js' );
    }
});

