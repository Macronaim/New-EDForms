<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

class GF_Field_Address extends GF_Field {

	public $type = 'address';

	function get_form_editor_field_settings() {
		return array(
			'conditional_logic_field_setting',
			'prepopulate_field_setting',
			'error_message_setting',
			'label_setting',
			'admin_label_setting',
			'label_placement_setting',
			'sub_label_placement_setting',
			'default_input_values_setting',
			'input_placeholders_setting',
			'address_setting',
			'rules_setting',
			'copy_values_option',
			'description_setting',
			'visibility_setting',
			'css_class_setting',
		);
	}

	public function is_conditional_logic_supported() {
		return true;
	}

	public function get_form_editor_field_title() {
		return esc_attr__( 'Address', 'edforms' );
	}

	function validate( $value, $form ) {

		if ( $this->isRequired ) {
			$copy_values_option_activated = $this->enableCopyValuesOption && rgpost( 'input_' . $this->id . '_copy_values_activated' );
			if ( $copy_values_option_activated ) {
				// validation will occur in the source field
				return;
			}

			$street  = rgpost( 'input_' . $this->id . '_1' );
			$city    = rgpost( 'input_' . $this->id . '_3' );
			$state   = rgpost( 'input_' . $this->id . '_4' );
			$zip     = rgpost( 'input_' . $this->id . '_5' );
			$country = rgpost( 'input_' . $this->id . '_6' );

			if ( empty( $street ) && ! $this->get_input_property( $this->id . '.1', 'isHidden' )
			     || empty( $city ) && ! $this->get_input_property( $this->id . '.3', 'isHidden' )
			     || empty( $zip ) && ! $this->get_input_property( $this->id . '.5', 'isHidden' )
			     || ( empty( $state ) && ! ( $this->hideState || $this->get_input_property( $this->id . '.4', 'isHidden' ) ) )
			     || ( empty( $country ) && ! ( $this->hideCountry || $this->get_input_property( $this->id . '.6', 'isHidden' ) ) )
			) {
				$this->failed_validation  = true;
				$this->validation_message = empty( $this->errorMessage ) ? esc_html__( 'This field is required. Please enter a complete address.', 'edforms' ) : $this->errorMessage;
			}
		}
	}

	public function get_value_submission( $field_values, $get_from_post_global_var = true ) {

		$value                                         = parent::get_value_submission( $field_values, $get_from_post_global_var );
		$value[ $this->id . '_copy_values_activated' ] = (bool) rgpost( 'input_' . $this->id . '_copy_values_activated' );

		return $value;
	}

