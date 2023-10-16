<!DOCTYPE html>
<html>
	<body>
		<style>
			#file-editor-left {
				position: absolute;
				height: 100%;
				width: 50%;
				top: 0;
				left: 0;
			}
			
			#separateur {
				position: absolute;
				margin-left: 10px;
				margin-right: 10px;
				height: 100%;
				width: 2px;
				text-align: center;
				border: 1px solid;
			}
			
			#file-editor-right {
				position: absolute;
				height: 100%;
				width: 50%;
				top: 0;
				right: 0;
			}
		</style>
		<div id="file-editor-left">some text</div>
		<span id="separateur"></span>
		<div id="file-editor-right">some text</div>
		<script src="js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/ace/theme-twilight.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/ace/mode-javascript.js" type="text/javascript" charset="utf-8"></script> 

		<script>
			var fileEditorLeft = ace.edit("file-editor-left");
			fileEditorLeft.setTheme("ace/theme/twilight");
			var JavaScriptMode = ace.require("ace/mode/javascript").Mode;
			fileEditorLeft.session.setMode(new JavaScriptMode());
			
			var fileEditorRight = ace.edit("file-editor-right");
			fileEditorRight.setTheme("ace/theme/twilight");
			var JavaScriptMode = ace.require("ace/mode/javascript").Mode;
			fileEditorRight.session.setMode(new JavaScriptMode());
		</script> 
	</body>
</html>