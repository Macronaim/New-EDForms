<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}


class GF_Field_Website extends GF_Field {

	public $type = 'website';

	public function get_form_editor_field_title() {
		return esc_attr__( 'Website', 'edforms' );
	}

	function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'size_setting',
			'rules_setting',
			'visibility_setting',
			'duplicate_setting',
			'default_value_setting',
			'placeholder_setting',
			'description_setting',
			'css_class_setting',
		);
	}

	public function is_conditional_logic_supported() {
		return true;
	}

	public function validate( $value, $form ) {
		if ( empty( $value ) || $value == 'http://' ) {
			$value = '';
			if ( $this->isRequired ) {
				$this->failed_validation  = true;
				$this->validation_message = empty( $this->errorMessage ) ? esc_html__( 'This field is required.', 'edforms' ) : $this->errorMessage;
			}
		}

		if ( ! empty( $value ) && ! GFCommon::is_valid_url( $value ) ) {
			$this->failed_validation  = true;
			$this->validation_message = empty( $this->errorMessage ) ? esc_html__( 'Please enter a valid Website URL (e.g. http://www.edconcept24.fr).', 'edforms' ) : $this->errorMessage;
		}
	}

	public function get_field_input( $form, $value = '', $entry = null ) {
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$form_id  = $form['id'];
		$id       = intval( $this->id );
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		$size            = $this->size;
		$disabled_text   = $is_form_editor ? "disabled='disabled'" : '';
		$class_suffix    = $is_entry_detail ? '_admin' : '';
		$class           = $size . $class_suffix;
		$is_html5        = RGFormsModel::is_html5_enabled();
		$html_input_type = $is_html5 ? 'url' : 'text';

		$max_length = is_numeric( $this->maxLength ) ? "maxlength='{$this->maxLength}'" : '';

		$logic_event = $this->get_conditional_logic_event( 'keyup' );

		$placeholder_attribute = $this->get_field_placeholder_attribute();
		$required_attribute    = $this->isRequired ? 'aria-required="true"' : '';
		$invalid_attribute     = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';

		$tabindex = $this->get_tabindex();
		$value    = esc_attr( $value );
		$class    = esc_attr( $class );

		return "<div class='ginput_container ginput_container_website'>
                    <input name='input_{$id}' id='{$field_id}' type='$html_input_type' value='{$value}' class='{$class}' {$max_length} {$tabindex} {$logic_event} {$disabled_text} {$placeholder_attribute} {$required_attribute} {$invalid_attribute}/>
                </div>";
	}

	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {
		$safe_value = esc_url( $value );
		return GFCommon::is_valid_url( $value ) && $format == 'html' ? "<a href='$safe_value' target='_blank'>$safe_value</a>" : $safe_value;
	}

	public function get_value_save_entry( $value, $form, $input_name, $lead_id, $lead ) {

		if ( $value == 'http://' ) {
			$value = '';
		}

		return filter_var( $value, FILTER_VALIDATE_URL );
	}
}

GF_Fields::register( new GF_Field_Website() );