	public function get_field_input( $form, $value = '', $entry = null ) {

		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$is_admin        = $is_entry_detail || $is_form_editor;

		$form_id  = absint( $form['id'] );
		$id       = intval( $this->id );
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";
		$form_id  = ( $is_entry_detail || $is_form_editor ) && empty( $form_id ) ? rgget( 'id' ) : $form_id;

		$disabled_text = $is_form_editor ? "disabled='disabled'" : '';
		$class_suffix  = $is_entry_detail ? '_admin' : '';

		$form_sub_label_placement  = rgar( $form, 'subLabelPlacement' );
		$field_sub_label_placement = $this->subLabelPlacement;
		$is_sub_label_above        = $field_sub_label_placement == 'above' || ( empty( $field_sub_label_placement ) && $form_sub_label_placement == 'above' );
		$sub_label_class_attribute = $field_sub_label_placement == 'hidden_label' ? "class='hidden_sub_label screen-reader-text'" : '';

		$street_value  = '';
		$street2_value = '';
		$city_value    = '';
		$state_value   = '';
		$zip_value     = '';
		$country_value = '';

		if ( is_array( $value ) ) {
			$street_value  = esc_attr( rgget( $this->id . '.1', $value ) );
			$street2_value = esc_attr( rgget( $this->id . '.2', $value ) );
			$city_value    = esc_attr( rgget( $this->id . '.3', $value ) );
			$state_value   = esc_attr( rgget( $this->id . '.4', $value ) );
			$zip_value     = esc_attr( rgget( $this->id . '.5', $value ) );
			$country_value = esc_attr( rgget( $this->id . '.6', $value ) );
		}

		// Inputs.
		$address_street_field_input  = GFFormsModel::get_input( $this, $this->id . '.1' );
		$address_street2_field_input = GFFormsModel::get_input( $this, $this->id . '.2' );
		$address_city_field_input    = GFFormsModel::get_input( $this, $this->id . '.3' );
		$address_state_field_input   = GFFormsModel::get_input( $this, $this->id . '.4' );
		$address_zip_field_input     = GFFormsModel::get_input( $this, $this->id . '.5' );
		$address_country_field_input = GFFormsModel::get_input( $this, $this->id . '.6' );

		// Placeholders.
		$street_placeholder_attribute  = GFCommon::get_input_placeholder_attribute( $address_street_field_input );
		$street2_placeholder_attribute = GFCommon::get_input_placeholder_attribute( $address_street2_field_input );
		$city_placeholder_attribute    = GFCommon::get_input_placeholder_attribute( $address_city_field_input );
		$zip_placeholder_attribute     = GFCommon::get_input_placeholder_attribute( $address_zip_field_input );

		$address_types = $this->get_address_types( $form_id );
		$addr_type     = empty( $this->addressType ) ? $this->get_default_address_type( $form_id ) : $this->addressType;
		$address_type  = rgar( $address_types, $addr_type );

		$state_label  = empty( $address_type['state_label'] ) ? esc_html__( 'State', 'edforms' ) : $address_type['state_label'];
		$zip_label    = empty( $address_type['zip_label'] ) ? esc_html__( 'Zip Code', 'edforms' ) : $address_type['zip_label'];
		$hide_country = ! empty( $address_type['country'] ) || $this->hideCountry || rgar( $address_country_field_input, 'isHidden' );

		if ( empty( $country_value ) ) {
			$country_value = $this->defaultCountry;
		}

		if ( empty( $state_value ) ) {
			$state_value = $this->defaultState;
		}

		$country_placeholder = GFCommon::get_input_placeholder_value( $address_country_field_input );
		$country_list        = $this->get_country_dropdown( $country_value, $country_placeholder );

		// Changing css classes based on field format to ensure proper display.
		$address_display_format = apply_filters( 'gform_address_display_format', 'default', $this );
		$city_location          = $address_display_format == 'zip_before_city' ? 'right' : 'left';
		$zip_location           = $address_display_format != 'zip_before_city' && ( $this->hideState || rgar( $address_state_field_input, 'isHidden' ) ) ? 'right' : 'left'; // support for $this->hideState legacy property
		$state_location         = $address_display_format == 'zip_before_city' ? 'left' : 'right';
		$country_location       = $this->hideState || rgar( $address_state_field_input, 'isHidden' ) ? 'left' : 'right'; // support for $this->hideState legacy property

		// Labels.
		$address_street_sub_label  = rgar( $address_street_field_input, 'customLabel' ) != '' ? $address_street_field_input['customLabel'] : esc_html__( 'Street Address', 'edforms' );
		$address_street_sub_label  = gf_apply_filters( array( 'gform_address_street', $form_id, $this->id ), $address_street_sub_label, $form_id );
		$address_street_sub_label  = esc_html( $address_street_sub_label );
		$address_street2_sub_label = rgar( $address_street2_field_input, 'customLabel' ) != '' ? $address_street2_field_input['customLabel'] : esc_html__( 'Address Line 2', 'edforms' );
		$address_street2_sub_label = gf_apply_filters( array( 'gform_address_street2', $form_id, $this->id ), $address_street2_sub_label, $form_id );
		$address_street2_sub_label = esc_html( $address_street2_sub_label );
		$address_zip_sub_label     = rgar( $address_zip_field_input, 'customLabel' ) != '' ? $address_zip_field_input['customLabel'] : $zip_label;
		$address_zip_sub_label     = gf_apply_filters( array( 'gform_address_zip', $form_id, $this->id ), $address_zip_sub_label, $form_id );
		$address_zip_sub_label     = esc_html( $address_zip_sub_label );
		$address_city_sub_label    = rgar( $address_city_field_input, 'customLabel' ) != '' ? $address_city_field_input['customLabel'] : esc_html__( 'City', 'edforms' );
		$address_city_sub_label    = gf_apply_filters( array( 'gform_address_city', $form_id, $this->id ), $address_city_sub_label, $form_id );
		$address_city_sub_label    = esc_html( $address_city_sub_label );
		$address_state_sub_label   = rgar( $address_state_field_input, 'customLabel' ) != '' ? $address_state_field_input['customLabel'] : $state_label;
		$address_state_sub_label   = gf_apply_filters( array( 'gform_address_state', $form_id, $this->id ), $address_state_sub_label, $form_id );
		$address_state_sub_label   = esc_html( $address_state_sub_label );
		$address_country_sub_label = rgar( $address_country_field_input, 'customLabel' ) != '' ? $address_country_field_input['customLabel'] : esc_html__( 'Country', 'edforms' );
		$address_country_sub_label = gf_apply_filters( array( 'gform_address_country', $form_id, $this->id ), $address_country_sub_label, $form_id );
		$address_country_sub_label = esc_html( $address_country_sub_label );

		// Address field.
		$street_address = '';
		$tabindex       = $this->get_tabindex();
		$style          = ( $is_admin && rgar( $address_street_field_input, 'isHidden' ) ) ? "style='display:none;'" : '';
		if ( $is_admin || ! rgar( $address_street_field_input, 'isHidden' ) ) {
			if ( $is_sub_label_above ) {
				$street_address = " <span class='ginput_full{$class_suffix} address_line_1' id='{$field_id}_1_container' {$style}>
                                        <label for='{$field_id}_1' id='{$field_id}_1_label' {$sub_label_class_attribute}>{$address_street_sub_label}</label>
                                        <input type='text' name='input_{$id}.1' id='{$field_id}_1' value='{$street_value}' {$tabindex} {$disabled_text} {$street_placeholder_attribute}/>
                                    </span>";
			} else {
				$street_address = " <span class='ginput_full{$class_suffix} address_line_1' id='{$field_id}_1_container' {$style}>
                                        <input type='text' name='input_{$id}.1' id='{$field_id}_1' value='{$street_value}' {$tabindex} {$disabled_text} {$street_placeholder_attribute}/>
                                        <label for='{$field_id}_1' id='{$field_id}_1_label' {$sub_label_class_attribute}>{$address_street_sub_label}</label>
                                    </span>";
			}
		}

		// Address line 2 field.
		$street_address2 = '';
		$style           = ( $is_admin && ( $this->hideAddress2 || rgar( $address_street2_field_input, 'isHidden' ) ) ) ? "style='display:none;'" : ''; // support for $this->hideAddress2 legacy property
		if ( $is_admin || ( ! $this->hideAddress2 && ! rgar( $address_street2_field_input, 'isHidden' ) ) ) {
			$tabindex = $this->get_tabindex();
			if ( $is_sub_label_above ) {
				$street_address2 = "<span class='ginput_full{$class_suffix} address_line_2' id='{$field_id}_2_container' {$style}>
                                        <label for='{$field_id}_2' id='{$field_id}_2_label' {$sub_label_class_attribute}>{$address_street2_sub_label}</label>
                                        <input type='text' name='input_{$id}.2' id='{$field_id}_2' value='{$street2_value}' {$tabindex} {$disabled_text} {$street2_placeholder_attribute}/>
                                    </span>";
			} else {
				$street_address2 = "<span class='ginput_full{$class_suffix} address_line_2' id='{$field_id}_2_container' {$style}>
                                        <input type='text' name='input_{$id}.2' id='{$field_id}_2' value='{$street2_value}' {$tabindex} {$disabled_text} {$street2_placeholder_attribute}/>
                                        <label for='{$field_id}_2' id='{$field_id}_2_label' {$sub_label_class_attribute}>{$address_street2_sub_label}</label>
                                    </span>";
			}
		}

		if ( $address_display_format == 'zip_before_city' ) {
			// Zip field.
			$zip      = '';
			$tabindex = $this->get_tabindex();
			$style    = ( $is_admin && rgar( $address_zip_field_input, 'isHidden' ) ) ? "style='display:none;'" : '';
			if ( $is_admin || ! rgar( $address_zip_field_input, 'isHidden' ) ) {
				if ( $is_sub_label_above ) {
					$zip = "<span class='ginput_{$zip_location}{$class_suffix} address_zip' id='{$field_id}_5_container' {$style}>
                                    <label for='{$field_id}_5' id='{$field_id}_5_label' {$sub_label_class_attribute}>{$address_zip_sub_label}</label>
                                    <input type='text' name='input_{$id}.5' id='{$field_id}_5' value='{$zip_value}' {$tabindex} {$disabled_text} {$zip_placeholder_attribute}/>
                                </span>";
				} else {
					$zip = "<span class='ginput_{$zip_location}{$class_suffix} address_zip' id='{$field_id}_5_container' {$style}>
                                    <input type='text' name='input_{$id}.5' id='{$field_id}_5' value='{$zip_value}' {$tabindex} {$disabled_text} {$zip_placeholder_attribute}/>
                                    <label for='{$field_id}_5' id='{$field_id}_5_label' {$sub_label_class_attribute}>{$address_zip_sub_label}</label>
                                </span>";
				}
			}

			// City field.
			$city     = '';
			$tabindex = $this->get_tabindex();
			$style    = ( $is_admin && rgar( $address_city_field_input, 'isHidden' ) ) ? "style='display:none;'" : '';
			if ( $is_admin || ! rgar( $address_city_field_input, 'isHidden' ) ) {
				if ( $is_sub_label_above ) {
					$city = "<span class='ginput_{$city_location}{$class_suffix} address_city' id='{$field_id}_3_container' {$style}>
                                    <label for='{$field_id}_3' id='{$field_id}_3_label' {$sub_label_class_attribute}>{$address_city_sub_label}</label>
                                    <input type='text' name='input_{$id}.3' id='{$field_id}_3' value='{$city_value}' {$tabindex} {$disabled_text} {$city_placeholder_attribute}/>
                                 </span>";
				} else {
					$city = "<span class='ginput_{$city_location}{$class_suffix} address_city' id='{$field_id}_3_container' {$style}>
                                    <input type='text' name='input_{$id}.3' id='{$field_id}_3' value='{$city_value}' {$tabindex} {$disabled_text} {$city_placeholder_attribute}/>
                                    <label for='{$field_id}_3' id='{$field_id}_3_label' {$sub_label_class_attribute}>{$address_city_sub_label}</label>
                                 </span>";
				}
			}

			// State field.
			$style = ( $is_admin && ( $this->hideState || rgar( $address_state_field_input, 'isHidden' ) ) ) ? "style='display:none;'" : ''; // support for $this->hideState legacy property
			if ( $is_admin || ( ! $this->hideState && ! rgar( $address_state_field_input, 'isHidden' ) ) ) {
				$state_field = $this->get_state_field( $id, $field_id, $state_value, $disabled_text, $form_id );
				if ( $is_sub_label_above ) {
					$state = "<span class='ginput_{$state_location}{$class_suffix} address_state' id='{$field_id}_4_container' {$style}>
                                           <label for='{$field_id}_4' id='{$field_id}_4_label' {$sub_label_class_attribute}>{$address_state_sub_label}</label>
                                           $state_field
                                      </span>";
				} else {
					$state = "<span class='ginput_{$state_location}{$class_suffix} address_state' id='{$field_id}_4_container' {$style}>
                                           $state_field
                                           <label for='{$field_id}_4' id='{$field_id}_4_label' {$sub_label_class_attribute}>{$address_state_sub_label}</label>
                                      </span>";
				}
			} else {
				$state = sprintf( "<input type='hidden' class='gform_hidden' name='input_%d.4' id='%s_4' value='%s'/>", $id, $field_id, $state_value );
			}
		} else {

			// City field.
			$city     = '';
			$tabindex = $this->get_tabindex();
			$style    = ( $is_admin && rgar( $address_city_field_input, 'isHidden' ) ) ? "style='display:none;'" : '';
			if ( $is_admin || ! rgar( $address_city_field_input, 'isHidden' ) ) {
				if ( $is_sub_label_above ) {
					$city = "<span class='ginput_{$city_location}{$class_suffix} address_city' id='{$field_id}_3_container' {$style}>
                                    <label for='{$field_id}_3' id='{$field_id}_3_label' {$sub_label_class_attribute}>{$address_city_sub_label}</label>
                                    <input type='text' name='input_{$id}.3' id='{$field_id}_3' value='{$city_value}' {$tabindex} {$disabled_text} {$city_placeholder_attribute}/>
                                 </span>";
				} else {
					$city = "<span class='ginput_{$city_location}{$class_suffix} address_city' id='{$field_id}_3_container' {$style}>
                                    <input type='text' name='input_{$id}.3' id='{$field_id}_3' value='{$city_value}' {$tabindex} {$disabled_text} {$city_placeholder_attribute}/>
                                    <label for='{$field_id}_3' id='{$field_id}_3_label' {$sub_label_class_attribute}>{$address_city_sub_label}</label>
                                 </span>";
				}
			}

			// State field.
			$style = ( $is_admin && ( $this->hideState || rgar( $address_state_field_input, 'isHidden' ) ) ) ? "style='display:none;'" : ''; // support for $this->hideState legacy property
			if ( $is_admin || ( ! $this->hideState && ! rgar( $address_state_field_input, 'isHidden' ) ) ) {
				$state_field = $this->get_state_field( $id, $field_id, $state_value, $disabled_text, $form_id );
				if ( $is_sub_label_above ) {
					$state = "<span class='ginput_{$state_location}{$class_suffix} address_state' id='{$field_id}_4_container' {$style}>
                                        <label for='{$field_id}_4' id='{$field_id}_4_label' {$sub_label_class_attribute}>$address_state_sub_label</label>
                                        $state_field
                                      </span>";
				} else {
					$state = "<span class='ginput_{$state_location}{$class_suffix} address_state' id='{$field_id}_4_container' {$style}>
                                        $state_field
                                        <label for='{$field_id}_4' id='{$field_id}_4_label' {$sub_label_class_attribute}>$address_state_sub_label</label>
                                      </span>";
				}
			} else {
				$state = sprintf( "<input type='hidden' class='gform_hidden' name='input_%d.4' id='%s_4' value='%s'/>", $id, $field_id, $state_value );
			}

			// Zip field.
			$zip      = '';
			$tabindex = GFCommon::get_tabindex();
			$style    = ( $is_admin && rgar( $address_zip_field_input, 'isHidden' ) ) ? "style='display:none;'" : '';
			if ( $is_admin || ! rgar( $address_zip_field_input, 'isHidden' ) ) {
				if ( $is_sub_label_above ) {
					$zip = "<span class='ginput_{$zip_location}{$class_suffix} address_zip' id='{$field_id}_5_container' {$style}>
                                    <label for='{$field_id}_5' id='{$field_id}_5_label' {$sub_label_class_attribute}>{$address_zip_sub_label}</label>
                                    <input type='text' name='input_{$id}.5' id='{$field_id}_5' value='{$zip_value}' {$tabindex} {$disabled_text} {$zip_placeholder_attribute}/>
                                </span>";
				} else {
					$zip = "<span class='ginput_{$zip_location}{$class_suffix} address_zip' id='{$field_id}_5_container' {$style}>
                                    <input type='text' name='input_{$id}.5' id='{$field_id}_5' value='{$zip_value}' {$tabindex} {$disabled_text} {$zip_placeholder_attribute}/>
                                    <label for='{$field_id}_5' id='{$field_id}_5_label' {$sub_label_class_attribute}>{$address_zip_sub_label}</label>
                                </span>";
				}
			}
		}

		if ( $is_admin || ! $hide_country ) {
			$style    = $hide_country ? "style='display:none;'" : '';
			$tabindex = $this->get_tabindex();
			if ( $is_sub_label_above ) {
				$country = "<span class='ginput_{$country_location}{$class_suffix} address_country' id='{$field_id}_6_container' {$style}>
                                        <label for='{$field_id}_6' id='{$field_id}_6_label' {$sub_label_class_attribute}>{$address_country_sub_label}</label>
                                        <select name='input_{$id}.6' id='{$field_id}_6' {$tabindex} {$disabled_text}>{$country_list}</select>
                                    </span>";
			} else {
				$country = "<span class='ginput_{$country_location}{$class_suffix} address_country' id='{$field_id}_6_container' {$style}>
                                        <select name='input_{$id}.6' id='{$field_id}_6' {$tabindex} {$disabled_text}>{$country_list}</select>
                                        <label for='{$field_id}_6' id='{$field_id}_6_label' {$sub_label_class_attribute}>{$address_country_sub_label}</label>
                                    </span>";
			}
		} else {
			$country = sprintf( "<input type='hidden' class='gform_hidden' name='input_%d.6' id='%s_6' value='%s'/>", $id, $field_id, $country_value );
		}

		$inputs = $address_display_format == 'zip_before_city' ? $street_address . $street_address2 . $zip . $city . $state . $country : $street_address . $street_address2 . $city . $state . $zip . $country;

		$copy_values_option = '';
		$input_style        = '';
		if ( ( $this->enableCopyValuesOption || $is_form_editor ) && ! $is_entry_detail ) {
			$copy_values_style      = $is_form_editor && ! $this->enableCopyValuesOption ? "style='display:none;'" : '';
			$copy_values_is_checked = isset( $value[$this->id . '_copy_values_activated'] ) ? $value[$this->id . '_copy_values_activated'] == true : $this->copyValuesOptionDefault == true;
			$copy_values_checked    = checked( true, $copy_values_is_checked, false );
			$copy_values_option     = "<div id='{$field_id}_copy_values_option_container' class='copy_values_option_container' {$copy_values_style}>
                                        <input type='checkbox' id='{$field_id}_copy_values_activated' class='copy_values_activated' value='1' name='input_{$id}_copy_values_activated' {$disabled_text} {$copy_values_checked}/>
                                        <label for='{$field_id}_copy_values_activated' id='{$field_id}_copy_values_option_label' class='copy_values_option_label inline'>{$this->copyValuesOptionLabel}</label>
                                    </div>";
			if ( $copy_values_is_checked ) {
				$input_style = "style='display:none;'";
			}
		}

		$css_class = $this->get_css_class();

		return "    {$copy_values_option}
                    <div class='ginput_complex{$class_suffix} ginput_container {$css_class} gfield_trigger_change' id='$field_id' {$input_style}>
                        {$inputs}
                    <div class='gf_clear gf_clear_complex'></div>
                </div>";
	}

