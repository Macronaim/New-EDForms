<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

/**
 * Enqueue the styles and scripts required for the tooltips.
 */
function enqueue_tooltip_scripts() {

	wp_enqueue_style( 'gform_tooltip' );
	wp_enqueue_style( 'gform_font_awesome' );

	wp_enqueue_script( 'gform_tooltip_init' );

}
add_action( 'admin_enqueue_scripts', 'enqueue_tooltip_scripts' );

global $__gf_tooltips;
$__gf_tooltips = array(
	'notification_send_to_email'                  => '<h6>' . __( 'Send To Email Address', 'edforms' ) . '</h6>' . __( 'Enter the email address you would like the notification email sent to.', 'edforms' ),
	'notification_autoformat'                     => '<h6>' . __( 'Disable Auto-Formatting', 'edforms' ) . '</h6>' . __( 'When enabled, auto-formatting will insert paragraph breaks automatically. Disable auto-formatting when using HTML to create email notification content.', 'edforms' ),
	'notification_send_to_routing'                => '<h6>' . __( 'Routing', 'edforms' ) . '</h6>' . __( 'Allows notification to be sent to different email addresses depending on values selected in the form.', 'edforms' ),
	'notification_from_email'                     => '<h6>' . __( 'From Email Address', 'edforms' ) . '</h6>' . __( 'Enter the email address you would like the notification email sent from, or select the email from available email form fields.', 'edforms' ),
	'notification_from_name'                      => '<h6>' . __( 'From Name', 'edforms' ) . '</h6>' . __( 'Enter the name you would like the notification email sent from, or select the name from available name fields.', 'edforms' ),
	'notification_reply_to'                       => '<h6>' . __( 'Reply To', 'edforms' ) . '</h6>' . __( 'Enter the email address you would like to be used as the reply to address for the notification email.', 'edforms' ),
	'notification_cc'                             => '<h6>' . __( 'Carbon Copy Addresses', 'edforms' ) . '</h6>' . __( 'Enter a comma separated list of email addresses you would like to receive a CC of the notification email.', 'edforms' ),
	'notification_bcc'                            => '<h6>' . __( 'Blind Carbon Copy Addresses', 'edforms' ) . '</h6>' . __( 'Enter a comma separated list of email addresses you would like to receive a BCC of the notification email.', 'edforms' ),
	'form_activity'                               => '<h6>' . __( 'Limit Form Activity', 'edforms' ) . '</h6>' . __( 'Limit the number of entries a form can generate and/or schedule a time period the form is active.', 'edforms' ),
	'form_limit_entries'                          => '<h6>' . __( 'Limit Number of Entries', 'edforms' ) . '</h6>' . __( 'Enter a number in the input box below to limit the number of entries allowed for this form. The form will become inactive when that number is reached.', 'edforms' ),
	'form_schedule_form'                          => '<h6>' . __( 'Schedule Form', 'edforms' ) . '</h6>' . __( 'Schedule a time period the form is active.', 'edforms' ),
	'form_honeypot'                               => '<h6>' . __( 'Enable Anti-spam honeypot', 'edforms' ) . '</h6>' . __( 'Enables the honeypot spam protection technique, which is an alternative to the reCAPTCHA field.', 'edforms' ),
	'form_animation'                              => '<h6>' . __( 'Enable Animation', 'edforms' ) . '</h6>' . __( 'Check this option to enable a sliding animation when displaying/hiding conditional logic fields.', 'edforms' ),
	'form_title'                                  => '<h6>' . __( 'Form Title', 'edforms' ) . '</h6>' . __( 'Enter the title of your form.', 'edforms' ),
	'form_description'                            => '<h6>' . __( 'Form Description', 'edforms' ) . '</h6>' . __( 'Enter a description for your form. This may be used for user instructions.', 'edforms' ),
	'form_label_placement'                        => '<h6>' . __( 'Form Label Placement', 'edforms' ) . '</h6>' . __( 'Select the default label placement.  Labels can be top aligned above a field, left aligned to the left of a field, or right aligned to the right of a field. This is a global label placement setting', 'edforms' ),
	'form_description_placement'                  => '<h6>' . __( 'Description Placement', 'edforms' ) . '</h6>' . __( 'Select the default description placement.  Descriptions can be placed above the field inputs or below the field inputs. This setting can be overridden in the appearance settings for each field.', 'edforms' ),
	'form_sub_label_placement'                    => '<h6>' . __( 'Sub-Label Placement', 'edforms' ) . '</h6>' . __( 'Select the default sub-label placement.  Sub-labels can be placed above the field inputs or below the field inputs. This setting can be overridden in the appearance settings for each field.', 'edforms' ),
	'form_button_text'                            => '<h6>' . __( 'Form Button Text', 'edforms' ) . '</h6>' . __( 'Enter the text you would like to appear on the form submit button.', 'edforms' ),
	'form_button_image'                           => '<h6>' . __( 'Form Button Image', 'edforms' ) . '</h6>' . __( 'Enter the path to an image you would like to use as the form submit button.', 'edforms' ),
	'form_css_class'                              => '<h6>' . __( 'Form CSS Class Name', 'edforms' ) . '</h6>' . __( 'Enter the CSS class name you would like to use in order to override the default styles for this form.', 'edforms' ),
	'form_field_add_icon_url'                     => '<h6>' . __( 'Add Icon URL', 'edforms' ) . '</h6>' . __( "Enter the URL of a custom image to replace the default 'add item' icon. A maximum size of 16px by 16px is recommended", 'edforms' ),
	'form_field_delete_icon_url'                  => '<h6>' . __( 'Delete Icon URL', 'edforms' ) . '</h6>' . __( "Enter the URL of a custom image to replace the default 'delete item' icon. A maximum size of 16px by 16px is recommended", 'edforms' ),
	'form_confirmation_message'                   => '<h6>' . __( 'Confirmation Message Text', 'edforms' ) . '</h6>' . __( 'Enter the text you would like the user to see on the confirmation page of this form.', 'edforms' ),
	'form_confirmation_autoformat'                => '<h6>' . __( 'Disable Auto-Formatting', 'edforms' ) . '</h6>' . __( 'When enabled, auto-formatting will insert paragraph breaks automatically. Disable auto-formatting when using HTML to create the confirmation content.', 'edforms' ),
	'form_redirect_to_webpage'                    => '<h6>' . __( 'Redirect Form to Page', 'edforms' ) . '</h6>' . __( 'Select the page you would like the user to be redirected to after they have submitted the form.', 'edforms' ),
	'form_redirect_to_url'                        => '<h6>' . __( 'Redirect Form to URL', 'edforms' ) . '</h6>' . __( 'Enter the URL of the webpage you would like the user to be redirected to after they have submitted the form.', 'edforms' ),
	                                                 /* Translators: %s: Link to article about query strings. */
	'form_redirect_querystring'                   => '<h6>' . __( 'Pass Data Via Query String', 'edforms' ) . '</h6>' . sprintf( __( "To pass field data to the confirmation page, build a Query String using the 'Insert Merge Tag' drop down. %s..more info on querystrings &raquo;%s", 'edforms' ), "<a href='https://en.wikipedia.org/wiki/Query_string' target='_blank'>", '</a>' ),
	'form_field_label'                            => '<h6>' . __( 'Field Label', 'edforms' ) . '</h6>' . __( 'Enter the label of the form field.  This is the field title the user will see when filling out the form.', 'edforms' ),
	'form_field_label_html'                       => '<h6>' . __( 'Field Label', 'edforms' ) . '</h6>' . __( 'Enter the label for this HTML block. It will help you identify your HTML blocks in the form editor, but it will not be displayed on the form.', 'edforms' ),
	'form_field_disable_margins'                  => '<h6>' . __( 'Disable Default Margins', 'edforms' ) . '</h6>' . __( 'When enabled, margins are added to properly align the HTML content with other form fields.', 'edforms' ),
	'form_field_recaptcha_theme'                  => '<h6>' . __( 'reCAPTCHA Theme', 'edforms' ) . '</h6>' . __( 'Select the visual theme for the reCAPTCHA field from the available options to better match your site design.', 'edforms' ),
	'form_field_captcha_type'                     => '<h6>' . __( 'CAPTCHA Type', 'edforms' ) . '</h6>' . __( 'Select the type of CAPTCHA you would like to use.', 'edforms' ),
	'form_field_custom_field_name'                => '<h6>' . __( 'Custom Field Name', 'edforms' ) . '</h6>' . __( 'Select the custom field name from available existing custom fields, or enter a new custom field name.', 'edforms' ),
	'form_field_type'                             => '<h6>' . __( 'Field type', 'edforms' ) . '</h6>' . __( 'Select the type of field from the available form fields.', 'edforms' ),
	'form_field_maxlength'                        => '<h6>' . __( 'Maximum Characters', 'edforms' ) . '</h6>' . __( 'Enter the maximum number of characters that this field is allowed to have.', 'edforms' ),
	'form_field_maxrows'                          => '<h6>' . __( 'Maximum Rows', 'edforms' ) . '</h6>' . __( 'Enter the maximum number of rows that users are allowed to add.', 'edforms' ),
	'form_field_date_input_type'                  => '<h6>' . __( 'Date Input Type', 'edforms' ) . '</h6>' . __( 'Select the type of inputs you would like to use for the date field. Date Picker will let users select a date from a calendar. Date Field will let users free type the date.', 'edforms' ),
	'form_field_address_type'                     => '<h6>' . __( 'Address Type', 'edforms' ) . '</h6>' . __( 'Select the type of address you would like to use.', 'edforms' ),
	'form_field_address_default_state_us'         => '<h6>' . __( 'Default State', 'edforms' ) . '</h6>' . __( 'Select the state you would like to be selected by default when the form gets displayed.', 'edforms' ),
	'form_field_address_default_state_canadian'   => '<h6>' . __( 'Default Province', 'edforms' ) . '</h6>' . __( 'Select the province you would like to be selected by default when the form gets displayed.', 'edforms' ),
	'form_field_address_default_country'          => '<h6>' . __( 'Default Country', 'edforms' ) . '</h6>' . __( 'Select the country you would like to be selected by default when the form gets displayed.', 'edforms' ),
	'form_field_address_hide_country'             => '<h6>' . __( 'Hide Country', 'edforms' ) . '</h6>' . __( 'For addresses that only apply to one country, you can choose to not display the country drop down. Entries will still be recorded with the selected country.', 'edforms' ),
	'form_field_address_hide_address2'            => '<h6>' . __( 'Hide Address Line 2', 'edforms' ) . '</h6>' . __( 'Check this box to prevent the extra address input (Address Line 2) from being displayed in the form.', 'edforms' ),
	'form_field_address_hide_state_us'            => '<h6>' . __( 'Hide State Field', 'edforms' ) . '</h6>' . __( 'Check this box to prevent the State field from being displayed in the form.', 'edforms' ),
	'form_field_address_hide_state_canadian'      => '<h6>' . __( 'Hide Province Field', 'edforms' ) . '</h6>' . __( 'Check this box to prevent Province field from being displayed in the form.', 'edforms' ),
	'form_field_address_hide_state_international' => '<h6>' . __( 'Hide State/Province/Region', 'edforms' ) . '</h6>' . __( 'Check this box to prevent the State/Province/Region from being displayed in the form.', 'edforms' ),
	'form_field_name_format'                      => '<h6>' . __( 'Field Name Format', 'edforms' ) . '</h6>' . __( 'Select the format you would like to use for the Name field.  There are 3 options, Normal which includes First and Last Name, Extended which adds Prefix and Suffix, or Simple which is a single input field.', 'edforms' ),
	'form_field_number_format'                    => '<h6>' . __( 'Number Format', 'edforms' ) . '</h6>' . __( 'Select the format of numbers that are allowed in this field. You have the option to use a comma or a dot as the decimal separator.', 'edforms' ),
	'form_field_force_ssl'                        => '<h6>' . __( 'Force SSL', 'edforms' ) . '</h6>' . __( 'Check this box to prevent this field from being displayed in a non-secure page (i.e. not https://). It will redirect the page to the same URL, but starting with https:// instead. This option requires a properly configured SSL certificate.', 'edforms' ),
	'form_field_card_style'                       => '<h6>' . __( 'Credit Card Icon Style', 'edforms' ) . '</h6>' . __( 'Select the style you would like to use for the credit card icons.', 'edforms' ),
	'form_field_date_format'                      => '<h6>' . __( 'Field Date Format', 'edforms' ) . '</h6>' . __( 'Select the format you would like to use for the date input.', 'edforms' ),
	'form_field_time_format'                      => '<h6>' . __( 'Time Format', 'edforms' ) . '</h6>' . __( 'Select the format you would like to use for the time field.  Available options are 12 hour (i.e. 8:30 pm) and 24 hour (i.e. 20:30).', 'edforms' ),
	'form_field_fileupload_allowed_extensions'    => '<h6>' . __( 'Allowed File Extensions', 'edforms' ) . '</h6>' . __( 'Enter the allowed file extensions for file uploads.  This will limit the type of files a user may upload.', 'edforms' ),
	'form_field_multiple_files'                   => '<h6>' . __( 'Enable Multi-File Upload', 'edforms' ) . '</h6>' . __( 'Select this option to enable multiple files to be uploaded for this field.', 'edforms' ),
	'form_field_max_files'                        => '<h6>' . __( 'Maximum Number of Files', 'edforms' ) . '</h6>' . __( "Specify the maximum number of files that can be uploaded using this field. Leave blank for unlimited. Note that the actual number of files permitted may be limited by this server's specifications and configuration.", 'edforms' ),
	'form_field_max_file_size'                    => '<h6>' . __( 'Maximum File Size', 'edforms' ) . '</h6>' . __( 'Specify the maximum file size in megabytes allowed for each of the files.', 'edforms' ),
	'form_field_phone_format'                     => '<h6>' . __( 'Phone Number Format', 'edforms' ) . '</h6>' . __( 'Select the format you would like to use for the phone input.  Available options are domestic US/CANADA style phone number and international long format phone number.', 'edforms' ),
	'form_field_description'                      => '<h6>' . __( 'Field Description', 'edforms' ) . '</h6>' . __( 'Enter the description for the form field.  This will be displayed to the user and provide some direction on how the field should be filled out or selected.', 'edforms' ),
	'form_field_required'                         => '<h6>' . __( 'Required Field', 'edforms' ) . '</h6>' . __( 'Select this option to make the form field required.  A required field will prevent the form from being submitted if it is not filled out or selected.', 'edforms' ),
	'form_field_no_duplicate'                     => '<h6>' . __( 'No Duplicates', 'edforms' ) . '</h6>' . __( 'Select this option to limit user input to unique values only.  This will require that a value entered in a field does not currently exist in the entry database for that field.', 'edforms' ),
	'form_field_number_range'                     => '<h6>' . __( 'Number Range', 'edforms' ) . '</h6>' . __( 'Enter the minimum and maximum values for this form field.  This will require that the value entered by the user must fall within this range.', 'edforms' ),
	'form_field_enable_calculation'               => '<h6>' . __( 'Enable Calculation', 'edforms' ) . '</h6>' . __( 'Enabling calculations will allow the value of this field to be dynamically calculated based on a mathematical formula.', 'edforms' ),
	'form_field_calculation_formula'              => '<h6>' . __( 'Formula', 'edforms' ) . '</h6>' . __( 'Specify a mathematical formula. The result of this formula will be dynamically populated as the value for this field.', 'edforms' ),
	'form_field_calculation_rounding'             => '<h6>' . __( 'Rounding', 'edforms' ) . '</h6>' . __( 'Specify how many decimal places the number should be rounded to.', 'edforms' ),
	'form_field_admin_label'                      => '<h6>' . __( 'Admin Label', 'edforms' ) . '</h6>' . __( 'Enter the admin label of the form field.  Entering a value in this field will override the Field Label when displayed in the Ed Forms administration tool.', 'edforms' ),
	'form_field_sub_labels'                       => '<h6>' . __( 'Sub-Labels', 'edforms' ) . '</h6>' . __( 'Enter values in this setting to override the Sub-Label for each field.', 'edforms' ),
	'form_field_label_placement'                  => '<h6>' . __( 'Label Visibility', 'edforms' ) . '</h6>' . __( 'Select the label visibility for this field.  Labels can either inherit the form setting or be hidden.', 'edforms' ),
	'form_field_description_placement'            => '<h6>' . __( 'Description Placement', 'edforms' ) . '</h6>' . __( 'Select the description placement.  Descriptions can be placed above the field inputs or below the field inputs.', 'edforms' ),
	'form_field_sub_label_placement'              => '<h6>' . __( 'Sub-Label Placement', 'edforms' ) . '</h6>' . __( 'Select the sub-label placement.  Sub-labels can be placed above the field inputs or below the field inputs.', 'edforms' ),
	'form_field_size'                             => '<h6>' . __( 'Field Size', 'edforms' ) . '</h6>' . __( 'Select a form field size from the available options. This will set the width of the field. Please note: if using a paragraph field, the size applies only to the height of the field.', 'edforms' ),
	'form_field_name_fields'                      => '<h6>' . __( 'Name Fields', 'edforms' ) . '</h6>' . __( "Select the fields you'd like to use in this Name field and customize the Sub-Labels by entering new ones.", 'edforms' ),
	'form_field_name_prefix_choices'              => '<h6>' . __( 'Name Prefix Choices', 'edforms' ) . '</h6>' . __( 'Add Choices to this field. You can mark a choice as selected by default by using the radio buttons on the left.', 'edforms' ),
	'form_field_address_fields'                   => '<h6>' . __( 'Address Fields', 'edforms' ) . '</h6>' . __( "Select the fields you'd like to use in this Address Field and customize the Sub-Labels by entering new ones.", 'edforms' ),
	'form_field_default_value'                    => '<h6>' . __( 'Default Value', 'edforms' ) . '</h6>' . __( 'If you would like to pre-populate the value of a field, enter it here.', 'edforms' ),
	'form_field_default_input_values'             => '<h6>' . __( 'Default Values', 'edforms' ) . '</h6>' . __( 'If you would like to pre-populate the value of a field, enter it here.', 'edforms' ),
	'form_field_placeholder'                      => '<h6>' . __( 'Placeholder', 'edforms' ) . '</h6>' . __( 'The Placeholder will not be submitted along with the form. Use the Placeholder to give a hint at the expected value or format.', 'edforms' ),
	'form_field_input_placeholders'               => '<h6>' . __( 'Placeholders', 'edforms' ) . '</h6>' . __( 'Placeholders will not be submitted along with the form. Use Placeholders to give a hint at the expected value or format.', 'edforms' ),
	'form_field_enable_copy_values_option'        => '<h6>' . __( 'Use Values Submitted in a Different Field', 'edforms' ) . '</h6>' . __( 'Activate this option to allow users to skip this field and submit the values entered in the associated field. For example, this is useful for shipping and billing address fields.', 'edforms' ),
	'form_field_copy_values_option_label'         => '<h6>' . __( 'Option Label', 'edforms' ) . '</h6>' . __( 'Enter the label to be displayed next to the check box. For example, &quot;same as shipping address&quot;.', 'edforms' ),
	'form_field_copy_values_option_field'         => '<h6>' . __( 'Source Field', 'edforms' ) . '</h6>' . __( 'Select the field to be used as the source for the values for this field.', 'edforms' ),
	'form_field_copy_values_option_default'       => '<h6>' . __( 'Activated by Default', 'edforms' ) . '</h6>' . __( 'Select this setting to display the option as activated by default when the form first loads.', 'edforms' ),
	'form_field_validation_message'               => '<h6>' . __( 'Validation Message', 'edforms' ) . '</h6>' . __( 'If you would like to override the default error validation for a field, enter it here.  This message will be displayed if there is an error with this field when the user submits the form.', 'edforms' ),
	'form_field_recaptcha_language'               => '<h6>' . __( 'reCAPTCHA Language', 'edforms' ) . '</h6>' . __( 'Select the language you would like to use for the reCAPTCHA display from the available options.', 'edforms' ),
	'form_field_css_class'                        => '<h6>' . __( 'CSS Class Name', 'edforms' ) . '</h6>' . __( 'Enter the CSS class name you would like to use in order to override the default styles for this field.', 'edforms' ),
	'form_field_visibility'                       => GFCommon::get_visibility_tooltip(),
	'form_field_choices'                          => '<h6>' . __( 'Field Choices', 'edforms' ) . '</h6>' . __( 'Add Choices to this field. You can mark each choice as checked by default by using the radio/checkbox fields on the left.', 'edforms' ),
	'form_field_choice_values'                    => '<h6>' . __( 'Enable Choice Values', 'edforms' ) . '</h6>' . __( 'Check this option to specify a value for each choice. Choice values are not displayed to the user viewing the form, but are accessible to administrators when viewing the entry.', 'edforms' ),
	'form_field_conditional_logic'                => '<h6>' . __( 'Conditional Logic', 'edforms' ) . '</h6>' . __( 'Create rules to dynamically display or hide this field based on values from another field.', 'edforms' ),
	                                                 /* Translators: %s: Link to Chosen jQuery framework. */
	'form_field_enable_enhanced_ui'               => '<h6>' . __( 'Enable Enhanced UI', 'edforms' ) . '</h6>' . sprintf( __( "By selecting this option, the %s jQuery script will be applied to this field, enabling search capabilities to Drop Down fields and a more user-friendly interface for Multi Select fields.", 'edforms' ), "<a href='https://harvesthq.github.com/chosen/' target='_blank'>Chosen</a>" ),
	'form_field_select_all_choices'               => '<h6>' . __( '"Select All" Choice', 'edforms' ) . '</h6>' . __( 'Check this option to add a "Select All" checkbox before the checkbox choices to allow users to check all the checkboxes with one click.', 'edforms' ),
	'form_field_other_choice'                     => '<h6>' . __( '"Other" Choice', 'edforms' ) . '</h6>' . __( 'Check this option to add a text input as the final choice of your radio button field. This allows the user to specify a value that is not a predefined choice.', 'edforms' ),
	'form_require_login'                          => '<h6>' . __( 'Require user to be logged in', 'edforms' ) . '</h6>' . __( 'Check this option to require a user to be logged in to view this form.', 'edforms' ),
	'form_require_login_message'                  => '<h6>' . __( 'Require Login Message', 'edforms' ) . '</h6>' . __( 'Enter a message to be displayed to users who are not logged in (shortcodes and HTML are supported).', 'edforms' ),
	'form_page_conditional_logic'                 => '<h6>' . __( 'Page Conditional Logic', 'edforms' ) . '</h6>' . __( 'Create rules to dynamically display or hide this page based on values from another field.', 'edforms' ),
	'form_progress_indicator'                     => '<h6>' . __( 'Progress Indicator', 'edforms' ) . '</h6>' . __( 'Select which type of visual progress indicator you would like to display.  Progress Bar, Steps or None.', 'edforms' ),
	'form_percentage_style'                       => '<h6>' . __( 'Progress Bar Style', 'edforms' ) . '</h6>' . __( 'Select which progress bar style you would like to use.  Select custom to choose your own text and background color.', 'edforms' ),
	'form_page_names'                             => '<h6>' . __( 'Page Names', 'edforms' ) . '</h6>' . __( 'Name each of the pages on your form.  Page names are displayed with the selected progress indicator.', 'edforms' ),
	'next_button_text'                            => '<h6>' . __( 'Next Button Text', 'edforms' ) . '</h6>' . __( 'Enter the text you would like to appear on the page next button.', 'edforms' ),
	'next_button_image'                           => '<h6>' . __( 'Next Button Image', 'edforms' ) . '</h6>' . __( 'Enter the path to an image you would like to use as the page next button.', 'edforms' ),
	'previous_button_text'                        => '<h6>' . __( 'Previous Button Text', 'edforms' ) . '</h6>' . __( 'Enter the text you would like to appear on the page previous button.', 'edforms' ),
	'previous_button_image'                       => '<h6>' . __( 'Previous Button Image', 'edforms' ) . '</h6>' . __( 'Enter the path to an image you would like to use as the page previous button.', 'edforms' ),
	'form_nextbutton_conditional_logic'           => '<h6>' . __( 'Next Button Conditional Logic', 'edforms' ) . '</h6>' . __( "Create rules to dynamically display or hide the page's Next Button based on values from another field.", 'edforms' ),
	'form_button_conditional_logic'               => '<h6>' . __( 'Conditional Logic', 'edforms' ) . '</h6>' . __( 'Create rules to dynamically display or hide the submit button based on values from another field.', 'edforms' ),
	'form_field_post_category_selection'          => '<h6>' . __( 'Post Category', 'edforms' ) . '</h6>' . __( 'Select which categories are displayed. You can choose to display all of them or select individual ones.', 'edforms' ),
	'form_field_post_status'                      => '<h6>' . __( 'Post Status', 'edforms' ) . '</h6>' . __( 'Select the post status that will be used for the post that is created by the form entry.', 'edforms' ),
	'form_field_post_author'                      => '<h6>' . __( 'Post Author', 'edforms' ) . '</h6>' . __( 'Select the author that will be used for the post that is created by the form entry.', 'edforms' ),
	'form_field_post_format'                      => '<h6>' . __( 'Post Format', 'edforms' ) . '</h6>' . __( 'Select the post format that will be used for the post that is created by the form entry.', 'edforms' ),
	'form_field_post_content_template_enable'     => '<h6>' . __( 'Post Content Template', 'edforms' ) . '</h6>' . __( 'Check this option to format and insert merge tags into the Post Content.', 'edforms' ),
	'form_field_post_title_template_enable'       => '<h6>' . __( 'Post Title Template', 'edforms' ) . '</h6>' . __( 'Check this option to format and insert merge tags into the Post Title.', 'edforms' ),
	'form_field_post_category'                    => '<h6>' . __( 'Post Category', 'edforms' ) . '</h6>' . __( 'Select the category that will be used for the post that is created by the form entry.', 'edforms' ),
	'form_field_current_user_as_author'           => '<h6>' . __( 'Use Current User as Author', 'edforms' ) . '</h6>' . __( 'Selecting this option will set the post author to the WordPress user that submitted the form.', 'edforms' ),
	'form_field_image_meta'                       => '<h6>' . __( 'Image Meta', 'edforms' ) . '</h6>' . __( 'Select one or more image metadata field to be displayed along with the image upload field. They enable users to enter additional information about the uploaded image.', 'edforms' ),
	'form_field_featured_image'                   => '<h6>' . __( 'Set as Featured Image', 'edforms' ) . '</h6>' . __( "Check this option to set this image as the post's Featured Image.", 'edforms' ),
	'form_field_prepopulate'                      => '<h6>' . __( 'Incoming Field Data', 'edforms' ) . '</h6>' . __( 'Check this option to enable data to be passed to the form and pre-populate this field dynamically. Data can be passed via Query Strings, Shortcode and/or Hooks.', 'edforms' ),
	'form_field_content'                          => '<h6>' . __( 'Content', 'edforms' ) . '</h6>' . __( 'Enter the content (Text or HTML) to be displayed on the form.', 'edforms' ),
	'form_field_base_price'                       => '<h6>' . __( 'Base Price', 'edforms' ) . '</h6>' . __( 'Enter the base price for this product.', 'edforms' ),
	'form_field_disable_quantity'                 => '<h6>' . __( 'Disable Quantity', 'edforms' ) . '</h6>' . __( 'Disables the quantity field.  A quantity of 1 will be assumed or you can add a Quantity field to your form from the Pricing Fields.', 'edforms' ),
	'form_field_product'                          => '<h6>' . __( 'Product Field', 'edforms' ) . '</h6>' . __( 'Select which Product this field is tied to.', 'edforms' ),
	'form_field_mask'                             => '<h6>' . __( 'Input Mask', 'edforms' ) . '</h6>' . __( 'Input masks provide a visual guide allowing users to more easily enter data in a specific format such as dates and phone numbers.', 'edforms' ),
	'form_standard_fields'                        => '<h6>' . __( 'Standard Fields', 'edforms' ) . '</h6>' . __( 'Standard Fields provide basic form functionality.', 'edforms' ),
	'form_advanced_fields'                        => '<h6>' . __( 'Advanced Fields', 'edforms' ) . '</h6>' . __( 'Advanced Fields are for specific uses.  They enable advanced formatting of regularly used fields such as Name, Email, Address, etc.', 'edforms' ),
	'form_post_fields'                            => '<h6>' . __( 'Post Fields', 'edforms' ) . '</h6>' . __( 'Post Fields allow you to add fields to your form that create Post Drafts in WordPress from the submitted data.', 'edforms' ),
	'form_pricing_fields'                         => '<h6>' . __( 'Pricing Fields', 'edforms' ) . '</h6>' . __( 'Pricing fields allow you to add fields to your form that calculate pricing for selling goods and services.', 'edforms' ),
	'export_select_form'                          => '<h6>' . __( 'Export Selected Form', 'edforms' ) . '</h6>' . __( 'Select the form you would like to export entry data from. You may only export data from one form at a time.', 'edforms' ),
	'export_select_forms'                         => '<h6>' . __( 'Export Selected Forms', 'edforms' ) . '</h6>' . __( 'Select the forms you would like to export.', 'edforms' ),
	'export_conditional_logic'                    => '<h6>' . __( 'Conditional Logic', 'edforms' ) . '</h6>' . __( 'Filter the entries by adding conditions.', 'edforms' ),
	'export_select_fields'                        => '<h6>' . __( 'Export Selected Fields', 'edforms' ) . '</h6>' . __( 'Select the fields you would like to include in the export.', 'edforms' ),
	'export_date_range'                           => '<h6>' . __( 'Export Date Range', 'edforms' ) . '</h6>' . __( 'Select a date range. Setting a range will limit the export to entries submitted during that date range. If no range is set, all entries will be exported.', 'edforms' ),
	'import_select_file'                          => '<h6>' . __( 'Select Files', 'edforms' ) . '</h6>' . __( 'Click the file selection button to upload a Ed Forms export file from your computer.', 'edforms' ),
	'settings_license_key'                        => '<h6>' . __( 'Settings License Key', 'edforms' ) . '</h6>' . __( 'Your Ed Forms support license key is used to verify your support package, enable automatic updates and receive support.', 'edforms' ),
	'settings_output_css'                         => '<h6>' . __( 'Output CSS', 'edforms' ) . '</h6>' . __( 'Select yes or no to enable or disable CSS output.  Setting this to no will disable the standard Ed Forms CSS from being included in your theme.', 'edforms' ),
	'settings_html5'                              => '<h6>' . __( 'Output HTML5', 'edforms' ) . '</h6>' . __( 'Select yes or no to enable or disable HTML5 output. Setting this to no will disable the standard Ed Forms HTML5 form field output.', 'edforms' ),
	'settings_noconflict'                         => '<h6>' . __( 'No-Conflict Mode', 'edforms' ) . '</h6>' . __( 'Select On or Off to enable or disable no-conflict mode. Setting this to On will prevent extraneous scripts and styles from being printed on Ed Forms admin pages, reducing conflicts with other plugins and themes.', 'edforms' ),
	'settings_recaptcha_public'                   => '<h6>' . __( 'reCAPTCHA Site Key', 'edforms' ) . '</h6>' . __( 'Enter your reCAPTCHA Site Key, if you do not have a key you can register for one at the provided link.  reCAPTCHA is a free service.', 'edforms' ),
	'settings_recaptcha_private'                  => '<h6>' . __( 'reCAPTCHA Secret Key', 'edforms' ) . '</h6>' . __( 'Enter your reCAPTCHA Secret Key, if you do not have a key you can register for one at the provided link.  reCAPTCHA is a free service.', 'edforms' ),
	'settings_currency'                           => '<h6>' . __( 'Currency', 'edforms' ) . '</h6>' . __( 'Please select the currency for your location.  Currency is used for pricing fields and price calculations.', 'edforms' ),
	'settings_akismet'                            => '<h6>' . __( 'Akismet Integration', 'edforms' ) . '</h6>' . __( 'Protect your form entries from spam using Akismet.', 'edforms' ),
	'entries_conversion'                          => '<h6>' . __( 'Entries Conversion', 'edforms' ) . '</h6>' . __( 'Conversion is the percentage of form views that generated an entry. If a form was viewed twice, and one entry was generated, the conversion will be 50%.', 'edforms' ),
	'widget_tabindex'                             => '<h6>' . __( 'Tab Index Start Value', 'edforms' ) . '</h6>' . __( 'If you have other forms on the page (i.e. Comments Form), specify a higher tabindex start value so that your Ed Form does not end up with the same tabindices as your other forms. To disable the tabindex, enter 0 (zero).', 'edforms' ),
	'notification_override_email'                 => '<h6>' . __( 'Override Notifications', 'edforms' ) . '</h6>' . __( 'Enter a comma separated list of email addresses you would like to receive the selected notification emails.', 'edforms' ),
	'form_percentage_confirmation_display'        => '<h6>' . __( 'Progress Bar Confirmation Display', 'edforms' ) . '</h6>' . __( 'Check this box if you would like the progress bar to display with the confirmation text.', 'edforms' ),
	'percentage_confirmation_page_name'           => '<h6>' . __( 'Progress Bar Completion Text', 'edforms' ) . '</h6>' . __( 'Enter text to display at the top of the progress bar.', 'edforms' ),
	'form_field_rich_text_editor'                 => '<h6>' . __( 'Use Rich Text Editor', 'edforms' ) . '</h6>' . __( 'Check this box if you would like to use the rich text editor for this field.', 'edforms' ),

);

