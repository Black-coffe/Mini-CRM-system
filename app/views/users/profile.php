<?php

$title = 'Profile';
ob_start(); 
?>

<!-- Основной контент -->
<div class="container mt-5">
  <h1 class="mb-4">Профиль пользователя</h1>
  <div class="card">
      <div class="card-body">
        <table class="table table-hover table-responsive">
          <tbody>
            <tr>
              <th scope="row">ID</th>
              <td><?php echo $user['id']; ?></td>
            </tr>
            <tr>
              <th scope="row">Имя пользователя</th>
              <td><?php echo $user['username']; ?></td>
            </tr>
            <tr>
              <th scope="row">Email</th>
              <td><?php echo $user['email']; ?></td>
            </tr>
            <tr>
              <th scope="row">Email подтвержден</th>
              <td><?php echo $user['email_verification'] ? 'Да' : 'Нет'; ?></td>
            </tr>
            <tr>
              <th scope="row">Администратор</th>
              <td><?php echo $user['is_admin'] ? 'Да' : 'Нет'; ?></td>
            </tr>
            <tr>
              <th scope="row">Привязан телеграм</th>
              <td><?php echo $isUserTelegram['telegram_username'] ? $isUserTelegram['telegram_username'] : 'Нет'; ?></td>
            </tr>
            <tr>
              <th scope="row">Роль</th>
              <td><?php echo $user['role']; ?></td>
            </tr>
            <tr>
              <th scope="row">Дата создания</th>
              <td><?php echo $user['created_at']; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

<?php if(!$isUserTelegram): ?>    
  <hr class="mt-5">

  <h3 class="mb-4">Генерация одноразового пароля</h3>
  <h5>Ваш OTP код: <?=$otp;?></h5>
  <ul class="list-group">
    <li class="list-group-item">Нажамите <strong>Сохранить пароль</strong></li>
    <li class="list-group-item">Перейдите в телеграм и найдите в поиске бота: <a target="_blank" href="https://t.me/mini_crm_bot" >@mini_crm_bot</a>.</li>
    <li class="list-group-item">Введите команду /addaccount</li>
    <li class="list-group-item">Бот запросит ваш email, вот он: <strong><?php echo $user['email']; ?></strong></li>
    <li class="list-group-item">Бот запросит OTP code, вот он: <strong><?=$otp;?></strong></li>
    <li class="list-group-item">Если все сделаете верно, ваши аккаунты будут связаны!</li>
  </ul>

  <?php if($visible): ?>
  <p style="color:olivedrab;">Данный OTP код после нажатия "Сохранить пароль" будет записан в базу данных и в течении 1 часа будет доступен для авторизации через телеграм</p>
  
  
  <form action="/users/otpstore" method="POST">
    <input type="hidden" name="otp" value="<?=$otp;?>">
    <input type="hidden" name="user_id" value="<?=$_SESSION['user_id'];?>">
    <button type="submit" class="btn btn-primary">Сохранить код</button>
  </form>
  <?php endif ?>
<?php endif ?>
</div>


<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>