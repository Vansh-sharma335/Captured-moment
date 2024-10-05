<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = 'uploads/';
    $targetFile = $targetDir . basename($_FILES['image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        echo json_encode(['success' => false, 'message' => 'File is not an image.']);
        exit();
    }

    // Check file size (limit to 5MB)
    if ($_FILES['image']['size'] > 5000000) {
        echo json_encode(['success' => false, 'message' => 'File is too large.']);
        exit();
    }

    // Allow certain file formats
    if ($imageFileType !== 'jpg' && $imageFileType !== 'jpeg' && $imageFileType !== 'png' && $imageFileType !== 'gif') {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.']);
        exit();
    }

    // Resize image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        // Resize image
        $resizeResult = resizeImage($targetFile, 800, 600);
        if ($resizeResult) {
            echo json_encode(['success' => true, 'filePath' => $targetFile]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to resize image.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
    }
}

function resizeImage($file, $maxWidth, $maxHeight) {
    list($width, $height) = getimagesize($file);
    $ratio = $width / $height;
    
    if ($width > $maxWidth) {
        $width = $maxWidth;
        $height = $width / $ratio;
    }
    
    if ($height > $maxHeight) {
        $height = $maxHeight;
        $width = $height * $ratio;
    }
    
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($width, $height);
    
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $width, $height);
    
    if (imagejpeg($dst, $file)) {
        imagedestroy($src);
        imagedestroy($dst);
        return true;
    }
    return false;
}
?>
