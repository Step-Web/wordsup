/**
 * Ajex.FileManager
 * http://demphest.ru/ajex-filemanager
 *
 * @version
 * 1.0.3 (24 May 2010)
 *
 * @copyright
 * Copyright (C) 2009-2010 Demphest Gorphek
 *
 * @license
 * Dual licensed under the MIT and GPL licenses.
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Ajex.FileManager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This file is part of Ajex.FileManager.
 */

var $cfg = {
	display:	{fileName: true, fileDate: false, fileSize: false},
	view:		{list: false, thumb: true},
	menu:	{file: {}, folder: {}},
	contextmenu: true,
	cutKey: 15,
	dir: '',
	file: '',
	thumb: '',
	skin: 'light',
	lang:	'ru',
	type:	'file',
	sort: 'name',
	returnTo: 'ckeditor',
	tmp: []
};
if ('' != (isSkin = parseUrl('skin'))) { $cfg.skin = isSkin; }
if ('' != (isType = parseUrl('type'))) { $cfg.type = isType; }
if ('' != (isReturn = parseUrl('returnTo'))) { $cfg.returnTo = isReturn; }
if ('' != (isCMenu = parseUrl('contextmenu'))) { $cfg.contextmenu = 'true' == isCMenu? true : false; }
if ('' != (isLang = parseUrl('langCode'))) { $cfg.lang = isLang; }
if ('' != (isLang = parseUrl('lang'))) { $cfg.lang = isLang; }

var $ajaxConnector = 'ajax/php/ajax.php';
if ('' != (isConnector = parseUrl('connector'))) {
	switch(isConnector) {
		case '###':
			break;
		case 'php':
		default:
			//$ajaxConnector = 'ajax/php/ajax.php';
	}
}

$('head').prepend('<script type="text/javascript" src="lang/' + $cfg.lang + '.js"></script>');
$('head').append('<link type="text/css" href="skin/' + $cfg.skin + '/' + $cfg.skin + '.css" rel="stylesheet" />');

if ($cfg.contextmenu) {
	$('head').append('<script type="text/javascript" src="lib/jquery.contextmenu.js"></script>');
}

var menuDiv	= {};
var statusDiv	= {};
var dialogDiv	= {};

