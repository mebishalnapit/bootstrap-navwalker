# BOOTSTRAP NAVWALKER
A custom WordPress nav walker class to implement the Bootstrap 4 navigation style in a custom WordPress Bootstrap based theme using the WordPress built in menu manager.

## INSTALLATION
Download and place the **bootstrap-navwalker** file inside your theme folder, ie, inside **/wp-content/themes/theme-name/** folder.

Now, require the file via the below PHP code addition inside your **functions.php** file of the theme:
```php
require get_template_directory() . '/bootstrap-navwalker.php';
```

## USAGE
Once you register the menu via the below PHP code in the **funtions.php** file:
```php
register_nav_menus( array(
    'menu-1' => esc_html__( 'Primary', 'theme-textdomain' ),
) );
```

Now, you can display the menu in your theme via below PHP code addition in the **header.php** file of your theme.
```php
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-content" aria-controls="navbar-content" aria-expanded="false" aria-label="<?php esc_html_e( 'Toggle Navigation', 'theme-textdomain' ); ?>">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbar-content">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'menu-1',
			'menu_id'        => 'primary-menu',
			'container'      => false,
			'depth'          => 2,
			'menu_class'     => 'navbar-nav ml-auto',
			'walker'         => new Bootstrap_NavWalker(),
			'fallback_cb'    => 'Bootstrap_NavWalker::fallback',
		) );
		?>
	</div>
</nav>
```
