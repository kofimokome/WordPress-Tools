<?php
/**
 * Created by PhpStorm.
 * User: kofi
 * Date: 6/5/19
 * Time: 12:41 PM
 * @version 1.0.0
 * @author kofi mokome
 */

class KMSetting {
	private $menu_slug;
	private $fields;
	private $section_id;
	private $sections;

	/**
     * @param string $menu_slug The menu slug of the menu or sub menu page
	 * @since 1.0.0
	 */
	public function __construct( $menu_slug ) {
		$this->menu_slug = $menu_slug;
		$this->fields    = array();
		$this->sections  = array();
	}

	/**
	 * @since 1.0.0
	 */
	public function show_form() {
		settings_errors(); ?>
        <form method="post" action="options.php">
			<?php
			foreach ( $this->sections as $section ):
				settings_fields( $section[0] );
				do_settings_sections( $this->menu_slug );
			endforeach;
			submit_button();
			?>
        </form>

		<?php
		//echo $this->default_content;
	}

	/**
	 * @since 1.0.0
	 */
	public function save() {
		add_action( 'admin_init', array( $this, 'add_settings' ) );
	}

	/**
	 * @since 1.0.0
	 */
	public function add_settings() {

		foreach ( $this->sections as $section ) {
			add_settings_section(
				$section[0],
				$section[1],
				array( $this, 'default_section_callback' ),
				$this->menu_slug );
		}

		foreach ( $this->fields as $field ) {
			add_settings_field(
				$field['id'],
				$field['label'],
				array( $this, 'default_field_callback' ),
				$this->menu_slug,
				$field['section_id'],
				$field
			);
			register_setting( $field['section_id'], $field['id'] );
		}
	}

	/**
	 * @since 1.0.0
	 */
	public function default_field_callback( $data ) {
		switch ( $data['type'] ) {
			case 'text':
				echo "<p><input type='text' name='{$data['id']}' value='" . get_option( $data['id'] ) . "' class='{$data['input_class']}' placeholder='{$data['placeholder']}'></p>";
				echo "<strong>{$data['tip']} </strong>";
				break;
			case 'number':
				echo "<p><input type='number' name='{$data['id']}' value='" . get_option( $data['id'] ) . "' min='" . $data['min'] . "' max='" . $data['max'] . "' class='{$data['input_class']}'  placeholder='{$data['placeholder']}'></p>";
				echo "<strong>{$data['tip']} </strong>";
				break;
			case 'textarea':
				echo "<p><textarea name='{$data['id']}' id='{$data['id']}' cols='80'
                  rows='8'
                  placeholder='{$data['placeholder']}' class='{$data['input_class']}' autocomplete='{$data['autocomplete']}'>" . get_option( $data['id'] ) . "</textarea></p>";
				echo "<strong>{$data['tip']} </strong>";
				break;
			case 'checkbox':
				$state = get_option( $data['id'] ) == 'on' ? 'checked' : '';
				echo "<p><input type='checkbox' name='{$data['id']}' id='{$data['id']}' " . $state . " class='{$data['input_class']}'></p>";
				echo "<strong>{$data['tip']} </strong>";
				break;
			case 'select':
				$selected_value = get_option( $data['id'] );
				echo "<p><select type='text' name='{$data['id']}' id='{$data['id']}' class='{$data['input_class']}'>";
				foreach ( $data['options'] as $key => $value ):?>
                    <option value='<?php echo $value ?>' <?php echo ( $value === $selected_value ) ? 'selected' : '' ?> ><?php echo $key ?></option>
				<?php
				endforeach;
				echo "</select></p>";
				echo "<strong>{$data['tip']} </strong>";
				break;
			default:
				echo "<< <span style='color: red;'>Please enter a valid field type</span> >>";
				break;
		}
	}

	/**
     * @param array $data Contains parameters of the field
	 * @since 1.0.0
	 */
	public function add_field( $data ) {
		$default_data = array(
			'type'           => '',
			'id'             => '',
			'label'          => '',
			'tip'            => '',
			'min'            => '',
			'max'            => '',
			'input_class'    => '', // class for input element
			'class'          => '', // class for parent element
			'options'        => array( 'Select a value' => '' ),
			'default_option' => '',
			'autocomplete'   => 'on',
			'placeholder'    => ''
		);
		$data         = array_merge( $default_data, $data );
		// todo: compare two arrays
		$data['section_id'] = $this->section_id;
		array_push( $this->fields, $data );

	}

	/**
	 * @since 1.0.0
	 */
	public function add_section( $id, $title = '' ) {
		array_push( $this->sections, array( $id, $title ) );
		$this->section_id = $id;
	}

	/**
	 * @since 1.0.0
	 */
	public function default_section_callback() {

	}
}
