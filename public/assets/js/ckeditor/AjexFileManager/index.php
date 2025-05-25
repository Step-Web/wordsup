<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Файловый менеджер v1.0.3</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link type="text/css" href="lib/dynatree/skin/ui.dynatree.css" rel="stylesheet" />
        <link type="text/css" href="../../../css/jquery-ui-1.10.0.custom.css" rel="stylesheet" />
        <link type="text/css" href="../../../css/jquery.Jcrop.min.css" rel="stylesheet" />

        

		<script type="text/javascript" src="lib/jquery.js"></script>
		<script type="text/javascript" src="lib/jquery-ui.js"></script>
		<script type="text/javascript" src="lib/jquery.form.js"></script>
		<script type="text/javascript" src="lib/jquery.cookie.js"></script>
		<script type="text/javascript" src="lib/jquery.MultiFile.js"></script>
		<script type="text/javascript" src="lib/ajex.filemanager.js"></script>
		<script type="text/javascript" src="lib/dynatree/jquery.dynatree.js"></script>
        <script type="text/javascript" src="lib/jquery.Jcrop.min.js"></script>
        
        
	</head>
<body>




<div class="wrapper">

<div id="menu">
    <div class="padleft240">
		<div class="view">
			<span lang="view">View:</span>
			<label for="viewlist"><input type="radio" id="viewlist" name="view" value="" /> <span lang="list">List</span></label>
			<label for="viewthumb"><input type="radio" id="viewthumb" name="view" value="" checked="checked" /> <span lang="images">Images</span></label>
		</div>
		<div class="display">
			<!--span lang="display">Display:</span-->
			<label for="fileName"><input type="checkbox" id="fileName" name="fileName" value="" checked="checked" /> <span lang="fileName">File Name</span> </label>
			<label for="fileDate"><input type="checkbox" id="fileDate" name="fileDate" value="" /> <span lang="fileDate">Date</span> </label>
			<label for="fileSize"><input type="checkbox" id="fileSize" name="fileSize" value="" /> <span lang="fileSize">Size</span></label>
		</div>
		<div class="sort">
			<span lang="sort">Sort:</span>
			<label for="sortName"><input type="radio" id="sortName" name="sort" value="" checked="checked" /> <span lang="sortName">Name</span> </label>
			<label for="sortDate"><input type="radio" id="sortDate" name="sort" value="" /> <span lang="sortDate">Date</span> </label>
			<label for="sortSize"><input type="radio" id="sortSize" name="sort" value="" /> <span lang="sortSize">Size</span></label>
            <label for="sortExt"><input type="radio" id="sortExt" name="sort" value="" checked="checked" /> <span lang="sortExt">Type</span> </label>
		</div>
        </div>
	</div>

	<div class="middle">

		<div class="container">
			<div class="content">
            <div id="files">
	
	<div id="fileList">
		<table width="100%">
			<thead>
				<tr>
					<td><input type="checkbox" id="checkAll" name="checkAll" value="1" /></td>
					<td><span lang="fileName">File Name</span></td>
					<td><span lang="fileDate">Date</span></td>
					<td><span lang="fileSize">Size</span></td>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
	</div>
	<div id="fileThumb"></div>
</div>
            
            </div>
		</div><!-- .container-->

		<div class="left-sidebar">
			
            
            <div id="dirs">

     
	
	
	<div id="dirsList"></div>
	<div class="dirsMenu">
		
		<div class="folderMenu"></div>
	</div>
	<form id="filesUploadForm" action="" enctype="multipart/form-data">
	<input type="hidden" name="dir" value="" />
	<input type="hidden" name="type" value="file" />
	<div id="uploadList">
		<div class="selectLang"><span lang="chooseFileUpload">Choose file</span></div>
		<div class="selectFile"><div><input type="file" name="uploadFiles[]" class="multi" /></div></div>
	</div>
    
	<div class="resizeGraph">
		<span lang="resizeGraph" class="textresize">Resize graphics files to</span>:<br />
		<span lang="resizewidth" class="txtBlock">Width</span><input type="text" id="resizeWidth" name="resizeWidth" value="800"> 
		<span lang="resizeheight" class="txtBlock">Height</span><input type="text" id="resizeHeight" name="resizeHeight">
	</div>
    <div id="uploadmyfiles"></div>
	</form>
</div>
            
            
		</div><!-- .left-sidebar -->

	</div><!-- .middle-->

</div><!-- .wrapper -->


<div id="dialog" title=""><div class="c"></div></div>
<div id="dialogImg" title=""></div>
<div id="dialogCrop" title="">
<div id="boximg"></div>
    Соблюдать пропорции: <select id="ratioCrop" onchange="ratioCrop()">
                         <option value="0" selected="selected">нет</option>
                         <option value="1:1">1x1</option>
                         <option value="4:3">4x3</option>
                         <option value="3:4">3x4</option>
                         </select>
    <input type="hidden" size="4" id="x1" name="x1" />
    <input type="hidden" size="4" id="x2" name="x2" />
    <input type="hidden" size="4" id="y1" name="y1" />
    <input type="hidden" size="4" id="y2" name="y2" />
    <input type="hidden" size="4" id="w" name="w" />
    <input type="hidden" size="4" id="h" name="h" />
    <div class="wh"><span lang="width">Width</span>  <input type="text" size="4" id="cw" name="cw" value="0" /> px<br />
    <span lang="height">Height</span>  <input type="text" size="4" id="ch" name="ch" value="0" />px</div>
  </div>


<div id="status"><div class="l"></div><div id="loading"></div></div></div>

 
  

<div id="dowloaded"></div>



</body>
</html>