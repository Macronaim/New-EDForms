<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

/**
 * Class GFHelp
 * Displays the Ed Forms Help page
 */
class GFHelp {

	/**
	 * Displays the Ed Forms Help page
	 *
	 * @since  Unknown
	 * @access public
	 */
	public static function help_page() {
		if ( ! GFCommon::ensure_wp_version() ) {
			return;
		}

		echo GFCommon::get_remote_message();

		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

		?>
		<link rel="stylesheet" href="<?php echo GFCommon::get_base_url() ?>/css/admin<?php echo $min; ?>.css" />
		<div class="wrap <?php echo GFCommon::get_browser_class() ?>">
			<h2><?php esc_html_e( 'Ed Forms Help', 'edforms' ); ?></h2>

			<?php GFCommon::display_dismissible_message(); ?>

			<div style="margin-top:10px;">

				<div class="gf_admin_notice"><?php printf( esc_html__( '%sIMPORTANT NOTICE:%s We do not provide support via telephone or e-mail. Please %sopen a support ticket%s.', 'edforms' ), '<strong>', '</strong>', '<a href="https://www.edconcept24.fr/support/" target="_blank">', '</a>' )  ?></div>

				<div class="gf_help_content"><p><?php printf( esc_html__( "Please review the plugin documentation and %sfrequently asked questions (FAQ)%s first. If you still can't find the answer %sopen a support ticket%s and we will be happy to answer your questions and assist you with any problems. %sPlease note:%s If you have not %spurchased a license%s from us, you will not have access to these help resources.", 'edforms' ), '<a href="https://docs.edconcept24.fr/category/knowledge-base/faqs/">', '</a>', '<a href="https://www.edconcept24.fr/support/" target="_blank">', '</a>', '<strong>', '</strong>', '<a href="https://www.edconcept24.fr/pricing/">', '</a>' ); ?></p></div>


				<div class="hr-divider"></div>

                <h3><?php esc_html_e( 'User Documentation', 'edforms' ); ?></h3>

                <div class="gforms_helpbox" style="margin:15px 0;">
                    <ul class="resource_list">
                        <li>
                            <i class="fa fa-book"></i> <a href="https://docs.edconcept24.fr/creating-a-form/">
                                <?php esc_html_e( 'Creating a Form', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/embedding-a-form/">
                                <?php esc_html_e( 'Embedding a Form', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/reviewing-form-submissions/">
                                <?php esc_html_e( 'Reviewing Form Submissions', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/configuring-confirmations-in-ed-forms/">
                                <?php esc_html_e( 'Configuring Confirmations', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/configuring-notifications-in-ed-forms/">
                                <?php esc_html_e( 'Configuring Notifications', 'edforms' ); ?>
                            </a>
                        </li>
                    </ul>

                </div>

				<div class="hr-divider"></div>

                <h3><?php esc_html_e( 'Developer Documentation', 'edforms' ); ?></h3>

                <div class="gforms_helpbox" style="margin:15px 0;">
                    <ul class="resource_list">
                        <li>
                            <i class="fa fa-book"></i> <a href="https://docs.edconcept24.fr/getting-started-with-the-ed-forms-api-gfapi/">
                                <?php esc_html_e( 'Getting Started with the Ed Forms API', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/api-functions/">
                                <?php esc_html_e( 'API Functions', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/web-api/">
                                <?php esc_html_e( 'Web API', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/add-on-framework/">
                                <?php esc_html_e( 'Add-On Framework', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/gfaddon/">
                                <?php esc_html_e( 'GFAddOn', 'edforms' ); ?>
                            </a>
                        </li>
                    </ul>

                </div>

				<div class="hr-divider"></div>

                <h3><?php esc_html_e( 'Designer Documentation', 'edforms' ); ?></h3>

                <div class="gforms_helpbox" style="margin:15px 0;">
                    <ul class="resource_list">
                        <li>
                            <i class="fa fa-book"></i> <a href="https://docs.edconcept24.fr/category/user-guides/design-and-layout/css-selectors/">
                                <?php esc_html_e( 'CSS Selectors', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/css-targeting-examples/">
                                <?php esc_html_e( 'CSS Targeting Examples', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/css-ready-classes/">
                                <?php esc_html_e( 'CSS Ready Classes', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/gform_field_css_class/">
                                <?php esc_html_e( 'gform_field_css_class', 'edforms' ); ?>
                            </a>
                        </li>
                        <li>
                            <i class="fa fa-book"></i> <a target="_blank" href="https://docs.edconcept24.fr/gform_noconflict_styles/">
                                <?php esc_html_e( 'gform_noconflict_styles', 'edforms' ); ?>
                            </a>
                        </li>
                    </ul>

                </div>

			</div>
		</div>


	<?php
	}
}
