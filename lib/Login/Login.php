<?php


namespace CGP\Login;


class Login {
	public static function init()
	{
		add_action('wp_footer', [__CLASS__, 'loginForm' ]);
		add_filter( 'wp_nav_menu_items', [__CLASS__, 'addLoginLink'], 2, PHP_INT_MAX);
	}

	/**
	 * Login form markup
	 */

	public static function loginForm()
	{
		if( is_user_logged_in() ) {
			return;
		}
		?>
			<div id="loginForm" class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<form class="modal-content" action="<?= home_url('wp/wp-login.php') ?>" method="post">
						<div class="modal-header">
							<h5 class="modal-title"><?= __('Login') ?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="user_login"><?= __('Username or Email Address') ?></label>
								<input type="text" class="form-control" id="user_login" name="log" required>
							</div>
							<div class="form-group">
								<label for="user_pass"><?= __('Password') ?></label>
								<input type="password" class="form-control" id="user_pass" name="pwd" required>
							</div>
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="rememberme" name="rememberme">
								<label class="form-check-label" for="rememberme" value="forever"><?= __('Remember me') ?></label>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="redirect_to" value="<?= $_SERVER['REQUEST_URI']; ?>" />
							<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Close') ?></button>
                            <button type="submit" class="btn btn-primary"><?= __('Login') ?></button>
						</div>
					</form>
				</div>
			</div>
		<?php
	}

	/**
	 * Add menu link to menu
	 * @var $items string menu items markup
	 * @var $args object menu arguments
	 * @return string menu items markup
	 */

	public static function addLoginLink($items, $args)
	{
		if ( 'primary_navigation' === $args->theme_location ) {
			if( !is_user_logged_in() ) {
				$items .= sprintf('<li class="nav-item"><a href="#loginForm" data-toggle="modal" data-target="#loginForm" class="nav-link">%s</a></li>', __('Login'));
			} else {
				global $current_user;
				$items .= sprintf('<li class="nav-item dropdown">
											<a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">%s</a>
											<div class="dropdown-menu" aria-labelledby="navbarDropdown">
          										<a class="dropdown-item" href="%s">%s</a>
         									</div>
										  </li>',
											__('Hello') . ', ' . $current_user->display_name,
											wp_logout_url($_SERVER['REQUEST_URI']),
											__('Logout'));
			}
		}
		return $items;
	}
}