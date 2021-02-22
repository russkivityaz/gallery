<?php 
require 'config.php';
$errors = [];
$message = [];

$imgFileName = $_GET['name'];
$commentFilePath = COMMENT_DIR . '/' . $imgFileName . '.txt';

/*echo $imgFileName;
echo $commentFilePath;*/




if(!empty($_POST['comment'])){
    
    $comment = trim($_POST['comment']); 

    if($comment === ""){
        $errors[] = 'Вы не ввели комментарий';
    }

    if(empty($errors)){
       $comment = strip_tags($comment);
       $comment = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"), "<br/>", $comment);
       $comment = date('d.m.y H:i') . ': ' . $comment; 

       file_put_contents($commentFilePath, $comment . "\n", FILE_APPEND);
       $message[]= 'Комментарий был добавлен';
       header("Location: ".$_SERVER['REQUEST_URI']);
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    

    <title>Document</title>
</head>
<body>

    <div class="fullSizeImage">

        <div class="elemFullSize">
            <img src="<?php echo UPLOAD_DIR . '/' . $imgFileName; ?>" alt="">
            <a href="<?php echo URL . "index.php" ?>" class="btn btn-secondary cl">X</a>                    
        </div>

        <div>
            <?php if(file_exists($commentFilePath)):?>
                <?php $readFileComments = explode("\n", file_get_contents($commentFilePath));?>
                    <?php foreach($readFileComments as $readFileComment):?>
                        <p><?php echo $readFileComment;?></p>
                    <?php endforeach; ?>
            <?php endif; ?>
        </div>

    <form action="" method="POST">
        <textarea class="comment" name="comment"></textarea>
        <button type="submit" class="btn btn-primary">Отправить комментарий</button>
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

</body>
</html>