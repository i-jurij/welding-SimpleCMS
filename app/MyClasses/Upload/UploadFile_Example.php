<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Upload</title>
  <meta name="description" content="Class Upload file">
  <META NAME="keywords" CONTENT="Class Upload file">
  <meta HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
  <meta HTTP-EQUIV="Content-language" CONTENT="ru-RU">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <meta name="author" content="i-jurij" >
</head>
  <body style="width:100%;">
<?php
define('APPROOT', __DIR__);
// Loading Libraries
/*
spl_autoload_register(function($className){
    if (file_exists(APPROOT.DIRECTORY_SEPARATOR.'app/'.mb_strtolower($className).'.php')) {
        require_once APPROOT.DIRECTORY_SEPARATOR.'app/'.mb_strtolower($className).'.php';
    }
    elseif (file_exists(APPROOT.DIRECTORY_SEPARATOR.'app/traits/'.mb_strtolower($className).'.php')) {
        require_once APPROOT.DIRECTORY_SEPARATOR.'app/traits/'.mb_strtolower($className).'.php';
    }
});
*/
spl_autoload_extensions('.php'); // comma-separated list
spl_autoload_register();
// class declaration
$load = new \App\MyClasses\Upload\UploadFile();
// if not empty data from $_FILES
if ($load->issetData()) {
    ?>
	<p><a href="javascript:history.back()" >Back</a></p>
<?php
        foreach ($load->files as $input => $input_array) {
            // print_r($input_array); print '<br />';
            echo 'Input "'.$input.'":<br />';

            foreach ($input_array as $key => $file) {
                if (!empty($file['name'])) {
                    if (mb_strlen($file['name'], 'UTF-8') < 101) {
                        $name = $file['name'];
                    } else {
                        $name = mb_strimwidth($file['name'], 0, 48, '...').mb_substr($file['name'], -48, null, 'UTF-8');
                    }
                    echo 'Name "'.$name.'":<br />';
                }
            // SET the vars for class
                $load->create_dir = true; // let create dest dir if not exists
                if ($input === 'file') {
                    $load->dest_dir = 'upload_files'; // where upload file will be saved
                    $load->tmp_dir = ''; // temporary dir for upload file before processing
                    $load->dir_permissions = 0777; // permissions for dest dir
                    $load->file_size = 3 * 100 * 1024; // 300KB - size for upload files = MAX_FILE_SIZE from html
                    $load->file_permissions = 0666; // permissions for the file being created
                    $load->file_mimetype = ['text/php', 'text/x-php', 'text/html']; // allowed mime-types for upload file
                    $load->file_ext = ['.php', 'html']; // allowed extension for upload file
                    $load->new_file_name = ''; // new name of upload file
                    $load->replace_old_file = false; // replace old file in dest dir with new upload file with same name
                }
                if ($input === 'picture') {
                    $load->defaultVars();
                    $load->dest_dir = 'upload_files';
                    $load->file_size = 1 * 1000 * 1024; // 1MB
                    $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                    $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                }
                if ($input === 'pictures') {
                    $load->defaultVars();
                    $load->dest_dir = 'upload_files';
                    $load->tmp_dir = 'tmp'; // because tmp dir is to set - file will be saved here
                    $load->dir_permissions = 0777;
                    $load->file_permissions = 0666;
                    $load->file_size = 1 * 1000 * 1024; // 1MB
                    $load->file_mimetype = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/webp'];
                    $load->file_ext = ['.jpg', '.jpeg', '.png', '.webp'];
                    $load->new_file_name = ['zzz', 'index']; // ['', 'noindex'], 'new_filename'
                    $load->replace_old_file = true;
                }
                // PROCESSING DATA
                if ($load->execute($input_array, $key, $file)) {
                    if (!empty($load->message)) {
                        echo $load->message;
                        echo '<br />';
                    }
                } else {
                    if (!empty($load->error)) {
                        echo $load->error;
                        echo '<br />';
                    }
                    continue;
                }
            //
                // some command for processing a file located in a tmp_dir or dest_dir
            //
            }
        }
        // CLEAR FOLDER - file will be deleted in a directory specified by user
        if (!$load->delFilesInDir('tmp')) {
            if (!empty($load->error)) {
                echo $load->error;
                echo '<br />';
            }
        }
} else {
    ?>
	<form method="post" action="" enctype="multipart/form-data" id="upload_test" style="width:100%;">
		<div style="max-width:360px;margin:20px auto;">
	       	<p >
		        <label >File <small>(.php, .html, < 300KB)</small>:<br />
		           	<input type="hidden" name="MAX_FILE_SIZE" value="307200" />
					<input type="file" name="file" accept=".php, .html, text/html, text/php, text/x-php" required>
		        </label>
		    </p>
		    <p >
		        <label >File <small>(jpg, png, webp, < 1MB)</small>:<br />
		            <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />
		            <input type="file" name="picture" accept=".jpg, .jpeg, .png, .webp, image/jpeg, image/pjpeg, image/png, image/webp">
		        </label>
		    </p>
		    <p >
		        <label >Multiple files <small>(jpg, png, webp, < 1MB)</small>:<br />
		            <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />
		            <input type="file" multiple="multiple" name="pictures[]" accept=".jpg, .jpeg, .png, .webp, image/jpeg, image/pjpeg, image/png, image/webp">
		          	<!--<input type="file" name="pictures[]" />
					<input type="file" name="pictures[]" /> -->
				</label>
		    </p>
	    </div>
	    <div style="max-width:360px;margin:20px auto;">
	        <button type="submit" form="upload_test">Upload</button>
	        <button type="reset" form="upload_test" >Reset</button>
	    </div>
  	</form>
<?php

}
?>
  </body>
</html>
