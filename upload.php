<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteImage'])) {
    $fileToDelete = $_POST['deleteImage'];
    $filePath = "uploads/" . basename($fileToDelete);

    if (strpos(realpath($filePath), realpath("uploads/")) === 0 && file_exists($filePath)) {
        unlink($filePath);
        echo "deleted";
    } else {
        echo "error";
    }
    exit();
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bannerNumber'])) {
    $bannerNumber = intval($_POST['bannerNumber']);
    $target_dir = "uploads/";

    foreach (['banner', 'destination'] as $type) {
        if (!empty($_FILES["{$type}{$bannerNumber}"]) && $_FILES["{$type}{$bannerNumber}"]["error"] === 0) {
            move_uploaded_file($_FILES["{$type}{$bannerNumber}"]["tmp_name"], "{$target_dir}{$type}{$bannerNumber}.jpg");
        }
    }

    if (isset($_POST["desc$bannerNumber"])) {
        file_put_contents("{$target_dir}banner{$bannerNumber}.txt", $_POST["desc$bannerNumber"]);
    }

    echo "success";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Upload Panel</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .main-contents{
      display: flex;
      flex-direction: column;
    }
    body {
      background: url("uploads/PageBg.png") repeat;
      background-size: 600px auto;
      font-family: 'Inter', sans-serif;
      align-items: center;
      min-height: 100vh;
    }
    header {
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(12px);
    background: #2c3e50;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom-left-radius: 25px;
    border-bottom-right-radius: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    transition: background 0.3s ease, backdrop-filter 0.3s ease;
    margin-bottom: 40px;
  }

    .hh1{
      padding-left: 0px;
      background: #2c3e50;
    }
    .hh1 h1 {
      align-self: center;
      color: #fff;
    }
    h3{
      color: #FFF;
    }
    .upload-container {
      width: 100%;
      max-width: 800px;
      padding: 20px;
      background: #2c3e50;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      border-radius: 12px;
      margin-bottom: 20px;
      margin: auto;
    }
    .upload-container p {
      text-align: center;
      margin-bottom: 20px;
      color: #fff;
      font-size: 30px;
    }
    .panel {
      background: #2c3e50;
      padding: 20px;
      margin-bottom: 30px;
      border-radius: 10px;
      border: 1px solid #ddd;
    }
    label, textarea, input[type="file"] {
      color: #FFF;
      display: block;
      width: 100%;
      margin-bottom: 15px;
    }
    textarea{
      background: rgba(255, 255, 255, 0.07);
    }
    button {
      background: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover { background: #0056b3; }
    .preview-img { width: 100%; margin: 10px 0; border-radius: 5px; }
    .home-button a button {
      background: #28a745;
    }
    .home-button a button:hover {
      background: #218838;
    }

    .delete-button {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 12px;
        margin: 5px 0;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .delete-button:hover {
        background: #c82333;
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <div class="menu">
      <h1>🌍 Travel Explorer</h1>
      <nav>
        <a href="index.html">Home</a>
        <a href="#">Destinations</a>
        <a href="#">Packages</a>
        <a href="https://www.facebook.com/rastafarai432" target="_blank">Contact</a>
        <a href="upload.php">Admin</a>
      </nav>
    </div>
  </header>
    <div class="upload-container">
      <p>Update Banners and Descriptions</p>
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <form class="panel" enctype="multipart/form-data">
          <h3>Banner Set <?= $i ?></h3>

          <label>Current Banner Image:</label>
          <img src="uploads/banner<?= $i ?>.jpg" alt="Banner <?= $i ?>" class="preview-img"><br>
          <button type="button" onclick="deleteImage('banner<?php echo $i; ?>.jpg')">Delete Banner Image</button>

          <label>Update Banner Image:</label>
          <input type="file" name="banner<?= $i ?>">

          <label>Current Selection Image:</label>
          <img src="uploads/destination<?= $i ?>.jpg" alt="Destination <?= $i ?>" class="preview-img"><br>
          <button type="button" onclick="deleteImage('destination<?php echo $i; ?>.jpg')">Delete Selection Image</button>


          <label>Update Selection Image:</label>
          <input type="file" name="destination<?= $i ?>">

          <label>Edit Description:</label>
          <textarea name="desc<?= $i ?>" rows="4"><?php 
            if (file_exists("uploads/banner$i.txt")) echo htmlspecialchars(file_get_contents("uploads/banner$i.txt")); 
          ?></textarea>

          <input type="hidden" name="bannerNumber" value="<?= $i ?>">

          <button type="submit">Save Banner Set <?= $i ?></button>
        </form>
      <?php endfor; ?>

    </div>
  <footer>
    <div class="footer-content">
      <p>© 2025 Travel Explorer - Cristino Algodon III.<br> All rights reserved.</p>
      <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="https://www.facebook.com/rastafarai432" target="_blank">Contact Us</a>
      </div>
    </div>
  </footer>

  <script>
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('upload.php', { method: 'POST', body: formData })
          .then(response => response.text())
          .then(result => {
            if (result.trim() === "success") {
              alert('Updated successfully!');
              form.querySelectorAll('img.preview-img').forEach(img => {
                const src = img.src.split('?')[0];
                img.src = `${src}?v=${Date.now()}`;
              });
            } else {
              alert('Failed to update.');
            }
          })
          .catch(() => alert('Error occurred!'));
      });
    });
    function deleteImage(fileName) {
  if (confirm('Are you sure you want to delete ' + fileName + '?')) {
    const formData = new FormData();
    formData.append('deleteImage', fileName);

    fetch('upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(result => {
      if (result.trim() === "deleted") {
        alert(fileName + ' deleted successfully!');
        document.querySelectorAll('img.preview-img').forEach(img => {
          if (img.src.includes(fileName)) {
            img.src = '';
          }
        });
      } else {
        alert('Failed to delete ' + fileName);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error occurred while deleting!');
    });
  }
}

  </script>

</body>
</html>