	public function get_field_label_class(){
		return 'gfield_label gfield_label_before_complex';
	}

	public function get_css_class() {

		$address_street_field_input  = GFFormsModel::get_input( $this, $this->id . '.1' );
		$address_street2_field_input = GFFormsModel::get_input( $this, $this->id . '.2' );
		$address_city_field_input    = GFFormsModel::get_input( $this, $this->id . '.3' );
		$address_state_field_input   = GFFormsModel::get_input( $this, $this->id . '.4' );
		$address_zip_field_input     = GFFormsModel::get_input( $this, $this->id . '.5' );
		$address_country_field_input = GFFormsModel::get_input( $this, $this->id . '.6' );

		$css_class = '';
		if ( ! rgar( $address_street_field_input, 'isHidden' ) ) {
			$css_class .= 'has_street ';
		}
		if ( ! rgar( $address_street2_field_input, 'isHidden' ) ) {
			$css_class .= 'has_street2 ';
		}
		if ( ! rgar( $address_city_field_input, 'isHidden' ) ) {
			$css_class .= 'has_city ';
		}
		if ( ! rgar( $address_state_field_input, 'isHidden' ) ) {
			$css_class .= 'has_state ';
		}
		if ( ! rgar( $address_zip_field_input, 'isHidden' ) ) {
			$css_class .= 'has_zip ';
		}
		if ( ! rgar( $address_country_field_input, 'isHidden' ) ) {
			$css_class .= 'has_country ';
		}

		$css_class .= 'ginput_container_address';

		return trim( $css_class );
	}

	public function get_address_types( $form_id ) {

		$addressTypes = array(
			'international' => array( 'label'       => esc_html__( 'International', 'edforms' ),
			                          'zip_label'   => gf_apply_filters( array( 'gform_address_zip', $form_id ), esc_html__( 'ZIP / Postal Code', 'edforms' ), $form_id ),
			                          'state_label' => gf_apply_filters( array( 'gform_address_state', $form_id ), esc_html__( 'State / Province / Region', 'edforms' ), $form_id )
			),
			'us'            => array(
				'label'       => esc_html__( 'United States', 'edforms' ),
				'zip_label'   => gf_apply_filters( array( 'gform_address_zip', $form_id ), esc_html__( 'ZIP Code', 'edforms' ), $form_id ),
				'state_label' => gf_apply_filters( array( 'gform_address_state', $form_id ), esc_html__( 'State', 'edforms' ), $form_id ),
				'country'     => 'United States',
				'states'      => array_merge( array( '' ), $this->get_us_states() )
			),
			'canadian'      => array(
				'label'       => esc_html__( 'Canadian', 'edforms' ),
				'zip_label'   => gf_apply_filters( array( 'gform_address_zip', $form_id ), esc_html__( 'Postal Code', 'edforms' ), $form_id ),
				'state_label' => gf_apply_filters( array( 'gform_address_state', $form_id ), esc_html__( 'Province', 'edforms' ), $form_id ),
				'country'     => 'Canada',
				'states'      => array_merge( array( '' ), $this->get_canadian_provinces() )
			)
		);

		/**
		 * Filters the address types available.
		 *
		 * @since Unknown
		 *
		 * @param array $addressTypes Contains the details for existing address types.
		 * @param int   $form_id      The form ID.
		 */
		return gf_apply_filters( array( 'gform_address_types', $form_id ), $addressTypes, $form_id );
	}

	/**
	 * Retrieve the default address type for this field.
	 *
	 * @param int $form_id The current form ID.
	 *
	 * @return string
	 */
	public function get_default_address_type( $form_id ) {
		$default_address_type = 'international';

		/**
		 * Allow the default address type to be overridden.
		 *
		 * @param string $default_address_type The default address type of international.
		 */
		$default_address_type = apply_filters( 'gform_default_address_type', $default_address_type, $form_id );

		return apply_filters( 'gform_default_address_type_' . $form_id, $default_address_type, $form_id );
	}

	public function get_state_field( $id, $field_id, $state_value, $disabled_text, $form_id ) {

		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();
		$is_admin        = $is_entry_detail || $is_form_editor;


		$state_dropdown_class = $state_text_class = $state_style = $text_style = $state_field_id = '';

		if ( empty( $state_value ) ) {
			$state_value = $this->defaultState;

			// For backwards compatibility (Canadian address type used to store the default state into the defaultProvince property).
			if ( $this->addressType == 'canadian' && ! empty( $this->defaultProvince ) ) {
				$state_value = $this->defaultProvince;
			}
		}

		$address_type        = empty( $this->addressType ) ? $this->get_default_address_type( $form_id ) : $this->addressType;
		$address_types       = $this->get_address_types( $form_id );
		$has_state_drop_down = isset( $address_types[ $address_type ]['states'] ) && is_array( $address_types[ $address_type ]['states'] );

		if ( $is_admin && rgget('view') != 'entry' ) {
			$state_dropdown_class = "class='state_dropdown'";
			$state_text_class     = "class='state_text'";
			$state_style          = ! $has_state_drop_down ? "style='display:none;'" : '';
			$text_style           = $has_state_drop_down ? "style='display:none;'" : '';
			$state_field_id       = '';
		} else {
			// ID only displayed on front end.
			$state_field_id = "id='" . $field_id . "_4'";
		}

		$tabindex         = $this->get_tabindex();
		$state_input      = GFFormsModel::get_input( $this, $this->id . '.4' );
		$sate_placeholder = GFCommon::get_input_placeholder_value( $state_input );
		$states           = empty( $address_types[ $address_type ]['states'] ) ? array() : $address_types[ $address_type ]['states'];
		$state_dropdown   = sprintf( "<select name='input_%d.4' %s $tabindex %s $state_dropdown_class $state_style>%s</select>", $id, $state_field_id, $disabled_text, $this->get_state_dropdown( $states, $state_value, $sate_placeholder ) );

		$tabindex                    = $this->get_tabindex();
		$state_placeholder_attribute = GFCommon::get_input_placeholder_attribute( $state_input );
		$state_text                  = sprintf( "<input type='text' name='input_%d.4' %s value='%s' {$tabindex} %s {$state_text_class} {$text_style} {$state_placeholder_attribute}/>", $id, $state_field_id, $state_value, $disabled_text );

		if ( $is_admin && rgget('view') != 'entry' ) {
			return $state_dropdown . $state_text;
		} elseif ( $has_state_drop_down ) {
			return $state_dropdown;
		} else {
			return $state_text;
		}
	}

