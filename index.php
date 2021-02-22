<?php 
require 'config.php';

$errors = [];
$message = [];


if(!empty($_FILES)){ /* empty проверяет, пустая ли переменная */
  
    /* Пробегаемся по файлам */
    for ($i = 0; $i < count($_FILES['files']['name']); $i++){
        $fileName = $_FILES['files']['name'][$i];

        /* Проверяем размер файла */
        if($_FILES['files']['size'][$i] > UPLOAD_MAX_SIZE){
            $errors[] = 'Недопустимый размер файла ' . $fileName;
            continue;
        }

        /*ПРОВЕРЯЕМ ФОРМАТ ФАЙЛА */
        /* in_array — Проверяет, присутствует ли в массиве значение */
        if(!in_array($_FILES['files']['type'][$i], ALLOWED_TYPES)){
            $errors[] = 'Недопустимый формат файла ' . $fileName;
            continue;
        }
        
        $filePath = UPLOAD_DIR . '/' . basename($fileName);

        /* ПЫТАЕМСЯ ЗАГРУЗИТЬ ФАЙЛ */
        /* move_uploaded_file — Перемещает загруженный файл в новое место */
        if(!move_uploaded_file($_FILES['files']['tmp_name'][$i], $filePath)){
            $errors[] = 'Ошибка загрузки файла ' . $fileName;
            continue;
        }
    }
}

//Если файл был удален
if(!empty($_POST['name'])){
    $filePath = UPLOAD_DIR . '/' . $_POST['name'];
    $commentPath  = COMMENT_DIR . '/' . $_POST['name'] . '.txt';
 
    //Удаляем изображение
    if(file_exists($filePath)){
        unlink($filePath);
    }
    //file_exists — Проверяет существование указанного файла или каталога
    //Удаляем файл комментариев, если он существует
    
    if(file_exists($commentPath)){
        unlink($commentPath);
    }

    $message[] = 'Файл был удален';
}

    //получаем список файлов, исключая системные
    $files = scandir(UPLOAD_DIR);
    $files = array_filter($files, function($file){
        return !in_array($file, ['.', '..', 'gitkeep']);
    });

    /*print_r($files); */
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Загрузка файлов</title>
</head>
<body>




<div class="container pt-4">
    <h1 class="mb-4">Загрузка файлов</h1>
 
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($_FILES) && empty($errors)): ?>
        <div class="alert alert-success">Файлы успешно загружены</div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"> <?php echo $_POST['name'] . " " . $message[0] ?> </div>

    <?php endif; ?>

    <!-- Удаление файла -->
    <div class="flex-container">
        <?php foreach($files as $key): ?>
            
        <div class="elem">
            
        <a href="<?php echo URL . "file.php?name=$key" ?>" title="Просмотр полного изображения">
            <img src="<?php echo UPLOAD_DIR . '/' . $key; ?>" alt="">            
        </a>  

            <form action="<?php echo URL; ?>" method="POST" class="cl">
                <input type="hidden" name = "name" value="<?php echo $key ?>">
                <button type="submit" class="btn btn-secondary">X</button>
            </form>
              
        </div>   
    <?php endforeach ?>

    </div>

    <form action="<?php echo URL; ?>" method="post" enctype="multipart/form-data">
        <div class="custom-file">
            <!--multiple required позволяет выбрать несколько файлов для загрузки-->
            <input type="file" class="custom-file-input" name="files[]" id="customFile" multiple required>
            <label class="custom-file-label" for="customFile" data-browse="Выбрать">Выберите файлы</label>
            <small class="form-text text-muted">
                Максимальный размер файла: <?php echo UPLOAD_MAX_SIZE / 1000000; ?>Мб.
                Допустимые форматы: <?php echo implode(', ', ALLOWED_TYPES) ?>.
            </small>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Загрузить</button>
        <a href="<?php echo URL; ?>" class="btn btn-secondary ml-3">Сброс</a>
    </form>
</div>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>

<script>
    $(() => {
        bsCustomFileInput.init();
    })
</script>
</body>
</html>

