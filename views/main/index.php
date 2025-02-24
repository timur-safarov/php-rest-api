<?php
	use models\User;
?>

<div class="col-lg-12">

	<?php if (!IS_AUTH) :?>
		
		<form id="reg" class="intro-newslatter" action="main/user-create" method="post">

			<fieldset class="d-flex flex-wrap align-content-center flex-column">

				<div class="input-group w-50 mb-4">
					<div class="input-group-prepend">
						<label class="input-group-text" for="email">Email</label>
					</div>

					<input id="email"
							mes="Формат E-mail не верный" 
								type="email"
									name="email" 
										placeholder="E-mail"
											value="user@test.ru"
												class="form-control">

				</div>

				<div class="input-group w-50 mb-4">
					<div class="input-group-prepend">
						<label class="input-group-text" for="username">Username</label>
					</div>

					<input id="username"
							mes="Имя пользвателя указано не правильно" 
								type="text"
									name="username" 
										placeholder="Имя пользователя"
											value="pupkin"
												class="form-control">

				</div>

				<div class="input-group w-50 mb-4">
					<div class="input-group-prepend">
						<label class="input-group-text" for="password">Password</label>
					</div>
					<input id="password"
							mes="Пароль указан не правильно" 
								type="password"
									name="password" 
										placeholder="Пароль"
											value="12345"
												class="form-control">
				</div>

				<div class="input-group w-50 mb-4">
					<button type="button" class="site-btn">Зарегистрироваться</button>
				</div>

			</fieldset>

		</form>
		
	<?php else: ?>

		<table class="table">
			<thead>
				<tr class="table-warning">
					<th colspan="2">Ваши реквизиты</th>
				</tr>
			</thead>
			<tbody>
				<tr class="table-success">
					<th>Username</th>
					<th><?=User::getAuthUser()['username'];?></th>
				</tr>
				<tr class="table-success">
					<th>Email</th>
					<th><?=User::getAuthUser()['email'];?></th>
				</tr>
				<tr class="table-success">
					<th>Api Token</th>
					<th><?=User::getAuthUser()['token'];?></th>
				</tr>

				<?php foreach($apiUrl as $k => $url): ?>

					<tr class="table-success">
						<th><?=$k;?></th>
						<th>
							<a href="<?=$url;?>">
								<?=$url;?>
							</a>
						</th>
					</tr>

				<?php endforeach; ?>
			</tbody>
		</table>

	<?php endif; ?>

</div>

<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="err-message"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
