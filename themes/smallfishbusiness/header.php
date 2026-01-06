<?php
/**
 * The header for our theme
 *
 * @package SmallFishBusiness
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts: Playfair Display, DM Sans, Source Serif 4 -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400;1,9..40,500&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,500;0,8..60,600;1,8..60,400;1,8..60,500&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-surface-50' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    <a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-white focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg" href="#primary">
        <?php esc_html_e( 'Skip to content', 'smallfishbusiness' ); ?>
    </a>

    <header id="masthead" class="site-header sticky top-0 z-50">
        <div class="container-fluid">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Logo -->
                <div class="site-branding flex-shrink-0">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link flex items-center">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/img/logo-small-fish-business.png' ); ?>"
                             alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                             class="h-9 lg:h-11 w-auto transition-transform duration-300">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav id="site-navigation" class="main-navigation hidden lg:flex items-center">
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 3,
                        'walker'         => new SFB_Nav_Walker(),
                        'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
                    ] );
                    ?>
                </nav>

                <!-- Mobile Menu Button -->
                <button type="button"
                        class="lg:hidden p-2.5 rounded-lg text-surface-600 hover:text-surface-900 hover:bg-surface-100 transition-all duration-150"
                        id="mobile-menu-toggle"
                        aria-expanded="false"
                        aria-controls="mobile-menu">
                    <span class="sr-only"><?php esc_html_e( 'Open menu', 'smallfishbusiness' ); ?></span>
                    <!-- Hamburger icon -->
                    <svg class="w-6 h-6 hamburger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <!-- Close icon (hidden by default) -->
                    <svg class="w-6 h-6 close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <?php get_template_part( 'template-parts/header/navigation', 'mobile' ); ?>
    </header>

    <div id="content" class="site-content flex-grow">

