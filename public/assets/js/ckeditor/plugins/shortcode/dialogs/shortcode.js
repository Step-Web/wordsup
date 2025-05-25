CKEDITOR.dialog.add( 'shortcodeDialog', function( editor ) {

    var arr = '';
    return {

        title: 'Метка ShortCode',
        minWidth: 500,
        minHeight: 200,
        contents: [
            {
                id: 'tab-basic',
                label: 'Основная метка',
                elements: [



                    {
                        type: 'select',
                        id: 'shortcode',
                        label: 'Выбрать тип метки',
                        items: [ ['Цены','price'],['Отзывы','reviews'],['FAQ','faq'],['Фото','photos'],['Новости','news'],['Статьи','articles'],['Бренды','brands'],['Форма','blueform']],
                        'default': '',
                        onChange: function( api ) {
                            // this = CKEDITOR.ui.dialog.select
                            //alert( 'Current value: ' + this.getValue() );
                            arr =  ''
                            CKEDITOR.ajax.load('/admin/shortcode.php?tab=' + this.getValue(), function( data ) {

                                $('#showItem').html(data);
                                if (data != '') {
                                    $('#showItem').parent().parent().parent().find('input').val('').prop('disabled', false);
                                } else {
                                    $('#showItem').parent().parent().parent().find('input').val('').prop('disabled', true);
                                }


                            });



                        },
                        onLoad: function( api ) {

                            $("#showItem").on("click","p", function(event){

                               var val = '';
                                var str = '';
                                $(this).toggleClass('active');

                                $('#showItem p').each(function (i,el) {

                                    if($(el).hasClass('active')){
                                       // alert($(el).attr('id'));
                                            str += $(el).attr('id')+',';
                                       }

                                });
                               val = str.slice(0, -1);
                               $('#showItem').parent().parent().parent().find('input').val(val);
                            });

                        },
                        validate: CKEDITOR.dialog.validate.notEmpty( "Необходимо выбрать тип метки." )

                    }
                ]
            },
            {
                id: 'tab-adv',
                label: 'Атрибуты метки',
                elements: [
                    {
                        type: 'text',
                        id: 'id',
                        label: 'Id'
                    },
                    {
                        type: 'html',
                        html: '<div id="showItem"></div>'
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;
            var scode = '';
            //alert(dialog.getValueOf( 'tab-basic', 'shortcode' ));

            var basic = dialog.getValueOf('tab-basic', 'shortcode');

           var id = dialog.getValueOf( 'tab-adv', 'id' );

            if (id) {

                sc = ' [='+basic+'_'+id+'=] ';
            } else {
                sc = ' [='+basic+'=] ';
            }
            

            var scode = editor.insertHtml(sc);

        }
    };
});