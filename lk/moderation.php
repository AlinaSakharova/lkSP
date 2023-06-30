<?php
$title = "Модерация";
require '../config.php';
require 'header.php';
require 'footer.php';



if ($_SESSION['logged_user']['role'] == 'moderator') {

    $sql = $dbconnect->query("SELECT dateEdit, header, picture, short_info, full_info, date, name, idnew FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 3 order by dateEdit desc");
    $i = 0;
    $details = "Проверить";
    ?>

    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-1 text-start">
                <?php if(isset($_REQUEST['idnew'])){
                    $id = (int)$_REQUEST['idnew'];
                    $_SESSION['news']['idnew'] = $id;
                    $count_query = $dbconnect->query("SELECT COUNT(idmoderator) FROM sptest.moderation where id_new = {$id}");
                    $count  =mysqli_fetch_assoc($count_query);

                    if ($count["COUNT(idmoderator)"] != 0)
                    {
                        $full = $dbconnect->query("SELECT dateEdit, picture, short_info, news.date, full_info, header, comment, moderation.date, name, idnew FROM sptest.user, sptest.news,sptest.moderation where user.iduser = news.id_user and id_status=3 and news.idnew = moderation.id_new and news.idnew = {$id} ORDER BY idmoderator");
                    }
                    else{
                        $full = $dbconnect->query("SELECT picture, short_info, news.date, full_info, header, name, idnew FROM sptest.user, sptest.news where user.iduser = news.id_user and id_status=3  and news.idnew = {$id}");
                    }
                    $row = mysqli_fetch_assoc($full);
                    ?>
                    <h2>Модерация новости №<?php echo $row['idnew']?></h2>

                    


                    <form action="do_moderate.php"  method="post" enctype="multipart/form-data">
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
                        $result = $dbconnect->query("SELECT moderation.date, comment FROM sptest.user, sptest.news,sptest.moderation where user.iduser = news.id_user and id_status=3 and news.idnew = moderation.id_new and news.idnew = {$id} ORDER BY idmoderator");

                        echo "<label>Комментарий автору</label><textarea name='comment' class='form-control'id='comment' rows='5' id='short_info' placeholder='Введите комментарий автору'></textarea>";
                        echo "<div class='counter1'>Осталось символов: <span id='counter1'></span></div><br>";
                        if($count["COUNT(idmoderator)"] != 0){
                            echo "<p>Прошлые проверки:</p>";
                            echo "<table width=100%><tr><th>Дата</th><th></th><th>Комментарий</th></tr>";
                            while ($row = mysqli_fetch_assoc($result)){
                                echo "<tr>";
                                echo "<td>" . $row["date"] . "</td>";
                                echo "<td></td>";
                                echo "<td>" . $row["comment"] . "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                        else{
                            echo "<p>Это первая проверка</p>";
                        }

                        echo "<div class='btn-group'>";
                        echo "<button class='btn btn-success' name='do_moderate_publish' type='submit'>Опубликовать</button>";

                        echo "<button class='btn btn-danger' name='do_moderate_rough' type='submit'>Вернуть на доработку</button>";
                        echo '</div>';
                        ?>
                    </form>
                <?php }
                else {   ?>
                <h2>Новости, отправленные на проверку</h2>
				<?php
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
                        <th id="heading-style">Дата</th>
                        <th id="heading-style">Заголовок</th>
                        <th id="heading-style">Автор</th>
                        <?php while ($row = mysqli_fetch_assoc($sql)){ ?>
                            <tr>
                                <td class="table-disription"> <?php echo $row['dateEdit'];?></td>
                                <td class="table-disription"> <?php echo $row['header'];?></td>
                                <td class="table-disription"> <?php echo $row['name'];?></td>
                                <?php
                                echo '<td class="link-fullnews"><a href="/lk/moderation.php?idnew='.$row['idnew'].'">Проверить</a></td>';
                                $i++; ?>

                            </tr>
                        <?php 	} ?>
                    </table>
                    <?php 	} ?>
                </div>
            </div>
        </div>
    </div>
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
</script>
<?php }
else{
    header('Location: index.php');
} ?>
<?php require 'footer.php'; ?> <!-- Подключаем подвал проекта -->