	public function get_countries() {
		return apply_filters(
			'gform_countries', array(
				esc_html__( 'Afghanistan', 'edforms' ),
				esc_html__( 'Albania', 'edforms' ),
				esc_html__( 'Algeria', 'edforms' ),
				esc_html__( 'American Samoa', 'edforms' ),
				esc_html__( 'Andorra', 'edforms' ),
				esc_html__( 'Angola', 'edforms' ),
				esc_html__( 'Antigua and Barbuda', 'edforms' ),
				esc_html__( 'Argentina', 'edforms' ),
				esc_html__( 'Armenia', 'edforms' ),
				esc_html__( 'Australia', 'edforms' ),
				esc_html__( 'Austria', 'edforms' ),
				esc_html__( 'Azerbaijan', 'edforms' ),
				esc_html__( 'Bahamas', 'edforms' ),
				esc_html__( 'Bahrain', 'edforms' ),
				esc_html__( 'Bangladesh', 'edforms' ),
				esc_html__( 'Barbados', 'edforms' ),
				esc_html__( 'Belarus', 'edforms' ),
				esc_html__( 'Belgium', 'edforms' ),
				esc_html__( 'Belize', 'edforms' ),
				esc_html__( 'Benin', 'edforms' ),
				esc_html__( 'Bermuda', 'edforms' ),
				esc_html__( 'Bhutan', 'edforms' ),
				esc_html__( 'Bolivia', 'edforms' ),
				esc_html__( 'Bosnia and Herzegovina', 'edforms' ),
				esc_html__( 'Botswana', 'edforms' ),
				esc_html__( 'Brazil', 'edforms' ),
				esc_html__( 'Brunei', 'edforms' ),
				esc_html__( 'Bulgaria', 'edforms' ),
				esc_html__( 'Burkina Faso', 'edforms' ),
				esc_html__( 'Burundi', 'edforms' ),
				esc_html__( 'Cambodia', 'edforms' ),
				esc_html__( 'Cameroon', 'edforms' ),
				esc_html__( 'Canada', 'edforms' ),
				esc_html__( 'Cape Verde', 'edforms' ),
				esc_html__( 'Cayman Islands', 'edforms' ),
				esc_html__( 'Central African Republic', 'edforms' ),
				esc_html__( 'Chad', 'edforms' ),
				esc_html__( 'Chile', 'edforms' ),
				esc_html__( 'China', 'edforms' ),
				esc_html__( 'Colombia', 'edforms' ),
				esc_html__( 'Comoros', 'edforms' ),
				esc_html__( 'Congo, Democratic Republic of the', 'edforms' ),
				esc_html__( 'Congo, Republic of the', 'edforms' ),
				esc_html__( 'Costa Rica', 'edforms' ),
				esc_html__( "Côte d'Ivoire", 'edforms' ),
				esc_html__( 'Croatia', 'edforms' ),
				esc_html__( 'Cuba', 'edforms' ),
				esc_html__( 'Curaçao', 'edforms' ),
				esc_html__( 'Cyprus', 'edforms' ),
				esc_html__( 'Czech Republic', 'edforms' ),
				esc_html__( 'Denmark', 'edforms' ),
				esc_html__( 'Djibouti', 'edforms' ),
				esc_html__( 'Dominica', 'edforms' ),
				esc_html__( 'Dominican Republic', 'edforms' ),
				esc_html__( 'East Timor', 'edforms' ),
				esc_html__( 'Ecuador', 'edforms' ),
				esc_html__( 'Egypt', 'edforms' ),
				esc_html__( 'El Salvador', 'edforms' ),
				esc_html__( 'Equatorial Guinea', 'edforms' ),
				esc_html__( 'Eritrea', 'edforms' ),
				esc_html__( 'Estonia', 'edforms' ),
				esc_html__( 'Ethiopia', 'edforms' ),
				esc_html__( 'Faroe Islands', 'edforms' ),
				esc_html__( 'Fiji', 'edforms' ),
				esc_html__( 'Finland', 'edforms' ),
				esc_html__( 'France', 'edforms' ),
				esc_html__( 'French Polynesia', 'edforms' ),
				esc_html__( 'Gabon', 'edforms' ),
				esc_html__( 'Gambia', 'edforms' ),
				esc_html( _x( 'Georgia', 'Country', 'edforms' ) ),
				esc_html__( 'Germany', 'edforms' ),
				esc_html__( 'Ghana', 'edforms' ),
				esc_html__( 'Greece', 'edforms' ),
				esc_html__( 'Greenland', 'edforms' ),
				esc_html__( 'Grenada', 'edforms' ),
				esc_html__( 'Guam', 'edforms' ),
				esc_html__( 'Guatemala', 'edforms' ),
				esc_html__( 'Guinea', 'edforms' ),
				esc_html__( 'Guinea-Bissau', 'edforms' ),
				esc_html__( 'Guyana', 'edforms' ),
				esc_html__( 'Haiti', 'edforms' ),
				esc_html__( 'Honduras', 'edforms' ),
				esc_html__( 'Hong Kong', 'edforms' ),
				esc_html__( 'Hungary', 'edforms' ),
				esc_html__( 'Iceland', 'edforms' ),
				esc_html__( 'India', 'edforms' ),
				esc_html__( 'Indonesia', 'edforms' ),
				esc_html__( 'Iran', 'edforms' ),
				esc_html__( 'Iraq', 'edforms' ),
				esc_html__( 'Ireland', 'edforms' ),
				esc_html__( 'Israel', 'edforms' ),
				esc_html__( 'Italy', 'edforms' ),
				esc_html__( 'Jamaica', 'edforms' ),
				esc_html__( 'Japan', 'edforms' ),
				esc_html__( 'Jordan', 'edforms' ),
				esc_html__( 'Kazakhstan', 'edforms' ),
				esc_html__( 'Kenya', 'edforms' ),
				esc_html__( 'Kiribati', 'edforms' ),
				esc_html__( 'North Korea', 'edforms' ),
				esc_html__( 'South Korea', 'edforms' ),
				esc_html__( 'Kosovo', 'edforms' ),
				esc_html__( 'Kuwait', 'edforms' ),
				esc_html__( 'Kyrgyzstan', 'edforms' ),
				esc_html__( 'Laos', 'edforms' ),
				esc_html__( 'Latvia', 'edforms' ),
				esc_html__( 'Lebanon', 'edforms' ),
				esc_html__( 'Lesotho', 'edforms' ),
				esc_html__( 'Liberia', 'edforms' ),
				esc_html__( 'Libya', 'edforms' ),
				esc_html__( 'Liechtenstein', 'edforms' ),
				esc_html__( 'Lithuania', 'edforms' ),
				esc_html__( 'Luxembourg', 'edforms' ),
				esc_html__( 'Macedonia', 'edforms' ),
				esc_html__( 'Madagascar', 'edforms' ),
				esc_html__( 'Malawi', 'edforms' ),
				esc_html__( 'Malaysia', 'edforms' ),
				esc_html__( 'Maldives', 'edforms' ),
				esc_html__( 'Mali', 'edforms' ),
				esc_html__( 'Malta', 'edforms' ),
				esc_html__( 'Marshall Islands', 'edforms' ),
				esc_html__( 'Mauritania', 'edforms' ),
				esc_html__( 'Mauritius', 'edforms' ),
				esc_html__( 'Mexico', 'edforms' ),
				esc_html__( 'Micronesia', 'edforms' ),
				esc_html__( 'Moldova', 'edforms' ),
				esc_html__( 'Monaco', 'edforms' ),
				esc_html__( 'Mongolia', 'edforms' ),
				esc_html__( 'Montenegro', 'edforms' ),
				esc_html__( 'Morocco', 'edforms' ),
				esc_html__( 'Mozambique', 'edforms' ),
				esc_html__( 'Myanmar', 'edforms' ),
				esc_html__( 'Namibia', 'edforms' ),
				esc_html__( 'Nauru', 'edforms' ),
				esc_html__( 'Nepal', 'edforms' ),
				esc_html__( 'Netherlands', 'edforms' ),
				esc_html__( 'New Zealand', 'edforms' ),
				esc_html__( 'Nicaragua', 'edforms' ),
				esc_html__( 'Niger', 'edforms' ),
				esc_html__( 'Nigeria', 'edforms' ),
				esc_html__( 'Northern Mariana Islands', 'edforms' ),
				esc_html__( 'Norway', 'edforms' ),
				esc_html__( 'Oman', 'edforms' ),
				esc_html__( 'Pakistan', 'edforms' ),
				esc_html__( 'Palau', 'edforms' ),
				esc_html__( 'Palestine, State of', 'edforms' ),
				esc_html__( 'Panama', 'edforms' ),
				esc_html__( 'Papua New Guinea', 'edforms' ),
				esc_html__( 'Paraguay', 'edforms' ),
				esc_html__( 'Peru', 'edforms' ),
				esc_html__( 'Philippines', 'edforms' ),
				esc_html__( 'Poland', 'edforms' ),
				esc_html__( 'Portugal', 'edforms' ),
				esc_html__( 'Puerto Rico', 'edforms' ),
				esc_html__( 'Qatar', 'edforms' ),
				esc_html__( 'Romania', 'edforms' ),
				esc_html__( 'Russia', 'edforms' ),
				esc_html__( 'Rwanda', 'edforms' ),
				esc_html__( 'Saint Kitts and Nevis', 'edforms' ),
				esc_html__( 'Saint Lucia', 'edforms' ),
				esc_html__( 'Saint Vincent and the Grenadines', 'edforms' ),
				esc_html__( 'Saint Martin', 'edforms' ),
				esc_html__( 'Samoa', 'edforms' ),
				esc_html__( 'San Marino', 'edforms' ),
				esc_html__( 'Sao Tome and Principe', 'edforms' ),
				esc_html__( 'Saudi Arabia', 'edforms' ),
				esc_html__( 'Senegal', 'edforms' ),
				esc_html__( 'Serbia', 'edforms' ),
				esc_html__( 'Seychelles', 'edforms' ),
				esc_html__( 'Sierra Leone', 'edforms' ),
				esc_html__( 'Singapore', 'edforms' ),
				esc_html__( 'Sint Maarten', 'edforms' ),
				esc_html__( 'Slovakia', 'edforms' ),
				esc_html__( 'Slovenia', 'edforms' ),
				esc_html__( 'Solomon Islands', 'edforms' ),
				esc_html__( 'Somalia', 'edforms' ),
				esc_html__( 'South Africa', 'edforms' ),
				esc_html__( 'Spain', 'edforms' ),
				esc_html__( 'Sri Lanka', 'edforms' ),
				esc_html__( 'Sudan', 'edforms' ),
				esc_html__( 'Sudan, South', 'edforms' ),
				esc_html__( 'Suriname', 'edforms' ),
				esc_html__( 'Swaziland', 'edforms' ),
				esc_html__( 'Sweden', 'edforms' ),
				esc_html__( 'Switzerland', 'edforms' ),
				esc_html__( 'Syria', 'edforms' ),
				esc_html__( 'Taiwan', 'edforms' ),
				esc_html__( 'Tajikistan', 'edforms' ),
				esc_html__( 'Tanzania', 'edforms' ),
				esc_html__( 'Thailand', 'edforms' ),
				esc_html__( 'Togo', 'edforms' ),
				esc_html__( 'Tonga', 'edforms' ),
				esc_html__( 'Trinidad and Tobago', 'edforms' ),
				esc_html__( 'Tunisia', 'edforms' ),
				esc_html__( 'Turkey', 'edforms' ),
				esc_html__( 'Turkmenistan', 'edforms' ),
				esc_html__( 'Tuvalu', 'edforms' ),
				esc_html__( 'Uganda', 'edforms' ),
				esc_html__( 'Ukraine', 'edforms' ),
				esc_html__( 'United Arab Emirates', 'edforms' ),
				esc_html__( 'United Kingdom', 'edforms' ),
				esc_html__( 'United States', 'edforms' ),
				esc_html__( 'Uruguay', 'edforms' ),
				esc_html__( 'Uzbekistan', 'edforms' ),
				esc_html__( 'Vanuatu', 'edforms' ),
				esc_html__( 'Vatican City', 'edforms' ),
				esc_html__( 'Venezuela', 'edforms' ),
				esc_html__( 'Vietnam', 'edforms' ),
				esc_html__( 'Virgin Islands, British', 'edforms' ),
				esc_html__( 'Virgin Islands, U.S.', 'edforms' ),
				esc_html__( 'Yemen', 'edforms' ),
				esc_html__( 'Zambia', 'edforms' ),
				esc_html__( 'Zimbabwe', 'edforms' ),
			)
		);
	}

