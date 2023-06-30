<?php
$title = "Опубликованы";
require 'header.php';
require '../config.php';
$id_user = $_SESSION['logged_user']['iduser'];
$count = $dbconnect->query("SELECT count(*) FROM sptest.news");
$itemPerPage = 10;

$sql = $dbconnect->query("SELECT dateEdit, header, full_info, short_info, news.date, idnew, picture, name FROM sptest.news WHERE id_user = {$id_user} and id_status = 4 order by dateEdit desc");
$i = 0;

function pagelink($p)
{
    return $p > 1 ? 'publish.php?page='.$p : 'publish.php';
}
function rotate01()
{
    global $pn;
    if ($pn == 0)
        $pn++;
    elseif ($pn == 1)
        $pn--;

}
// обработка запроса и формирование результатов
if($_REQUEST['page']) {
    $from = ($_REQUEST['page']-1) * $itemPerPage;
    $to = $itemPerPage;
    if ($_SESSION['logged_user']['role']== 'moderator'){
        $results = $dbconnect->query("SELECT * FROM sptest.user,  sptest.news where user.iduser = news.id_user  and id_status=5 ORDER BY idnew DESC LIMIT {$from},{$to}");
    }
}else {
    if ($_SESSION['logged_user']['role']== 'moderator'){
        $results =  $dbconnect->query("SELECT * FROM sptest.user, sptest.news where user.iduser = news.id_user and id_status=5 ORDER BY idnew DESC LIMIT 0,{$itemPerPage}");
    }
}




if (!empty($_SESSION['auth'])) { ?>
    <div class="container">
        <div class="row justify-content">
            <div class="col-md-10 col-md-offset-1 text-start">
                <?php
                if (isset($_REQUEST['idnew']) && $_SESSION['logged_user']['role'] == 'moderator'){
                $id = (int)$_REQUEST['idnew'];
                $_SESSION['news']['idnew'] = $id;
                $full = $dbconnect->query("SELECT dateEdit, header, full_info, short_info, date, idnew, picture FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 5 and idnew = '$id'");
                //var_dump($full);
                $row = mysqli_fetch_assoc($full);

                ?>
                    <h2>Модерация новости №<?php echo $row['idnew']?></h2>
                    <form action="do_archive.php"  method="post" enctype="multipart/form-data">
                        <?php
                        if (isset($_SESSION['message'])) {

                            if ($_SESSION['message']['status'] == 'success')
                            {
                                echo "<p class=\"text-success\">".$_SESSION['message']['text'];
                                echo "</p>";
                            }
                            else
                            {
                                echo "<p class=\"text-danger\">".$_SESSION['message']['text'];
                                echo "</p>";
                            }

                            unset($_SESSION['message']);
                        }
                        ?>
                        <label>Заголовок</label><input type="text" class="form-control" name="header" id="header" value="<?php echo $row['header'] ?>"><br>
                        <label>Дата</label><input type="date" class="form-control" name="date" id="date" value="<?php echo $row['date'] ?>"><br>
                        <?php
                        echo "<label>Превью</label><textarea name='short_info' class='form-control' rows='5' id='short_info'>".$row['short_info'].'</textarea>';
                        echo "<div class='counter'>Осталось символов: <span id='counter'></span></div>";
                        echo "<br>";
                        echo "<label>Текст новости</label><textarea class='form-control' name='full_info' rows='10' id='full_info'>".$row['full_info'].'</textarea><br>';
                        echo "<label for='picture'>Картинка для главной страницы</label><br>";

                        if ($row['picture'] == "")
                        {
                            echo "<span>Изображение не добавлено</span><br>";
                        }
                        else{
                            ?>
                            <img class="img-responsive" width="150" src="/<?=$row['picture']?>"><br>

                            <?php
                        }?>
                        <div class="form-group">
                            <label for="picture">Выберите фотографию для главной страницы</label>
                            <input type="file" name="picture" id="picture">
                        </div>
                        <?
                        echo "<label>Комментарий автору</label><textarea name='comment' class='form-control'id='comment' rows='5' id='short_info' placeholder='Введите комментарий автору'></textarea>";
                        echo "<div class='counter1'>Осталось символов: <span id='counter1'></span></div><br>";
                        echo "<div class='btn-group'>";
                        echo "<button class='btn btn-success' name='do_edit_publish' type='submit'>Опубликовать</button>";

                        echo "<button class='btn btn-danger' name='do_edit_rough' type='submit'>Вернуть на доработку</button>";
                        echo "<button class='btn btn-secondary' name='do_edit_archive' type='submit'>Оставить в архиве</button>";
                        echo '</div>';
                        ?>
                    </form>
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
                        $(document).ready(function(){
                            var maxCount = 500;

                            $("#counter1").html(maxCount);

                            $("#comment").keyup(function() {
                                var revText = this.value.length;

                                if (this.value.length > maxCount)
                                {
                                    this.value = this.value.substr(0, maxCount);
                                }
                                var cnt = (maxCount - revText);
                                if(cnt <= 0){$("#counter1").html('0');}
                                else {$("#counter1").html(cnt);}

                            });
                        });
                    </script>
                    <script>
                        CKEDITOR.replace( 'full_info' );
                        CKEDITOR.replace( 'short_info' );
                    </script><?php
                }
                else {   ?>

                    <h2>Новости в архиве</h2>
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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <th id="heading-style">Дата публикации</th>
                            <th id="heading-style">Заголовок</th>
                            <th id="heading-style">Автор</th>
                            <?php while ($row = mysqli_fetch_assoc($results)){
                                rotate01();
                                ?>
                                <tr>
                                    <td width="100" size = 14px class="table-disription"> <?php echo $row['date'];?></td>
                                    <td class="table-disription"> <?php echo $row['header'];?></td>
                                    <td class="table-disription"> <?php echo $row['name'];?></td>
                                    <?php
                                    echo '<td class="link-fullnews"><a href="/lk/archive.php?idnew='.$row['idnew'].'">Просмотреть</a></td>';
                                    $i++;
                                    ?>

                                </tr>
                            <?php 	} ?>
                        </table>
                        <?php
                        $count = $dbconnect->query("SELECT count(*) FROM  sptest.news where id_user = {$id_user} and id_status=5");
                        $row = mysqli_fetch_assoc($count);
                        $pages[] = "Страницы:";
                        for($iter=1; $iter<=$row["count(*)"] / $itemPerPage+1; $iter++) {

                            if ($iter == $_REQUEST['page']) {
                                $pages[] = "[$iter]";
                            } else {
                                $pages[] = "<a href=".pagelink($iter).">[$iter]</a>";
                            }
                        }
                        $news_pages = join("&nbsp;", $pages);

                        echo $news_pages;
                        ?>
                    </div>
                <?php 	} ?>
            </div>
        </div>
    </div>
<?php }




