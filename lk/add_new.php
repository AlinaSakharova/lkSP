<?php
$title = "Добавить новость";
require 'header.php';
require '../config.php';

if (!empty($_SESSION['auth'])) { ?>
    <div class="container">
        <div class="row justify-content">
            <div class="col-md-10 col-md-offset-1 text-start">
                <h2>Добавить новость</h2>
                <form action="do_add_new.php" enctype="multipart/form-data" method="post" id="form-new">
                    <?
                    if (isset($_SESSION['message'])) {
                        if ($_SESSION['message']['status'] == 'success')
                        {
                            echo "<p class=\"text-success\">".$_SESSION['message']['text'];
                        }
                        else
                        {
                            echo "<p class=\"text-danger\">".$_SESSION['message']['text'];
                        }

                        unset($_SESSION['message']);
                    }
                    ?>
                    <input type="text" class="form-control" name="header" id="header" placeholder="Введите заголовок"><br>

                    <?php

                        echo "<input type='date' class='form-control' name='date' id='date' placeholder='Введите дату' required value = '".date("d.m.Y")."'><br>";
                        echo "<p>Введите краткое описание</p>";
                     ?>
                    <textarea class="form-control" name="short_info" id="short_info" placeholder= "Введите краткое описание" required></textarea><br>
                    <?php
                        echo "<div class='counter'>Осталось символов: <span id='counter'></span></div><br>";
                        echo "<br>";
                        echo "<p>Введите текст новости</p>";
                    ?>

                    <textarea class="form-control" name="full_info" id="full_info" cols="60" rows="7" placeholder="Введите текст новости"></textarea><br>
                    <div class="form-group">
                        <label for="picture">Выберите фотографию для главной страницы</label>
                        <input type="file" name="picture" id="picture">
                    </div>
                    <button class="btn btn-success" name="do_add_new" type="submit">Добавить новость</button>
                    <button class="btn btn-default" name="do_save_new" type="submit">Черновик</button>
            </div>
            </form>
        </div>
    </div>
    </div>
    
    <script>
        CKEDITOR.replace( 'full_info' );
        CKEDITOR.replace( 'short_info' );
    </script>
    <script>
        window.addEventListener('load',
            function (e) {
                var d = new Date();
                var day = d.getDate(); if (day<10) day='0'+day;
                var month = d.getMonth() + 1; if (month<10) month='0'+month;
                var year = d.getFullYear();
                document.getElementById("date").value = year+"-"+month+"-"+day;
            }, false);
    </script>
    <script>
        $(document).ready(function(){
            var maxCount = 2000;

            $("#counter").html(maxCount);

            $("#short_info").keyup(function() {
                var revText = this.value.length;

                if (this.value.length > maxCount)
                {
                    this.value = this.value.substr(0, maxCount);
                }
                var cnt = (maxCount - revText);
                if(cnt <= 0){$("#counter").html('0');}
                else {$("#counter").html(cnt);}

            });
        });

    </script>
<?php } ?>