var Action = {
	createFolder: function() {
		if ('root' == $cfg.dir || '' == $cfg.dir) {return false;}

		$cfg.tmp['mode'] = 'createFolder';
		$cfg.tmp['oldname'] = $cfg.dir;
		$cfg.tmp['key'] = $cfg.dir;

		dialogSet($lang.enterNameCreateFolder, '<b>' + $lang.location + '</b> [' + $cfg.url + $cfg.dir + '/]<br /><input type="text" id="newName" value="" class="t" /><br />' + $lang.allowRegSymbol);
		return;
	},
	renameFolder: function() {
		var folders = $cfg.dir.split('/');
		if (1 == folders.length) return;

		var folder = folders[folders.length - 1];
		var key = $cfg.dir.substring(0, $cfg.dir.lastIndexOf('/'));

		$cfg.tmp['mode'] = 'renameFolder';
		$cfg.tmp['oldname'] = $cfg.dir;
		$cfg.tmp['key'] = key;

		dialogSet($lang.enterNewNameFolder, '<b>' + folder + '</b> [' + $cfg.url + key + '/]<br /><input type="text" id="newName" value="" class="t" /><br />' + $lang.allowRegSymbol);
		return;
	},
	deleteFolder: function() {
		if ('root' == $cfg.dir || '' == $cfg.dir) {return false;}

		$('#dirsList').dynatree('disable');
		$.post($ajaxConnector + '?mode=deleteFolder', {
					dir:	$cfg.dir,
					type:	$cfg.type,
					lang:	$cfg.lang
				},
				function(reply) {
					$('#dirsList').dynatree('enable');
					if (reply.isDelete) {
						var key = $cfg.dir.substring(0, $cfg.dir.lastIndexOf('/'));
						var tree = $('#dirsList').dynatree('getTree');
						var node = tree.getNodeByKey(encodeURIComponent(key));
						node.reload(true);

						$('>div.l', statusDiv).fadeIn('fast').html('<div class="successUpload"><b>' + $lang.successDeleteFolder + '</b></div>').delay(1500).fadeOut('fast');
					} else {
						$('>div.l', statusDiv).fadeIn('fast').html('<div class="warning"><b>' + $lang.failedDeleteFolder + '</b></div>').delay(1500).fadeOut('fast');
					}
			}, 'json');
		return;
	},
	uploadFolder: function() {
		if ('' == $('input:file').val()) {
			$('>div.l', statusDiv).fadeIn('fast').html('<div class="warning"><b>' + $lang.chooseDownloads + '</b></div>').delay(1500).fadeOut('fast');
			return;
		}

		var downLoaded = $('#dowloaded');
		downLoaded.html('<div class="isDownload">' + $lang.resultUpload + '</div>').fadeIn(1000);
		$('#filesUploadForm').ajaxSubmit({
				url:	$ajaxConnector + '?mode=uploads' ,
				type:	'post',
				dataType: 'json',
				beforeSubmit: function() {
					var f = $('#filesUploadForm');
					$('input[name="dir"]', f).val($cfg.dir);
					$('input[name="type"]', f).val($cfg.type);
					return true;
				},
				//success : function showResponse(data) {
				success : function showResponse(response, status) {
					bindFileContextMenu();
					bindFolderContextMenu();
					
					//alert(response.downloaded);
					$('input:file').MultiFile('reset');
					var loaded = '';
					if (response.downloaded.length) {
						$('>div.l', statusDiv).fadeIn('fast').html('<div class="successUpload"><b>' + $lang.successUpload + '</b></div>').delay(1500).fadeOut('fast');
						
						for (var i=-1; ++i<response.downloaded.length;) {
							if (response.downloaded[i][0]) {
								loaded += '<div><span class="ok">ok</span> ' + response.downloaded[i][1] + '</div>';
							} else {
								loaded += '<div><span class="no">no</span> ' + response.downloaded[i][1] + '</div>';
							}
						}
						viewsUpdate($cfg.dir);
						downLoaded.append(loaded);
						setTimeout("$('#dowloaded').fadeOut(2000);", 2000);
					} else {
						downLoaded.fadeOut(1);
					}
				}
			});
		return;
	},

	deleteFiles: function() {
		var files = [];
		$('#fileThumb input[name="file\\[\\]"]:checked').each(function() {
				files.push(this.value);
			});
		if (!files.length) {
			return;
		}

		$.post($ajaxConnector + '?mode=deleteFiles', {
			dir:	$cfg.dir,
			files:	files.join('::'),
			type:	$cfg.type,
			lang:	$cfg.lang
		}, function(reply) {
			appendFiles(reply);
		}, 'json');
	},
	deleteFile: function() {
		if ('' == $cfg.file) return false;
		$.post($ajaxConnector + '?mode=deleteFiles', {
			dir:	$cfg.dir,
			files:	$cfg.file,
			type:	$cfg.type,
			lang:	$cfg.lang
		}, function(reply) {
			appendFiles(reply);
		}, 'json');
	},
	renameFile: function() {
		if ('' == $cfg.file) return false;
		$cfg.tmp['mode'] = 'renameFile';
		$cfg.tmp['oldname'] = $cfg.file;
		$cfg.tmp['key'] = '';
		dialogSet($lang.enterNewNameFile, '<br /><input type="text" id="newName" value="' + $cfg.file + '" class="t" /><br />' + $lang.allowRegSymbol);
	},
	downloadFile: function() {
		if ('' == $cfg.file) return false;
		location.replace($ajaxConnector + '?downloadFile=' + $cfg.dir + '/' + $cfg.file);
	},
	lookFile: function() {
		if ('' == $cfg.file) return false;
		//window.open($cfg.url + $cfg.dir +'/'+ $cfg.file, 'preView', '');
		var ext = $cfg.file.split(".").pop();
		//console.log($cfg);
		if(ext == 'jpg' || ext == 'jpeg' || ext == 'gif' || ext == 'png'){
		dialogImg($cfg.file,$cfg.url + $cfg.dir);
		} else {
			window.open($cfg.url + $cfg.dir +'/'+ $cfg.file, 'preView', '');
		}
		
		
	},
	editImage: function() {
		if ('' == $cfg.file) return false;
		//window.open($cfg.url + $cfg.dir +'/'+ $cfg.file, 'preView', '');
		var ext = $cfg.file.split(".").pop();
		//console.log($cfg);
		if(ext == 'jpg' || ext == 'jpeg' || ext == 'gif' || ext == 'png'){
		dialogCrop($cfg.file,$cfg.url + $cfg.dir);
		}
		
	},
	setThumb: function() {
		if ('' == $cfg.file) return false;
		_setReturnData($cfg.url + '_thumb/'+$cfg.dir +'/'+ $cfg.file);
	},
	setFile: function() {
		if ('' == $cfg.file) return false;
		_setReturnData($cfg.url + $cfg.dir +'/'+ $cfg.file);
	}

};


