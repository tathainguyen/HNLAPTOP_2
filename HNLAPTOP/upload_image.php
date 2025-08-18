<?php
if(isset($_FILES['file'])){
    $file = $_FILES['file'];
    $upload_dir = __DIR__ . '/uploads2/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $file_name = time() . '_' . basename($file['name']);
    $target = $upload_dir . $file_name;
    if(move_uploaded_file($file['tmp_name'], $target)){
        // Trả về đường dẫn để Summernote chèn vào nội dung
        echo 'uploads2/' . $file_name;
    } else {
        http_response_code(400);
        echo "Upload failed";
    }
}
?>