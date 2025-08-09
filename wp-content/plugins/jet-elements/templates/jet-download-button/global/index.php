<?php
	$settings = $this->get_settings_for_display();
	
	$allowed_positions = array( 'left', 'right', 'top', 'bottom' );
	$position = isset( $settings['download_icon_position'] ) ? $settings['download_icon_position'] : 'left';

	if ( ! in_array( $position, $allowed_positions ) ) {
		$position = 'left';
	}
	
	$rel      = $this->_get_html( 'download_link_rel', ' rel="%s"' );

	?><a class="elementor-button elementor-size-md jet-download jet-download-icon-position-<?php echo esc_attr( $position ); ?>" data-e-disable-page-transition="true" href="<?php echo jet_elements_download_handler()->get_download_link( $settings['download_file'] ); ?>" <?php echo $rel; ?>><?php

	$icon_format = '<span class="jet-download__icon jet-download-icon-' . $position . ' jet-elements-icon">%s</span>';

	$this->_icon( 'download_icon', $icon_format );

	$label    = $this->_get_html( 'download_label' );
	$sublabel = $this->_get_html( 'download_sub_label' );
	$file_name = '';

	if ( $settings['download_file'] && $settings['download_file_name'] ) {

		$attachment_url = wp_get_attachment_url($settings['download_file']);
		$file_name = basename($attachment_url);
	}

	if ( $label || $sublabel ) {

		echo '<span class="jet-download__text">';

		printf(
			'<span class="jet-download__label">%s</span>',
			$this->_format_label( $label, $settings['download_file'] )
		);

		printf(
			'<span class="jet-download__file-name">%s</span>',
			$this->_format_label( $file_name, $settings['download_file'] )
		);

		printf(
			'<small class="jet-download__sub-label">%s</small>',
			$this->_format_label( $sublabel, $settings['download_file'] )
		);

		echo '</span>';
	}

?></a>