$(document).ready(function() {

	$('#loading').bind('ajaxSend', function() {
		$(this).show();
	}).bind('ajaxComplete', function() {
		$(this).hide();
	});

	$.post($ajaxConnector + '?mode=cfg', {
			type:	$cfg.type,
			lang: $cfg.lang
	},
	function(reply) {
		for (var i in reply.config) {
			$cfg[i] = reply.config[i];
		}

		$('#dirsList').dynatree({
			title: 'upload',
			rootVisible: true,
			persist: true,
			clickFolderMode: 1,
			fx: {height: "toggle", duration: 200},
			children: $cfg.children,
			onActivate: function(dtnode) {
				$cfg.file = '';
				$cfg.dir = decodeURIComponent(dtnode.data.key);
				viewsUpdate(dtnode.data.key);
				return;
			},
			onLazyRead: function(dtnode) {
				$.post($ajaxConnector + '?mode=getDirs', {
						dir:	dtnode.data.key,
						type:	$cfg.type,
						lang:	$cfg.lang
					},
					function(reply) {
						dtnode.addChild(reply.dirs);
						dtnode.setLazyNodeStatus(DTNodeStatus_Ok);
						$cfg.contextmenu? bindFolderContextMenu() : null;
					}
				, 'json');

				return false;
			}
		});
		
		if ('' != (tmp = $.cookie('dynatree-active'))) {
			$cfg.file = '';
			$cfg.dir = decodeURIComponent(tmp);
			viewsUpdate(tmp);
		}
		
		//alert($cfg.allow);

		$cfg.contextmenu? bindFolderContextMenu() : null;
		$('.multi').MultiFile({
			max: 16,
			accept: $cfg.allow,
			list: '#uploadList',
			STRING: {
				remove:		$lang.removeFile,
				selected:	$lang.selected,
				denied:		$lang.deniedExt,
				duplicate:	$lang.duplicate
			}
		});

		if ('' != $cfg.maxUpload) {
			$('span[lang="chooseFileUpload"]', $('#uploadList')).append(' <span>'+$lang.maxi+' ' + $cfg.maxUpload + '</span>');
		}

		$('head').append('<style type="text/css">#fileThumb .thumb img {width: '+$cfg.thumbWidth+'px;height:'+$cfg.thumbHeight+'px;} #fileThumb .name {width: '+$cfg.thumbWidth+'px;}</style>');

	}, 'json');


	

	menuDiv		= $('#menu');
	statusDiv	= $('#status');
	dialogDiv		= $('#dialog');

	for (var i in $lang) {
		$('span[lang="' + i + '"]').text($lang[i]);
	}

	$('.view label[for="viewlist"], .view label[for="viewthumb"]', menuDiv).click(function() {
		if ($('#viewlist').attr('checked')) {
			$('#fileThumb').hide();
			$('#fileList').show();
			$cfg.view.list = true;
			$cfg.view.thumb = false;
		} else {
			$('#fileList').hide();
			$('#fileThumb').show();
			$cfg.view.list = false;
			$cfg.view.thumb = true;
		}
		return;
	});

	$('.display label', menuDiv).click(function() {
		var attrId = $(this).attr('for');
		var attrDiv = attrId.substring(4).toLowerCase();

		if ($('#' + attrId).attr('checked')) {
			$('#fileThumb .' + attrDiv).show();
			$cfg.display[attrId] = true;
		} else {
			$('#fileThumb .' + attrDiv).hide();
			$cfg.display[attrId] = false;
		}
	});

	$('#checkAll').click(function() {
		if ($(this).attr('checked')) {
			$('#fileList tbody input[name="file\\[\\]"], #fileThumb input[name="file\\[\\]"]').attr('checked', 'checked');
		} else {
			$('#fileList tbody input[name="file\\[\\]"], #fileThumb input[name="file\\[\\]"]').removeAttr('checked');
		}
		return;
	});

	$('.sort label', menuDiv).click(function() {
		var attrId = $(this).attr('for');
		$cfg.sort = attrId.substring(4).toLowerCase();
		viewsUpdate($cfg.dir);
	});

	$('.dirsMenu .folderMenu', $('#dirs')).html('\
			<a href="#" onclick="Action.deleteFolder()" class="deleteFolder" title="' + $lang.deleteFolder + '"></a>\
			<a href="#" onclick="Action.renameFolder()" class="renameFolder" title="' + $lang.renameFolder + '"></a>\
			<a href="#" onclick="Action.createFolder()" class="createFolder" title="' + $lang.createFolder + '"></a>\
	');
	$('#uploadmyfiles').html('<a href="#" onclick="Action.uploadFolder()" class="uploadFolder" title="' + $lang.uploadSelectFiles + '">' + $lang.uploadSelectFiles + '</a>');

	if ($cfg.contextmenu) {
		$cfg.menu.file = [
			{
				'<span lang="select">Select</span>' : {
					onclick : function(menuItem, menu) {return Action.setFile();},
					icon : 'skin/_ico/arrow_rotate_anticlockwise.png'
				}
			},
			{
				'<span lang="selectThumb">Select this thumbnail</span>' : {
					onclick : function(menuItem, menu) { return Action.setThumb();
						},
					disabled : false,
					icon : 'skin/_ico/arrow_out.png'
				}
			},
			$.contextMenu.separator,
			{
				'<span lang="lookAt">Look</span>' : {
					onclick : function(menuItem, menu) {return Action.lookFile();},
					icon : 'skin/_ico/eye.png'
				}
			},
			
			{
				'<span lang="editImage">Edit Image</span>' : {
					onclick : function(menuItem, menu) {return Action.editImage();},
					icon : 'skin/_ico/imagecrop.png'
				}
			},
			
			{
				'<span lang="downloadFile">Download file</span>' : {
					onclick : function(menuItem, menu) {return Action.downloadFile();},
					icon : 'skin/_ico/download.png'
				}
			},
			{
				'<span lang="renameFile">Rename file</span>' : {
					onclick: function(menuItem, menu) {return Action.renameFile();},
					icon: 'skin/_ico/rename.png'
				}
			},
			$.contextMenu.separator,
			{
				'<span lang="deleteFile">Delete file</span>' : {
					onclick: function(menuItem, menu) {return Action.deleteFile();},
					icon: 'skin/_ico/cross.png'
				}
			},
			{
				'<span lang="deleteCheckedFile">Delete checked files</span>' : {
					onclick: function(menuItem, menu) {return Action.deleteFiles();},
					icon: 'skin/_ico/delete.png'
				}
			}
		];

		$cfg.menu.folder = [
			{
				'<span lang="createFolder">Create folder</span>' : {
					onclick: function(menuItem, menu) {return Action.createFolder();},
					icon: 'skin/_ico/folder_add.png'
				}
			},
			{
				'<span lang="renameFolder">Rename folder</span>' : {
					onclick: function(menuItem, menu) {return Action.renameFolder();},
					icon: 'skin/_ico/application_xp_terminal.png'
				}
			},
			{
				'<span lang="deleteFolder">Delete folder</span>' : {
					onclick: function(menuItem, menu) {return Action.deleteFolder();},
					icon: 'skin/_ico/folder_delete.png'
				}
			},
			$.contextMenu.separator,
			{
				'<span lang="uploadSelectFiles">Upload selected files</span>' : {
					onclick : function(menuItem, menu) {return Action.uploadFolder();},
					icon : 'skin/_ico/arrow_up.png'
				}
			}
		];
	}

	$('#dialogImg').dialog({
		bgiframe: true,
		maxHeight: 400,
		width: 'auto',
		height: 'auto',
		position: 'top',
        modal: true,
		autoOpen: false
	});
	
	
	$('#dialogCrop').dialog({
		bgiframe: true,
		maxHeight: 400,
		width: 'auto',
		height: 'auto',
		position: 'top',
        modal: true,
		autoOpen: false,
		  buttons: {
       'Crop': function() {
          cropImg();
        }
		  }
	});    
	
	
		$(dialogDiv).dialog({
		bgiframe: true,
		resizable: false,
		width: 'auto',
		height: 'auto',
		modal: true,
		autoOpen: false,
		buttons: {
			'Cancel': function() {
				$(this).dialog('close');
			},
			' OK ': function() {
				var newname = $('#newName').val();
				if (!/^[a-z0-9-_#~\$%()\[\]&=]+/i.test(newname)) {
					return false;
				}
				$(this).dialog('close');
				$('#dialog input').attr('disabled', 'disabled');

				$.post($ajaxConnector + '?mode=' + $cfg.tmp['mode'], {
						dir:	$cfg.dir,
						type:	$cfg.type,
						lang:	$cfg.lang,
						oldname:	$cfg.tmp['oldname'],
						newname:	newname
					},
					function(reply) {
						if (reply.isSuccess && ('createFolder' == $cfg.tmp['mode'] || 'renameFolder' == $cfg.tmp['mode'])) {
							if ('exist' == reply.isSuccess) {
								$('>div.l', statusDiv).fadeIn('fast').html('<div class="warning"><b>' + $lang.folderExist + '</b></div>').delay(1500).fadeOut('fast');
								return;
							}
							var tree = $('#dirsList').dynatree('getTree');
							var node = tree.getNodeByKey(encodeURIComponent($cfg.tmp['key']));
							node.reload(true);
						} else {
							appendFiles(reply);
						}
					}
				, 'json');

				return;
			}
		}
	});
	

	
});



function showCoords(c)
  {     x1 = c.x;  $('#x1').val(c.x); 
        y1 = c.y;  $('#y1').val(c.y); 
        x2 = c.x2;  $('#x2').val(c.x2); 
        y2 = c.y2;  $('#y2').val(c.y2); 
        $('#w').val(c.w);
        $('#h').val(c.h); 
		$('#cw').val(c.w.toFixed(0)); 
		$('#ch').val(c.h.toFixed(0));
		if(c.w > 0) {
			$('#dialogCrop').parent('.ui-dialog').find('button').eq(0).removeClass('ui-button-primary').addClass('ui-button-success');  
		} else { 
		   jcrop_api.setSelect([ 10, 10, 50, 50 ]);
		}
          if (parseInt(jQuery('#w').val())>0) return true;
	
		  
    
  };

function ratioCrop() {
	var r = String($('#ratioCrop').val());
	r = r.split(':');
    jcrop_api.setOptions({ aspectRatio: r[0]/r[1]});
    jcrop_api.focus();
    };


function dialogCrop(img,dir)
{       
	//?'+ + Math.random()+
	$('#dialogCrop #boximg').html('<img src="'+dir+'/'+img+'?'+ Math.random()+'" id="cropbox">');
	$('#dialogCrop').dialog('open');
	 $('#cropbox').Jcrop({ 
	    setSelect:   [ 10, 10, 50, 50 ],
        onChange: showCoords,
        onSelect: showCoords
    },function(){ 
        jcrop_api = this; 
    });
	 $('#dialogCrop').parent('.ui-dialog').find('button').eq(0).text($lang.cropDialog).removeClass('ui-state-default').addClass('ui-button-success');
	  $("#ratioCrop [value='0']").attr("selected", "selected");
	 
	
}


function dialogImg(img,dir)
{     
	$('div.ui-dialog span.ui-dialog-title').html(img);
	$('#dialogImg').html('<img src="'+dir+'/'+img+'">');
	$('#dialogImg').dialog('open');
}








function cropImg() {
	var img = $('#cropbox').attr('src');
    var x1 = $('#x1').val(); 
    var x2 = $('#x2').val(); 
    var y1 = $('#y1').val(); 
	var y2 = $('#y2').val(); 
	var w = $('#w').val(); 
	var h = $('#h').val();
	var cw = $('#cw').val(); 
	var ch = $('#ch').val();
	if(ch > 0 && cw > 0) {
	$.post($ajaxConnector + '?mode=cropImage', {
			img: img,
			x1: x1,
			x2: x2,
			y1: y1,
			y2: y2,
			w: w,
			h: h,
			cw: cw,
			ch: ch,
			dir:	$cfg.dir,
			files:	$cfg.file,
			type:	$cfg.type,
			lang:	$cfg.lang
		}, function(reply) {
			$('>div.l', statusDiv).fadeIn('fast').html('<div class="successUpload"><b>' + $lang.successUpload + '</b></div>').delay(1500).fadeOut('fast');
			window.location.reload();	
		}, 'json');
	} else {
		$('>div.l', statusDiv).fadeIn('fast').html('<div class="warning"><b>' + $lang.selectCrop + '</b></div>').delay(1500).fadeOut('fast');
	}

}





function dialogSet(title, html)
{
	$('div.ui-dialog span.ui-dialog-title').html(title);
	$(dialogDiv).html(html);
	$(dialogDiv).dialog('open');
	$('#newName').focus();
	return;
}

 
function bindFolderContextMenu()
{
	return $('.ui-dynatree-document, .ui-dynatree-folder').not('#ui-dynatree-id-root').contextMenu($cfg.menu.folder, {
		theme: $cfg.skin,
		beforeShow: function() {
			$cfg.dir = decodeURIComponent($(this.target).attr('id'));
			$cfg.dir = $cfg.dir.substr($cfg.cutKey);

			/*if ('' == $('input[name="uploadFiles\\[\\]"]').val()) {
				$(this.menu).find('.context-menu-item').eq(4).addClass('context-menu-item-disabled');
			} else {
				$(this.menu).find('.context-menu-item').eq(4).removeClass('context-menu-item-disabled');
			}*/

			for (var i in $lang) {$('span[lang="' + i + '"]', $(this.menu)).text($lang[i]);}/*		TODO: remake		*/
		}
	});
}

function bindFileContextMenu()
{
	return $('#fileThumb .thumb, #fileList .name').contextMenu($cfg.menu.file, {
		theme: $cfg.skin,
		beforeShow: function() {
			$cfg.file = $cfg.view.thumb? $('.name', this.target).text() : $('a', this.target).parent().text();
			if ('' == $(this.target).attr('thumb')) {
				$(this.menu).find('.context-menu-item').eq(1).addClass('context-menu-item-disabled');
			} else {
				$(this.menu).find('.context-menu-item').eq(1).removeClass('context-menu-item-disabled');
			}
			
			if($(this.target).data('ext') == 'jpg'){
				$(this.menu).find('.context-menu-item').eq(3).removeClass('context-menu-item-disabled');
			} else {
				$(this.menu).find('.context-menu-item').eq(3).addClass('context-menu-item-disabled');
			}
			

			for (var i in $lang) {
				//alert(i);
				//
				$('span[lang="' + i + '"]', $(this.menu)).text($lang[i]);}/*		TODO: remake		*/
		}
	});
}

function _setReturnData(input, data)
{
	switch($cfg.returnTo) {
		case 'ckeditor':
			window.top.opener['CKEDITOR'].tools.callFunction(parseUrl('CKEditorFuncNum'), input, data);
			window.top.close();
			window.top.opener.focus();
			break;

		case 'tinymce':
			var win = window.dialogArguments || opener || parent || top;
			tinyMCE = win.tinyMCE;
			var params = tinyMCE.activeEditor.windowManager.params;
			params.window.document.getElementById(params.input).value = input;
			try {
				params.window.ImageDialog.showPreviewImage(input);
			} catch(e) {}
			window.close();
			break;

		default:
			try {
				if ('$' == $cfg.returnTo.substr(0, 1)) {
					var objInput = $cfg.returnTo.substr(1);
					window.top.opener.document.getElementById(objInput).value = input;
				} else {
					window.top.opener[$cfg.returnTo](input);
				}
				window.close();
			} catch(e) {
				
				alert('Function is not available or does not exist: ' + $cfg.returnTo + "\r" + e.message);
			}
	}

	return true;
}

function viewsUpdate(dir)
{
	if ('root' == dir)
		return;

	$.post($ajaxConnector + '?mode=getFiles', {
			dir:	dir,
			type:	$cfg.type,
			lang:	$cfg.lang,
			sort:	$cfg.sort
		},
		function(reply) {
			
			appendFiles(reply);
		}
	, 'json');

	return;
}

function appendFiles(reply)
{
	$('>div.l', statusDiv).html('<div>' + $cfg.url + $cfg.dir + '/</div><div><b>' + reply.files.length + '</b> ' + $lang.fileOf + '</div>');

	var files = reply.files;
	var list = '', thumb = '', w_h = '', attr = '';

	for (var i in files) {
		attr = 'file="' + ($cfg.url + $cfg.dir + '/' + files[i].name) + '" thumb="' + (files[i].width? ($cfg.url + $cfg.thumb + '/' + $cfg.dir + '/' + files[i].name) : '') + '"';
		thumb += '<div class="thumb ext_' + files[i].ext + '" ' + attr + ' data-ext="' + files[i].ext + '"><div class="image">';

		if (files[i].width) {
			w_h = '(' + files[i].width + ' x ' + files[i].height + ') ';
			thumb += '<img id="img'+files[i].name+'" src="' + files[i].thumb + '" alt="" />';
		} else {
			w_h = '';
			thumb += '<img src="skin/.gif" alt="" />';
		}

		thumb += '</div><div class="check"><input type="checkbox" name="file[]" value="' + files[i].name + '" /></div>';
		thumb += '<div class="name" ' + ($cfg.display.fileName? 'style="display:block;"' : 'style="display:none"') + '>' + files[i].name + '</div>';
		thumb += '<div class="date" ' + ($cfg.display.fileDate? 'style="display:block;"' : 'style="display:none"') + '>' + files[i].date + '</div>';
		thumb += '<div class="size" ' + ($cfg.display.fileSize? 'style="display:block;"' : 'style="display:none"') + '>' + w_h + files[i].size + '</div>';
		thumb += '</div>';

		list += '<tr>';
		list += '<td><input type="checkbox" name="file[]" value="' + files[i].name + '" /></td>';
		list += '<td><div class="name"' + attr + '><a href="">' + files[i].name + '</a></div></td>';
		list += '<td><div class="date">' + files[i].date + '</div></td>';
		list += '<td><div class="size">' + w_h + files[i].size + '</div></td>';
		list += '</tr>';
	}

	
	
	
	$('#fileThumb').html(thumb);
	$('#fileList > table > tbody').html(list);
	$('#fileThumb > div.thumb').each(function() {

		var div = $(this);
		div.click(function() {
			//$('#fileThumb > div').removeClass('thumbClick');
			$cfg.file = $('.name', div).text();
			$cfg.thumb = $(div).attr('thumb');
			$('>div.l', statusDiv).html('<div class="cutName"><a href="' + $cfg.url + $cfg.dir + '/' + $cfg.file + '" target="_urlFile">' + $cfg.url + $cfg.dir + '/' + $cfg.file + '</a></div><div>'+$lang.fileSize+': '+$('.size', div).text()+'</div><div>'+$lang.fileDate+': '+$('.date', div).text()+'</div>');
			//div.addClass('thumbClick');
                  
		//if($(div +' input').attr("checked")){
	  if($(div).find('input').attr('checked') == true){
		  div.addClass('thumbClick');
		  
	  } else {
		  div.removeClass('thumbClick')
		  
	  }
 // }else{
	//  alert('чекбокс выключен');
  // делаем что-то другое, когда чекбокс выключен
 // }
			

		}).dblclick(function() {
			_setReturnData($cfg.url + $cfg.dir + '/' + $('.name', div).text());
		});

		$('#fileList .name a').dblclick(function () {
			_setReturnData($cfg.url + $cfg.dir + '/' + $(this).text());
			return false;
		}).click(function() {
			$cfg.file = $(this).text();
			$('>div.l', statusDiv).html('<div class="cutName"><a href="' + $cfg.url + $cfg.dir + '/' + $cfg.file + '" target="_urlFile">' + $cfg.url + $cfg.dir + '/' + $cfg.file + '</a></div><div>'+$lang.fileSize+': '+$('.size', div).text()+'</div><div>'+$lang.fileDate+': '+$('.date', div).text()+'</div>');
			return false;
		});

	});


	$('#fileList input[name="file\\[\\]"]').click(function () {
		$(this).attr('checked')? $('#fileThumb input[value="' + $(this).attr('value') + '"]').attr('checked', 'checked') : $('#fileThumb input[value="' + $(this).attr('value') + '"]').removeAttr('checked');
	});
	$('#fileThumb input[name="file\\[\\]"]').click(function () {
		$(this).attr('checked')? 	$('#fileList input[value="' + $(this).attr('value') + '"]').attr('checked', 'checked') : $('#fileList input[value="' + $(this).attr('value') + '"]').removeAttr('checked');
	});

	$cfg.contextmenu? bindFileContextMenu() : null;
	return;
}







/*
 * -----
 * misc
 *
 * */

function parseUrl(name)
{
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if (null == results) {
		return '';
	}
	return results[1];
}

var cssFix = function()
{
	var u = navigator.userAgent.toLowerCase(),
	is = function(t) {
		return (u.indexOf(t) != -1);
	};
	$("html").addClass([(!(/opera|webtv/i.test(u)) && /msie (\d)/.test(u)) ? ('ie ie' + RegExp.$1)
		: is('firefox/2') ? 'gecko ff2'	: is('firefox/3') ? 'gecko ff3'	: is('gecko/') ? 'gecko'
		: is('chrome/') ? 'chrome'
		: is('opera/9') ? 'opera opera9'	: /opera (\d)/.test(u) ? 'opera opera' + RegExp.$1
		: is('konqueror') ? 'konqueror'
		: is('applewebkit/') ? 'webkit safari'
		: is('mozilla/') ? 'gecko'
		: '',
		(is('x11') || is('linux')) ? ' linux' : is('mac') ? ' mac' : is('win') ? ' win'
	: ''].join(''));
}();