	public function get_country_code( $country_name ) {
		$codes = $this->get_country_codes();

		return rgar( $codes, GFCommon::safe_strtoupper( $country_name ) );
	}

	public function get_country_codes() {
		$codes = array(
			esc_html__( 'AFGHANISTAN', 'edforms' )                       => 'AF',
			esc_html__( 'ALBANIA', 'edforms' )                           => 'AL',
			esc_html__( 'ALGERIA', 'edforms' )                           => 'DZ',
			esc_html__( 'AMERICAN SAMOA', 'edforms' )                    => 'AS',
			esc_html__( 'ANDORRA', 'edforms' )                           => 'AD',
			esc_html__( 'ANGOLA', 'edforms' )                            => 'AO',
			esc_html__( 'ANTIGUA AND BARBUDA', 'edforms' )               => 'AG',
			esc_html__( 'ARGENTINA', 'edforms' )                         => 'AR',
			esc_html__( 'ARMENIA', 'edforms' )                           => 'AM',
			esc_html__( 'AUSTRALIA', 'edforms' )                         => 'AU',
			esc_html__( 'AUSTRIA', 'edforms' )                           => 'AT',
			esc_html__( 'AZERBAIJAN', 'edforms' )                        => 'AZ',
			esc_html__( 'BAHAMAS', 'edforms' )                           => 'BS',
			esc_html__( 'BAHRAIN', 'edforms' )                           => 'BH',
			esc_html__( 'BANGLADESH', 'edforms' )                        => 'BD',
			esc_html__( 'BARBADOS', 'edforms' )                          => 'BB',
			esc_html__( 'BELARUS', 'edforms' )                           => 'BY',
			esc_html__( 'BELGIUM', 'edforms' )                           => 'BE',
			esc_html__( 'BELIZE', 'edforms' )                            => 'BZ',
			esc_html__( 'BENIN', 'edforms' )                             => 'BJ',
			esc_html__( 'BERMUDA', 'edforms' )                           => 'BM',
			esc_html__( 'BHUTAN', 'edforms' )                            => 'BT',
			esc_html__( 'BOLIVIA', 'edforms' )                           => 'BO',
			esc_html__( 'BOSNIA AND HERZEGOVINA', 'edforms' )            => 'BA',
			esc_html__( 'BOTSWANA', 'edforms' )                          => 'BW',
			esc_html__( 'BRAZIL', 'edforms' )                            => 'BR',
			esc_html__( 'BRUNEI', 'edforms' )                            => 'BN',
			esc_html__( 'BULGARIA', 'edforms' )                          => 'BG',
			esc_html__( 'BURKINA FASO', 'edforms' )                      => 'BF',
			esc_html__( 'BURUNDI', 'edforms' )                           => 'BI',
			esc_html__( 'CAMBODIA', 'edforms' )                          => 'KH',
			esc_html__( 'CAMEROON', 'edforms' )                          => 'CM',
			esc_html__( 'CANADA', 'edforms' )                            => 'CA',
			esc_html__( 'CAPE VERDE', 'edforms' )                        => 'CV',
			esc_html__( 'CAYMAN ISLANDS', 'edforms' )                    => 'KY',
			esc_html__( 'CENTRAL AFRICAN REPUBLIC', 'edforms' )          => 'CF',
			esc_html__( 'CHAD', 'edforms' )                              => 'TD',
			esc_html__( 'CHILE', 'edforms' )                             => 'CL',
			esc_html__( 'CHINA', 'edforms' )                             => 'CN',
			esc_html__( 'COLOMBIA', 'edforms' )                          => 'CO',
			esc_html__( 'COMOROS', 'edforms' )                           => 'KM',
			esc_html__( 'CONGO, DEMOCRATIC REPUBLIC OF THE', 'edforms' ) => 'CD',
			esc_html__( 'CONGO, REPUBLIC OF THE', 'edforms' )            => 'CG',
			esc_html__( 'COSTA RICA', 'edforms' )                        => 'CR',
			esc_html__( "CÔTE D'IVOIRE", 'edforms' )                     => 'CI',
			esc_html__( 'CROATIA', 'edforms' )                           => 'HR',
			esc_html__( 'CUBA', 'edforms' )                              => 'CU',
			esc_html__( 'CURAÇAO', 'edforms' )                           => 'CW',
			esc_html__( 'CYPRUS', 'edforms' )                            => 'CY',
			esc_html__( 'CZECH REPUBLIC', 'edforms' )                    => 'CZ',
			esc_html__( 'DENMARK', 'edforms' )                           => 'DK',
			esc_html__( 'DJIBOUTI', 'edforms' )                          => 'DJ',
			esc_html__( 'DOMINICA', 'edforms' )                          => 'DM',
			esc_html__( 'DOMINICAN REPUBLIC', 'edforms' )                => 'DO',
			esc_html__( 'EAST TIMOR', 'edforms' )                        => 'TL',
			esc_html__( 'ECUADOR', 'edforms' )                           => 'EC',
			esc_html__( 'EGYPT', 'edforms' )                             => 'EG',
			esc_html__( 'EL SALVADOR', 'edforms' )                       => 'SV',
			esc_html__( 'EQUATORIAL GUINEA', 'edforms' )                 => 'GQ',
			esc_html__( 'ERITREA', 'edforms' )                           => 'ER',
			esc_html__( 'ESTONIA', 'edforms' )                           => 'EE',
			esc_html__( 'ETHIOPIA', 'edforms' )                          => 'ET',
			esc_html__( 'FAROE ISLANDS', 'edforms' )                     => 'FO',
			esc_html__( 'FIJI', 'edforms' )                              => 'FJ',
			esc_html__( 'FINLAND', 'edforms' )                           => 'FI',
			esc_html__( 'FRANCE', 'edforms' )                            => 'FR',
			esc_html__( 'FRENCH POLYNESIA', 'edforms' )                  => 'PF',
			esc_html__( 'GABON', 'edforms' )                             => 'GA',
			esc_html__( 'GAMBIA', 'edforms' )                            => 'GM',
			esc_html( _x( 'GEORGIA', 'Country', 'edforms' ) )            => 'GE',
			esc_html__( 'GERMANY', 'edforms' )                           => 'DE',
			esc_html__( 'GHANA', 'edforms' )                             => 'GH',
			esc_html__( 'GREECE', 'edforms' )                            => 'GR',
			esc_html__( 'GREENLAND', 'edforms' )                         => 'GL',
			esc_html__( 'GRENADA', 'edforms' )                           => 'GD',
			esc_html__( 'GUAM', 'edforms' )                              => 'GU',
			esc_html__( 'GUATEMALA', 'edforms' )                         => 'GT',
			esc_html__( 'GUINEA', 'edforms' )                            => 'GN',
			esc_html__( 'GUINEA-BISSAU', 'edforms' )                     => 'GW',
			esc_html__( 'GUYANA', 'edforms' )                            => 'GY',
			esc_html__( 'HAITI', 'edforms' )                             => 'HT',
			esc_html__( 'HONDURAS', 'edforms' )                          => 'HN',
			esc_html__( 'HONG KONG', 'edforms' )                         => 'HK',
			esc_html__( 'HUNGARY', 'edforms' )                           => 'HU',
			esc_html__( 'ICELAND', 'edforms' )                           => 'IS',
			esc_html__( 'INDIA', 'edforms' )                             => 'IN',
			esc_html__( 'INDONESIA', 'edforms' )                         => 'ID',
			esc_html__( 'IRAN', 'edforms' )                              => 'IR',
			esc_html__( 'IRAQ', 'edforms' )                              => 'IQ',
			esc_html__( 'IRELAND', 'edforms' )                           => 'IE',
			esc_html__( 'ISRAEL', 'edforms' )                            => 'IL',
			esc_html__( 'ITALY', 'edforms' )                             => 'IT',
			esc_html__( 'JAMAICA', 'edforms' )                           => 'JM',
			esc_html__( 'JAPAN', 'edforms' )                             => 'JP',
			esc_html__( 'JORDAN', 'edforms' )                            => 'JO',
			esc_html__( 'KAZAKHSTAN', 'edforms' )                        => 'KZ',
			esc_html__( 'KENYA', 'edforms' )                             => 'KE',
			esc_html__( 'KIRIBATI', 'edforms' )                          => 'KI',
			esc_html__( 'NORTH KOREA', 'edforms' )                       => 'KP',
			esc_html__( 'SOUTH KOREA', 'edforms' )                       => 'KR',
			esc_html__( 'KOSOVO', 'edforms' )                            => 'KV',
			esc_html__( 'KUWAIT', 'edforms' )                            => 'KW',
			esc_html__( 'KYRGYZSTAN', 'edforms' )                        => 'KG',
			esc_html__( 'LAOS', 'edforms' )                              => 'LA',
			esc_html__( 'LATVIA', 'edforms' )                            => 'LV',
			esc_html__( 'LEBANON', 'edforms' )                           => 'LB',
			esc_html__( 'LESOTHO', 'edforms' )                           => 'LS',
			esc_html__( 'LIBERIA', 'edforms' )                           => 'LR',
			esc_html__( 'LIBYA', 'edforms' )                             => 'LY',
			esc_html__( 'LIECHTENSTEIN', 'edforms' )                     => 'LI',
			esc_html__( 'LITHUANIA', 'edforms' )                         => 'LT',
			esc_html__( 'LUXEMBOURG', 'edforms' )                        => 'LU',
			esc_html__( 'MACEDONIA', 'edforms' )                         => 'MK',
			esc_html__( 'MADAGASCAR', 'edforms' )                        => 'MG',
			esc_html__( 'MALAWI', 'edforms' )                            => 'MW',
			esc_html__( 'MALAYSIA', 'edforms' )                          => 'MY',
			esc_html__( 'MALDIVES', 'edforms' )                          => 'MV',
			esc_html__( 'MALI', 'edforms' )                              => 'ML',
			esc_html__( 'MALTA', 'edforms' )                             => 'MT',
			esc_html__( 'MARSHALL ISLANDS', 'edforms' )                  => 'MH',
			esc_html__( 'MAURITANIA', 'edforms' )                        => 'MR',
			esc_html__( 'MAURITIUS', 'edforms' )                         => 'MU',
			esc_html__( 'MEXICO', 'edforms' )                            => 'MX',
			esc_html__( 'MICRONESIA', 'edforms' )                        => 'FM',
			esc_html__( 'MOLDOVA', 'edforms' )                           => 'MD',
			esc_html__( 'MONACO', 'edforms' )                            => 'MC',
			esc_html__( 'MONGOLIA', 'edforms' )                          => 'MN',
			esc_html__( 'MONTENEGRO', 'edforms' )                        => 'ME',
			esc_html__( 'MOROCCO', 'edforms' )                           => 'MA',
			esc_html__( 'MOZAMBIQUE', 'edforms' )                        => 'MZ',
			esc_html__( 'MYANMAR', 'edforms' )                           => 'MM',
			esc_html__( 'NAMIBIA', 'edforms' )                           => 'NA',
			esc_html__( 'NAURU', 'edforms' )                             => 'NR',
			esc_html__( 'NEPAL', 'edforms' )                             => 'NP',
			esc_html__( 'NETHERLANDS', 'edforms' )                       => 'NL',
			esc_html__( 'NEW ZEALAND', 'edforms' )                       => 'NZ',
			esc_html__( 'NICARAGUA', 'edforms' )                         => 'NI',
			esc_html__( 'NIGER', 'edforms' )                             => 'NE',
			esc_html__( 'NIGERIA', 'edforms' )                           => 'NG',
			esc_html__( 'NORTHERN MARIANA ISLANDS', 'edforms' )          => 'MP',
			esc_html__( 'NORWAY', 'edforms' )                            => 'NO',
			esc_html__( 'OMAN', 'edforms' )                              => 'OM',
			esc_html__( 'PAKISTAN', 'edforms' )                          => 'PK',
			esc_html__( 'PALAU', 'edforms' )                             => 'PW',
			esc_html__( 'PALESTINE, STATE OF', 'edforms' )               => 'PS',
			esc_html__( 'PANAMA', 'edforms' )                            => 'PA',
			esc_html__( 'PAPUA NEW GUINEA', 'edforms' )                  => 'PG',
			esc_html__( 'PARAGUAY', 'edforms' )                          => 'PY',
			esc_html__( 'PERU', 'edforms' )                              => 'PE',
			esc_html__( 'PHILIPPINES', 'edforms' )                       => 'PH',
			esc_html__( 'POLAND', 'edforms' )                            => 'PL',
			esc_html__( 'PORTUGAL', 'edforms' )                          => 'PT',
			esc_html__( 'PUERTO RICO', 'edforms' )                       => 'PR',
			esc_html__( 'QATAR', 'edforms' )                             => 'QA',
			esc_html__( 'ROMANIA', 'edforms' )                           => 'RO',
			esc_html__( 'RUSSIA', 'edforms' )                            => 'RU',
			esc_html__( 'RWANDA', 'edforms' )                            => 'RW',
			esc_html__( 'SAINT KITTS AND NEVIS', 'edforms' )             => 'KN',
			esc_html__( 'SAINT LUCIA', 'edforms' )                       => 'LC',
			esc_html__( 'SAINT MARTIN', 'edforms' )					  => 'MF',
			esc_html__( 'SAINT VINCENT AND THE GRENADINES', 'edforms' )  => 'VC',
			esc_html__( 'SAMOA', 'edforms' )                             => 'WS',
			esc_html__( 'SAN MARINO', 'edforms' )                        => 'SM',
			esc_html__( 'SAO TOME AND PRINCIPE', 'edforms' )             => 'ST',
			esc_html__( 'SAUDI ARABIA', 'edforms' )                      => 'SA',
			esc_html__( 'SENEGAL', 'edforms' )                           => 'SN',
			esc_html__( 'SERBIA', 'edforms' )                            => 'RS',
			esc_html__( 'SEYCHELLES', 'edforms' )                        => 'SC',
			esc_html__( 'SIERRA LEONE', 'edforms' )                      => 'SL',
			esc_html__( 'SINGAPORE', 'edforms' )                         => 'SG',
			esc_html__( 'SINT MAARTEN', 'edforms' )                      => 'SX',
			esc_html__( 'SLOVAKIA', 'edforms' )                          => 'SK',
			esc_html__( 'SLOVENIA', 'edforms' )                          => 'SI',
			esc_html__( 'SOLOMON ISLANDS', 'edforms' )                   => 'SB',
			esc_html__( 'SOMALIA', 'edforms' )                           => 'SO',
			esc_html__( 'SOUTH AFRICA', 'edforms' )                      => 'ZA',
			esc_html__( 'SPAIN', 'edforms' )                             => 'ES',
			esc_html__( 'SRI LANKA', 'edforms' )                         => 'LK',
			esc_html__( 'SUDAN', 'edforms' )                             => 'SD',
			esc_html__( 'SUDAN, SOUTH', 'edforms' )                      => 'SS',
			esc_html__( 'SURINAME', 'edforms' )                          => 'SR',
			esc_html__( 'SWAZILAND', 'edforms' )                         => 'SZ',
			esc_html__( 'SWEDEN', 'edforms' )                            => 'SE',
			esc_html__( 'SWITZERLAND', 'edforms' )                       => 'CH',
			esc_html__( 'SYRIA', 'edforms' )                             => 'SY',
			esc_html__( 'TAIWAN', 'edforms' )                            => 'TW',
			esc_html__( 'TAJIKISTAN', 'edforms' )                        => 'TJ',
			esc_html__( 'TANZANIA', 'edforms' )                          => 'TZ',
			esc_html__( 'THAILAND', 'edforms' )                          => 'TH',
			esc_html__( 'TOGO', 'edforms' )                              => 'TG',
			esc_html__( 'TONGA', 'edforms' )                             => 'TO',
			esc_html__( 'TRINIDAD AND TOBAGO', 'edforms' )               => 'TT',
			esc_html__( 'TUNISIA', 'edforms' )                           => 'TN',
			esc_html__( 'TURKEY', 'edforms' )                            => 'TR',
			esc_html__( 'TURKMENISTAN', 'edforms' )                      => 'TM',
			esc_html__( 'TUVALU', 'edforms' )                            => 'TV',
			esc_html__( 'UGANDA', 'edforms' )                            => 'UG',
			esc_html__( 'UKRAINE', 'edforms' )                           => 'UA',
			esc_html__( 'UNITED ARAB EMIRATES', 'edforms' )              => 'AE',
			esc_html__( 'UNITED KINGDOM', 'edforms' )                    => 'GB',
			esc_html__( 'UNITED STATES', 'edforms' )                     => 'US',
			esc_html__( 'URUGUAY', 'edforms' )                           => 'UY',
			esc_html__( 'UZBEKISTAN', 'edforms' )                        => 'UZ',
			esc_html__( 'VANUATU', 'edforms' )                           => 'VU',
			esc_html__( 'VATICAN CITY', 'edforms' )                      => 'VA',
			esc_html__( 'VENEZUELA', 'edforms' )                         => 'VE',
			esc_html__( 'VIRGIN ISLANDS, BRITISH', 'edforms' )           => 'VG',
			esc_html__( 'VIRGIN ISLANDS, U.S.', 'edforms' )              => 'VI',
			esc_html__( 'VIETNAM', 'edforms' )                           => 'VN',
			esc_html__( 'YEMEN', 'edforms' )                             => 'YE',
			esc_html__( 'ZAMBIA', 'edforms' )                            => 'ZM',
			esc_html__( 'ZIMBABWE', 'edforms' )                          => 'ZW',
		);

		return $codes;
	}

