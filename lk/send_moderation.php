<?php
$title = "На проверке";
require 'header.php';
require '../config.php';

//$id = (int)$_REQUEST['idnew'];
//$_SESSION['news']['idnew'] = $id;
$sql = $dbconnect->query("SELECT dateEdit, header, full_info, short_info, news.date, idnew, picture, name FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 3");
$i = 0;


if (!empty($_SESSION['auth'])) { ?>
    <div class="container">
        <div class="row justify-content">
            <div class="col-md-10 col-md-offset-1 text-start">
			<?php if(isset($_REQUEST['idnew'])){
					$id = (int)$_REQUEST['idnew'];
                    $_SESSION['news']['idnew'] = $id;
                    $full = $dbconnect->query("SELECT dateEdit, header, full_info, short_info, date, idnew, picture, name FROM sptest.user, sptest.news WHERE user.iduser = news.id_user and id_status = 3 and idnew = '$id'");
					
                    $row = mysqli_fetch_assoc($full);
					

                    ?>
                    <h2>Новость №<?php echo $row['idnew']?></h2>
					
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
                        <label>Заголовок</label><p><?php echo $row['header'] ?></p><br>
                        <label>Дата</label><p><?php echo $row['date'] ?></p><br>
                       <label>Превью</label><p><?php echo $row['short_info']?></p>
                        <label>Текст новости</label><p><?php echo $row['full_info']?></p>
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
                        
						
					}
            else {   ?>
			
			 <h2>Отправлено на проверку</h2>
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
                        <th id="heading-style">Дата изменения</th>
                        <th id="heading-style">Заголовок</th>
						<th id="heading-style">Автор</th>
                        <?php while ($row = mysqli_fetch_assoc($sql)){ ?>
                            <tr>
                                <td width="100" class="table-disription"> <?php echo $row['dateEdit'];?></td>
                                <td class="table-disription"> <?php echo $row['header'];?></td>
                                <td class="table-disription"> <?php echo $row['name'];?></td>
                                <?php
                                echo '<td class="link-fullnews"><a href="/lk/send_moderation.php?idnew='.$row['idnew'].'">Просмотреть</a></td>';
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
</script>
<?php } ?>



