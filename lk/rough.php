<?php
$title = "Черновики";
require 'header.php';
require '../config.php';

//$id = (int)$_REQUEST['idnew'];
  $id = $_SESSION['logged_user']['iduser'];
$sql = $dbconnect->query("SELECT dateEdit, header, full_info, short_info, news.date, idnew, picture, name FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 1 and user.iduser = '$id' order by dateEdit desc");
$i = 0;


if (!empty($_SESSION['auth'])) { ?>
    <div class="container">
        <div class="row justify-content">
            <div class="col-md-10 col-md-offset-1 text-start">
			<?php if(isset($_REQUEST['idnew'])){
					$id = (int)$_REQUEST['idnew'];
                    $_SESSION['news']['idnew'] = $id;
                    $full = $dbconnect->query("SELECT dateEdit, header, full_info, short_info, date, idnew, picture, name FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 1 and idnew = '$id'");
					
                    $row = mysqli_fetch_assoc($full);
					

                    ?>
                    <h2>Черновик №<?php echo $row['idnew']?></h2>
					
                    <form action="do_rough.php" enctype="multipart/form-data"  method="post"> 
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
                        
                       <label>Превью</label><textarea name='short_info' class='form-control' rows='5' id='short_info'><?php echo $row['short_info']?></textarea>
                       <div class='counter'>Осталось символов: <span id='counter'></span></div>
                        <br>
                        <label>Текст новости</label><textarea class='form-control' name='full_info' cols="60" rows="7" id='full_info'><?php echo $row['full_info']?></textarea>
						  <div class='counter'>Осталось символов: <span id='counter1'></span></div>
                        <label for='picture'>Картинка для главной страницы</label>
                        <br>
						<?php 
						if ($row['picture'] == NULL)
						{
							echo "<span>Изображение не добавлено</span>";
						}
						else{
						?>
							<img class="img-responsive" width="150" src="/<?=$row['picture']?>"><br>
						<?php }
                        
						?>
						<div class="form-group">
                        <label for="picture">Выберите фотографию для главной страницы</label>
                        <input type="file" name="picture" id="picture">
                    </div>
          
                        <div class='btn-group'>
                        <button class='btn btn-success' name='do_add_new' type='submit'>Отправить на проверку</button>

                        <button class='btn btn-danger' name='do_save_new' type='submit'>Черновик</button>
                        </div>
                        
					</form>
			 <?php }
            else {   ?>
			
			 <h2>Черновики</h2>
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
                        <th id="heading-style">Дата</th>
                        <th id="heading-style">Заголовок</th>
						<th id="heading-style">Автор</th>
                        <?php while ($row = mysqli_fetch_assoc($sql)){ ?>
                            <tr>
                                <td class="table-disription"> <?php echo $row['dateEdit'];?></td>
                                <td class="table-disription"> <?php echo $row['header'];?></td>
                                <td class="table-disription"> <?php echo $row['name'];?></td>
                                <?php
                                echo '<td class="link-fullnews"><a href="/lk/rough.php?idnew='.$row['idnew'].'">Исправить</a></td>';
                                $i++; 
								?>

                            </tr>
                        <?php 	} ?>
                    </table>
				</div>
			<?php 	} ?>
            </div>
        </div>
    </div>
<script>
         CKEDITOR.replace( 'full_info' );
         CKEDITOR.replace( 'short_info' );
</script>
    <script>
        $(document).ready(function(){
            var maxCount = 1000;

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
<?php } ?>