	public function get_us_states() {
		return apply_filters(
			'gform_us_states', array(
				esc_html__( 'Alabama', 'edforms' ),
				esc_html__( 'Alaska', 'edforms' ),
				esc_html__( 'Arizona', 'edforms' ),
				esc_html__( 'Arkansas', 'edforms' ),
				esc_html__( 'California', 'edforms' ),
				esc_html__( 'Colorado', 'edforms' ),
				esc_html__( 'Connecticut', 'edforms' ),
				esc_html__( 'Delaware', 'edforms' ),
				esc_html__( 'District of Columbia', 'edforms' ),
				esc_html__( 'Florida', 'edforms' ),
				esc_html( _x( 'Georgia', 'US State', 'edforms' ) ),
				esc_html__( 'Hawaii', 'edforms' ),
				esc_html__( 'Idaho', 'edforms' ),
				esc_html__( 'Illinois', 'edforms' ),
				esc_html__( 'Indiana', 'edforms' ),
				esc_html__( 'Iowa', 'edforms' ),
				esc_html__( 'Kansas', 'edforms' ),
				esc_html__( 'Kentucky', 'edforms' ),
				esc_html__( 'Louisiana', 'edforms' ),
				esc_html__( 'Maine', 'edforms' ),
				esc_html__( 'Maryland', 'edforms' ),
				esc_html__( 'Massachusetts', 'edforms' ),
				esc_html__( 'Michigan', 'edforms' ),
				esc_html__( 'Minnesota', 'edforms' ),
				esc_html__( 'Mississippi', 'edforms' ),
				esc_html__( 'Missouri', 'edforms' ),
				esc_html__( 'Montana', 'edforms' ),
				esc_html__( 'Nebraska', 'edforms' ),
				esc_html__( 'Nevada', 'edforms' ),
				esc_html__( 'New Hampshire', 'edforms' ),
				esc_html__( 'New Jersey', 'edforms' ),
				esc_html__( 'New Mexico', 'edforms' ),
				esc_html__( 'New York', 'edforms' ),
				esc_html__( 'North Carolina', 'edforms' ),
				esc_html__( 'North Dakota', 'edforms' ),
				esc_html__( 'Ohio', 'edforms' ),
				esc_html__( 'Oklahoma', 'edforms' ),
				esc_html__( 'Oregon', 'edforms' ),
				esc_html__( 'Pennsylvania', 'edforms' ),
				esc_html__( 'Rhode Island', 'edforms' ),
				esc_html__( 'South Carolina', 'edforms' ),
				esc_html__( 'South Dakota', 'edforms' ),
				esc_html__( 'Tennessee', 'edforms' ),
				esc_html__( 'Texas', 'edforms' ),
				esc_html__( 'Utah', 'edforms' ),
				esc_html__( 'Vermont', 'edforms' ),
				esc_html__( 'Virginia', 'edforms' ),
				esc_html__( 'Washington', 'edforms' ),
				esc_html__( 'West Virginia', 'edforms' ),
				esc_html__( 'Wisconsin', 'edforms' ),
				esc_html__( 'Wyoming', 'edforms' ),
				esc_html__( 'Armed Forces Americas', 'edforms' ),
				esc_html__( 'Armed Forces Europe', 'edforms' ),
				esc_html__( 'Armed Forces Pacific', 'edforms' ),
			)
		);
	}

