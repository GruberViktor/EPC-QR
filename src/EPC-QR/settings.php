<?php

class WC_Settings_My_Plugin extends WC_Settings_Page {

    /**
     * Constructor
     */
    public function __construct() {

        $this->id    = 'demo_plugin';

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
        add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );

    }

    /**
     * Add plugin options tab
     *
     * @return array
     */
    public function add_settings_tab( $settings_tabs ) {
        $settings_tabs[$this->id] = __( 'Settings Demo Tab', 'woocommerce-settings-tab-demo' );
        return $settings_tabs;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {

        $sections = array(
            'section-0'         => __( 'Plugin Options', 'woocommerce-settings-tab-demo' ),
            'section-1'         => __( 'Section 1', 'woocommerce-settings-tab-demo' ),
            'section 2'         => __( 'Section 2', 'woocommerce-settings-tab-demo' ),

        );

        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }


    /**
     * Get sections
     *
     * @return array
     */
    public function get_settings( $section = null ) {

        switch( $section ){

            case 'section-0' :
                $settings = array(
                    'section_title' => array(
                        'name'     => __( 'Main Section Title', 'woocommerce-settings-tab-demo' ),
                        'type'     => 'title',
                        'desc'     => '',
                        'id'       => 'wc_settings_tab_demo_title_section-1'
                    ),
                    'title' => array(
                        'name' => __( 'Main Title', 'woocommerce-settings-tab-demo' ),
                        'type' => 'text',
                        'desc' => __( 'This is some helper text', 'woocommerce-settings-tab-demo' ),
                        'id'   => 'wc_settings_tab_demo_title_section-1'
                    ),
                    'description' => array(
                        'name' => __( 'Main Description', 'woocommerce-settings-tab-demo' ),
                        'type' => 'textarea',
                        'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo' ),
                        'id'   => 'wc_settings_tab_demo_description_section-1'
                    ),
                    'section_end' => array(
                         'type' => 'sectionend',
                         'id' => 'wc_settings_tab_demo_end-section-1'
                    )
                );

            break;
            case 'section-1':
                $settings = array(
                    'section_title' => array(
                        'name'     => __( 'Section One Title', 'woocommerce-settings-tab-demo' ),
                        'type'     => 'title',
                        'desc'     => '',
                        'id'       => 'wc_settings_tab_demo_section_title_section-2'
                    ),
                    'title' => array(
                        'name' => __( 'Section One Title', 'woocommerce-settings-tab-demo' ),
                        'type' => 'text',
                        'desc' => __( 'This is some helper text', 'woocommerce-settings-tab-demo' ),
                        'id'   => 'wc_settings_tab_demo_title_section-2'
                    ),
                    'description' => array(
                        'name' => __( 'Section One Description', 'woocommerce-settings-tab-demo' ),
                        'type' => 'textarea',
                        'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo' ),
                        'id'   => 'wc_settings_tab_demo_description_section-2'
                    ),
                    'section_end' => array(
                         'type' => 'sectionend',
                         'id' => 'wc_settings_tab_demo_section_end_section-2'
                    )
                );
            break;
            case 'section-2':
                $settings = array(
                    'section_title' => array(
                        'name'     => __( 'Section Two Title', 'woocommerce-settings-tab-demo' ),
                        'type'     => 'title',
                        'desc'     => '',
                        'id'       => 'wc_settings_tab_demo_section_title'
                    ),
                    'title' => array(
                        'name' => __( 'Section Two Title', 'woocommerce-settings-tab-demo' ),
                        'type' => 'text',
                        'desc' => __( 'This is some helper text', 'woocommerce-settings-tab-demo' ),
                        'id'   => 'wc_settings_tab_demo_title'
                    ),
                    'description' => array(
                        'name' => __( 'Section Two Description', 'woocommerce-settings-tab-demo' ),
                        'type' => 'textarea',
                        'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo' ),
                        'id'   => 'wc_settings_tab_demo_description'
                    ),
                    'section_end' => array(
                         'type' => 'sectionend',
                         'id' => 'wc_settings_tab_demo_section_end'
                    )
                );


            break;

        }

        return apply_filters( 'wc_settings_tab_demo_settings', $settings, $section );

    }

    /**
     * Output the settings
     */
    public function output() {
        global $current_section;
        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::output_fields( $settings );
    }


    /**
     * Save settings
     */
    public function save() {
        global $current_section;
        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::save_fields( $settings );
    }

}

return new WC_Settings_My_Plugin();