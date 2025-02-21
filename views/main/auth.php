
<div class="col-lg-12">

	<?php if (!IS_AUTH) :?>

		<form id="form-auth" class="intro-newslatter" action="../main/auth" method="post">

			<fieldset class="d-flex flex-wrap align-content-center flex-column">

				<div class="input-group w-50 mb-4">
					<div class="input-group-prepend">
						<label class="input-group-text" for="username">Username</label>
					</div>

					<input id="username"
							mes="Имя пользвателя указано не правильно" 
								type="text"
									name="user[username]" 
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
									name="user[password]" 
										placeholder="Пароль"
											value="12345"
												class="form-control">
				</div>

				<div class="input-group w-50 mb-4">
					<button type="button" class="site-btn">Отправить</button>
				</div>

			</fieldset>

		</form>
		
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