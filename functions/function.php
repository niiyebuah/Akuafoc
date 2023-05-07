<?php
// Function to verify password

function verify_pass($hash_pass, $login_pass){
    return password_verify($login_pass, $hash_pass);
}


function file_upload($directory, $subdir, $tempname, $image){
    // check whether the directory exists
    // upload the file into the directory
    $folder = "../$directory/$subdir/".$image;
    if (!file_exists("../$directory/$subdir/")) {
        @mkdir("../$directory/$subdir/", 0777);

        echo("Folder created successfully");
        move_uploaded_file($tempname, $folder);
        return $folder;
    }
    else{
        move_uploaded_file($tempname, $folder);
        return $folder;
    }
    return false;
}


?>