/**
 * Displays the tooltip
 *
 * @global $__gf_tooltips
 *
 * @param string $name      The name of the tooltip to be displayed
 * @param string $css_class Optional. The CSS class to apply toi the element. Defaults to empty string.
 * @param bool   $return    Optional. If the tooltip should be returned instead of output. Defaults to false (output)
 *
 * @return string
 */
function gform_tooltip( $name, $css_class = '', $return = false ) {
	global $__gf_tooltips; //declared as global to improve WPML performance

	$css_class     = empty( $css_class ) ? 'tooltip' : $css_class;
	/**
	 * Filters the tooltips available
	 *
	 * @param array $__gf_tooltips Array containing the available tooltips
	 */
	$__gf_tooltips = apply_filters( 'gform_tooltips', $__gf_tooltips );

	//AC: the $name parameter is a key when it has only one word. Maybe try to improve this later.
	$parameter_is_key = count( explode( ' ', $name ) ) == 1;

	$tooltip_text  = $parameter_is_key ? rgar( $__gf_tooltips, $name ) : $name;
	$tooltip_class = isset( $__gf_tooltips[ $name ] ) ? "tooltip_{$name}" : '';
	$tooltip_class = esc_attr( $tooltip_class );

	if ( empty( $tooltip_text ) ) {
		return '';
	}

	$tooltip = "<a href='#' onclick='return false;' onkeypress='return false;' class='gf_tooltip " . esc_attr( $css_class ) . " {$tooltip_class}' title='" . esc_attr( $tooltip_text ) . "'><i class='fa fa-question-circle'></i></a>";

	if ( $return ) {
		return $tooltip;
	} else {
		echo $tooltip;
	}
}