	public function get_us_state_code( $state_name ) {
		$states = array(
			GFCommon::safe_strtoupper( esc_html__( 'Alabama', 'edforms' ) )                 => 'AL',
			GFCommon::safe_strtoupper( esc_html__( 'Alaska', 'edforms' ) )                  => 'AK',
			GFCommon::safe_strtoupper( esc_html__( 'Arizona', 'edforms' ) )                 => 'AZ',
			GFCommon::safe_strtoupper( esc_html__( 'Arkansas', 'edforms' ) )                => 'AR',
			GFCommon::safe_strtoupper( esc_html__( 'California', 'edforms' ) )              => 'CA',
			GFCommon::safe_strtoupper( esc_html__( 'Colorado', 'edforms' ) )                => 'CO',
			GFCommon::safe_strtoupper( esc_html__( 'Connecticut', 'edforms' ) )             => 'CT',
			GFCommon::safe_strtoupper( esc_html__( 'Delaware', 'edforms' ) )                => 'DE',
			GFCommon::safe_strtoupper( esc_html__( 'District of Columbia', 'edforms' ) )    => 'DC',
			GFCommon::safe_strtoupper( esc_html__( 'Florida', 'edforms' ) )                 => 'FL',
			GFCommon::safe_strtoupper( esc_html( _x( 'Georgia', 'US State', 'edforms' ) ) ) => 'GA',
			GFCommon::safe_strtoupper( esc_html__( 'Hawaii', 'edforms' ) )                  => 'HI',
			GFCommon::safe_strtoupper( esc_html__( 'Idaho', 'edforms' ) )                   => 'ID',
			GFCommon::safe_strtoupper( esc_html__( 'Illinois', 'edforms' ) )                => 'IL',
			GFCommon::safe_strtoupper( esc_html__( 'Indiana', 'edforms' ) )                 => 'IN',
			GFCommon::safe_strtoupper( esc_html__( 'Iowa', 'edforms' ) )                    => 'IA',
			GFCommon::safe_strtoupper( esc_html__( 'Kansas', 'edforms' ) )                  => 'KS',
			GFCommon::safe_strtoupper( esc_html__( 'Kentucky', 'edforms' ) )                => 'KY',
			GFCommon::safe_strtoupper( esc_html__( 'Louisiana', 'edforms' ) )               => 'LA',
			GFCommon::safe_strtoupper( esc_html__( 'Maine', 'edforms' ) )                   => 'ME',
			GFCommon::safe_strtoupper( esc_html__( 'Maryland', 'edforms' ) )                => 'MD',
			GFCommon::safe_strtoupper( esc_html__( 'Massachusetts', 'edforms' ) )           => 'MA',
			GFCommon::safe_strtoupper( esc_html__( 'Michigan', 'edforms' ) )                => 'MI',
			GFCommon::safe_strtoupper( esc_html__( 'Minnesota', 'edforms' ) )               => 'MN',
			GFCommon::safe_strtoupper( esc_html__( 'Mississippi', 'edforms' ) )             => 'MS',
			GFCommon::safe_strtoupper( esc_html__( 'Missouri', 'edforms' ) )                => 'MO',
			GFCommon::safe_strtoupper( esc_html__( 'Montana', 'edforms' ) )                 => 'MT',
			GFCommon::safe_strtoupper( esc_html__( 'Nebraska', 'edforms' ) )                => 'NE',
			GFCommon::safe_strtoupper( esc_html__( 'Nevada', 'edforms' ) )                  => 'NV',
			GFCommon::safe_strtoupper( esc_html__( 'New Hampshire', 'edforms' ) )           => 'NH',
			GFCommon::safe_strtoupper( esc_html__( 'New Jersey', 'edforms' ) )              => 'NJ',
			GFCommon::safe_strtoupper( esc_html__( 'New Mexico', 'edforms' ) )              => 'NM',
			GFCommon::safe_strtoupper( esc_html__( 'New York', 'edforms' ) )                => 'NY',
			GFCommon::safe_strtoupper( esc_html__( 'North Carolina', 'edforms' ) )          => 'NC',
			GFCommon::safe_strtoupper( esc_html__( 'North Dakota', 'edforms' ) )            => 'ND',
			GFCommon::safe_strtoupper( esc_html__( 'Ohio', 'edforms' ) )                    => 'OH',
			GFCommon::safe_strtoupper( esc_html__( 'Oklahoma', 'edforms' ) )                => 'OK',
			GFCommon::safe_strtoupper( esc_html__( 'Oregon', 'edforms' ) )                  => 'OR',
			GFCommon::safe_strtoupper( esc_html__( 'Pennsylvania', 'edforms' ) )            => 'PA',
			GFCommon::safe_strtoupper( esc_html__( 'Rhode Island', 'edforms' ) )            => 'RI',
			GFCommon::safe_strtoupper( esc_html__( 'South Carolina', 'edforms' ) )          => 'SC',
			GFCommon::safe_strtoupper( esc_html__( 'South Dakota', 'edforms' ) )            => 'SD',
			GFCommon::safe_strtoupper( esc_html__( 'Tennessee', 'edforms' ) )               => 'TN',
			GFCommon::safe_strtoupper( esc_html__( 'Texas', 'edforms' ) )                   => 'TX',
			GFCommon::safe_strtoupper( esc_html__( 'Utah', 'edforms' ) )                    => 'UT',
			GFCommon::safe_strtoupper( esc_html__( 'Vermont', 'edforms' ) )                 => 'VT',
			GFCommon::safe_strtoupper( esc_html__( 'Virginia', 'edforms' ) )                => 'VA',
			GFCommon::safe_strtoupper( esc_html__( 'Washington', 'edforms' ) )              => 'WA',
			GFCommon::safe_strtoupper( esc_html__( 'West Virginia', 'edforms' ) )           => 'WV',
			GFCommon::safe_strtoupper( esc_html__( 'Wisconsin', 'edforms' ) )               => 'WI',
			GFCommon::safe_strtoupper( esc_html__( 'Wyoming', 'edforms' ) )                 => 'WY',
			GFCommon::safe_strtoupper( esc_html__( 'Armed Forces Americas', 'edforms' ) )   => 'AA',
			GFCommon::safe_strtoupper( esc_html__( 'Armed Forces Europe', 'edforms' ) )     => 'AE',
			GFCommon::safe_strtoupper( esc_html__( 'Armed Forces Pacific', 'edforms' ) )    => 'AP',
		);

		$state_name = GFCommon::safe_strtoupper( $state_name );
		$code       = isset( $states[ $state_name ] ) ? $states[ $state_name ] : $state_name;

		return $code;
	}

