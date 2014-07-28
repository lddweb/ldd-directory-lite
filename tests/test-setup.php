<?php

class SetupTest extends WP_UnitTestCase {

    public $plugin;

    function setUp() {
        parent::setUp();
        $this->plugin = ldl();
    }
    function testInstance() {
        $this->assertTrue(is_object($this->plugin), 'Missing the primary singleton');
    }

    function testVersion() {
        $this->assertTrue(LDDLITE_VERSION == get_option('lddlite_version'), 'Version failed to update');
    }

    function testSettings() {
        $this->assertClassHasAttribute('settings', 'ldd_directory_lite');
        $defaults = ldl_get_default_settings();

        foreach ($defaults as $k => $v) {
            $setting = $this->plugin->get_setting($k);
            $this->assertTrue($v == $setting);
        }
    }

    function testUpdateSetting() {

        $default_settings = ldl_get_default_settings();
        $key = key($default_settings);
        $value = 'testing update_setting()';

        $this->plugin->update_setting($key, $value);
        $this->assertTrue($value == $this->plugin->get_setting($key));
    }


    function testScriptsRegistered() {

        $styles = array(
            'lddlite',
            'font-awesome',
            'lddlite-admin',
        );

        $scripts = array(
            'lddlite-happy',
            'lddlite-contact',
            'lddlite-admin',
        );

        foreach ($styles as $handle) {
            $this->assertTrue(wp_style_is($handle, 'registered'), 'Missing style registration: ' . $handle);
        }

        foreach ($scripts as $handle) {
            $this->assertTrue(wp_script_is($handle, 'registered'), 'Missing script registration: ' . $handle);
        }

    }
}

