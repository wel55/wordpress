
<?php
/* Template Name: Página Meus Dados
 */
?>

<?php
/* Get user info. */
global $current_user, $wp_roles;
//get_currentuserinfo(); //deprecated since 3.1

/* Load the registration file. */
//require_once( ABSPATH . WPINC . '/registration.php' ); //deprecated since 3.1
$error = array();    
/* If profile was saved, update profile. */
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

    /* Update user password. */
    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
        if ( $_POST['pass1'] == $_POST['pass2'] )
            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }

    /* Update user information. */
    if ( !empty( $_POST['url'] ) )
        wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => esc_url( $_POST['url'] ) ) );
    if ( !empty( $_POST['email'] ) ){
        if (!is_email(esc_attr( $_POST['email'] )))
            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
        else{
            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
        }
    }

    if ( !empty( $_POST['first-name'] ) )
        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
    if ( !empty( $_POST['last-name'] ) )
        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
    if ( !empty( $_POST['description'] ) )
        update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

    /* Redirect so the page will show updated info.*/
  /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
    if ( count($error) == 0 ) {
        //action hook for plugins and extra fields saving
        do_action('edit_user_profile_update', $current_user->ID);
        wp_redirect( get_permalink() );
        exit;
    }
}
?>


<div id="inner-wrapper">
    <div class="inner consultas">
        <section class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <div id="post-<?php the_ID(); ?>">
                            <div class="entry-content entry">
                                <?php the_content(); ?>
                                <?php if ( !is_user_logged_in() ) : ?>
                                        <p class="warning">
                                            <?php _e('Você precisa fazer login para editar seus dados.', 'profile'); ?>
                                        </p><!-- .warning -->
                                <?php else : ?>
                                    <?php if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
                                    <form method="post" id="adduser" action="<?php the_permalink(); ?>">
                                        <p class="form-username">
                                            <label for="first-name"><?php _e('Nome', 'profile'); ?></label>
                                            <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
                                        </p><!-- .form-username -->
                                        <p class="form-username">
                                            <label for="last-name"><?php _e('Sobrenome', 'profile'); ?></label>
                                            <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
                                        </p><!-- .form-username -->
                                        <p class="form-email">
                                            <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                                            <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
                                        </p><!-- .form-email -->
                                        <p class="form-password">
                                            <label for="pass1"><?php _e('Senha *', 'profile'); ?> </label>
                                            <input class="text-input" name="pass1" type="password" id="pass1" />
                                        </p><!-- .form-password -->
                                        <p class="form-password">
                                            <label for="pass2"><?php _e('Repetir senha *', 'profile'); ?></label>
                                            <input class="text-input" name="pass2" type="password" id="pass2" />
                                        </p><!-- .form-password -->
                                        

                                        <?php 
                                            //action hook for plugin and extra fields
                                           do_action('edit_user_profile',$current_user)
                                        ?>
                                        <p class="form-submit">
                                            <?php echo $referer; ?>
                                            <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Salvar', 'profile'); ?>" />
                                            <?php wp_nonce_field( 'update-user' ) ?>
                                            <input name="action" type="hidden" id="action" value="update-user" />
                                        </p><!-- .form-submit -->
                                    </form><!-- #adduser -->
                                    
                                <?php endif; ?>
                            </div><!-- .entry-content -->
                        </div><!-- .hentry .post -->
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-data">
                            <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
                        </p><!-- .no-data -->
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>