	public function get_canadian_provinces() {
		return array( esc_html__( 'Alberta', 'edforms' ), esc_html__( 'British Columbia', 'edforms' ), esc_html__( 'Manitoba', 'edforms' ), esc_html__( 'New Brunswick', 'edforms' ), esc_html__( 'Newfoundland & Labrador', 'edforms' ), esc_html__( 'Northwest Territories', 'edforms' ), esc_html__( 'Nova Scotia', 'edforms' ), esc_html__( 'Nunavut', 'edforms' ), esc_html__( 'Ontario', 'edforms' ), esc_html__( 'Prince Edward Island', 'edforms' ), esc_html__( 'Quebec', 'edforms' ), esc_html__( 'Saskatchewan', 'edforms' ), esc_html__( 'Yukon', 'edforms' ) );

	}

	public function get_state_dropdown( $states, $selected_state = '', $placeholder = '' ) {
		$str = '';
		foreach ( $states as $code => $state ) {
			if ( is_array( $state ) ) {
				$str .= sprintf( '<optgroup label="%1$s">%2$s</optgroup>', esc_attr( $code ), $this->get_state_dropdown( $state, $selected_state, $placeholder ) );
			} else {
				if ( is_numeric( $code ) ) {
					$code = $state;
				}
				if ( empty( $state ) ) {
					$state = $placeholder;
				}

				$str .= $this->get_select_option( $code, $state, $selected_state );
			}
		}

		return $str;
	}

	/**
	 * Returns the option tag for the current choice.
	 *
	 * @param string $value The choice value.
	 * @param string $label The choice label.
	 * @param string $selected_value The value for the selected choice.
	 *
	 * @return string
	 */
	public function get_select_option( $value, $label, $selected_value ) {
		$selected = $value == $selected_value ? "selected='selected'" : '';

		return sprintf( "<option value='%s' %s>%s</option>", esc_attr( $value ), $selected, esc_html( $label ) );
	}

	public function get_us_state_dropdown( $selected_state = '' ) {
		$states = array_merge( array( '' ), $this->get_us_states() );
		$str    = '';
		foreach ( $states as $code => $state ) {
			if ( is_numeric( $code ) ) {
				$code = $state;
			}

			$selected = $code == $selected_state ? "selected='selected'" : '';
			$str .= "<option value='" . esc_attr( $code ) . "' $selected>" . esc_html( $state ) . '</option>';
		}

		return $str;
	}

	public function get_canadian_provinces_dropdown( $selected_province = '' ) {
		$states = array_merge( array( '' ), $this->get_canadian_provinces() );
		$str    = '';
		foreach ( $states as $state ) {
			$selected = $state == $selected_province ? "selected='selected'" : '';
			$str .= "<option value='" . esc_attr( $state ) . "' $selected>" . esc_html( $state ) . '</option>';
		}

		return $str;
	}

	public function get_country_dropdown( $selected_country = '', $placeholder = '' ) {
		$str       = '';
		$selected_country = strtolower( $selected_country );
		$countries = array_merge( array( '' ), $this->get_countries() );
		foreach ( $countries as $code => $country ) {
			if ( is_numeric( $code ) ) {
				$code = $country;
			}
			if ( empty( $country ) ) {
				$country = $placeholder;
			}
			$selected = strtolower( $code ) == $selected_country ? "selected='selected'" : '';
			$str .= "<option value='" . esc_attr( $code ) . "' $selected>" . esc_html( $country ) . '</option>';
		}

		return $str;
	}

	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {
		if ( is_array( $value ) ) {
			$street_value  = trim( rgget( $this->id . '.1', $value ) );
			$street2_value = trim( rgget( $this->id . '.2', $value ) );
			$city_value    = trim( rgget( $this->id . '.3', $value ) );
			$state_value   = trim( rgget( $this->id . '.4', $value ) );
			$zip_value     = trim( rgget( $this->id . '.5', $value ) );
			$country_value = trim( rgget( $this->id . '.6', $value ) );

			if ( $format === 'html' ) {
				$street_value  = esc_html( $street_value );
				$street2_value = esc_html( $street2_value );
				$city_value    = esc_html( $city_value );
				$state_value   = esc_html( $state_value );
				$zip_value     = esc_html( $zip_value );
				$country_value = esc_html( $country_value );

				$line_break = '<br />';
			} else {
				$line_break = "\n";
			}

			/**
			 * Filters the format that the address is displayed in.
			 *
			 * @since Unknown
			 *
			 * @param string           'default' The format to use. Defaults to 'default'.
			 * @param GF_Field_Address $this     An instance of the GF_Field_Address object.
			 */
			$address_display_format = apply_filters( 'gform_address_display_format', 'default', $this );
			if ( $address_display_format == 'zip_before_city' ) {
				/*
                Sample:
                3333 Some Street
                suite 16
                2344 City, State
                Country
                */

				$addr_ary   = array();
				$addr_ary[] = $street_value;

				if ( ! empty( $street2_value ) ) {
					$addr_ary[] = $street2_value;
				}

				$zip_line = trim( $zip_value . ' ' . $city_value );
				$zip_line .= ! empty( $zip_line ) && ! empty( $state_value ) ? ", {$state_value}" : $state_value;
				$zip_line = trim( $zip_line );
				if ( ! empty( $zip_line ) ) {
					$addr_ary[] = $zip_line;
				}

				if ( ! empty( $country_value ) ) {
					$addr_ary[] = $country_value;
				}

				$address = implode( '<br />', $addr_ary );

			} else {
				$address = $street_value;
				$address .= ! empty( $address ) && ! empty( $street2_value ) ? $line_break . $street2_value : $street2_value;
				$address .= ! empty( $address ) && ( ! empty( $city_value ) || ! empty( $state_value ) ) ? $line_break . $city_value : $city_value;
				$address .= ! empty( $address ) && ! empty( $city_value ) && ! empty( $state_value ) ? ", $state_value" : $state_value;
				$address .= ! empty( $address ) && ! empty( $zip_value ) ? " $zip_value" : $zip_value;
				$address .= ! empty( $address ) && ! empty( $country_value ) ? $line_break . $country_value : $country_value;
			}

			// Adding map link.
			/**
			 * Disables the Google Maps link from displaying in the address field.
			 *
			 * @since 1.9
			 *
			 * @param bool false Determines if the map link should be disabled. Set to true to disable. Defaults to false.
			 */
			$map_link_disabled = apply_filters( 'gform_disable_address_map_link', false );
			if ( ! empty( $address ) && $format == 'html' && ! $map_link_disabled ) {
				$address_qs = str_replace( $line_break, ' ', $address ); //replacing <br/> and \n with spaces
				$address_qs = urlencode( $address_qs );
				$address .= "<br/><a href='http://maps.google.com/maps?q={$address_qs}' target='_blank' class='map-it-link'>Map It</a>";
			}

			return $address;
		} else {
			return '';
		}
	}

	public function get_input_property( $input_id, $property_name ) {
		$input = GFFormsModel::get_input( $this, $input_id );

		return rgar( $input, $property_name );
	}

	public function sanitize_settings() {
		parent::sanitize_settings();
		if ( $this->addressType ) {
			$this->addressType = wp_strip_all_tags( $this->addressType );
		}

		if ( $this->defaultCountry ) {
			$this->defaultCountry = wp_strip_all_tags( $this->defaultCountry );
		}

		if ( $this->defaultProvince ) {
			$this->defaultProvince = wp_strip_all_tags( $this->defaultProvince );
		}

	}

	public function get_value_export( $entry, $input_id = '', $use_text = false, $is_csv = false ) {
		if ( empty( $input_id ) ) {
			$input_id = $this->id;
		}

		if ( absint( $input_id ) == $input_id ) {
			$street_value  = str_replace( '  ', ' ', trim( rgar( $entry, $input_id . '.1' ) ) );
			$street2_value = str_replace( '  ', ' ', trim( rgar( $entry, $input_id . '.2' ) ) );
			$city_value    = str_replace( '  ', ' ', trim( rgar( $entry, $input_id . '.3' ) ) );
			$state_value   = str_replace( '  ', ' ', trim( rgar( $entry, $input_id . '.4' ) ) );
			$zip_value     = trim( rgar( $entry, $input_id . '.5' ) );
			$country_value = $this->get_country_code( trim( rgar( $entry, $input_id . '.6' ) ) );

			$address = $street_value;
			$address .= ! empty( $address ) && ! empty( $street2_value ) ? "  $street2_value" : $street2_value;
			$address .= ! empty( $address ) && ( ! empty( $city_value ) || ! empty( $state_value ) ) ? ", $city_value," : $city_value;
			$address .= ! empty( $address ) && ! empty( $city_value ) && ! empty( $state_value ) ? "  $state_value" : $state_value;
			$address .= ! empty( $address ) && ! empty( $zip_value ) ? "  $zip_value," : $zip_value;
			$address .= ! empty( $address ) && ! empty( $country_value ) ? "  $country_value" : $country_value;

			return $address;
		} else {

			return rgar( $entry, $input_id );
		}
	}
}

GF_Fields::register( new GF_Field_Address() );
