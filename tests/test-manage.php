<?php

class ManageTest extends WP_UnitTestCase {


    function testAccessDenied() {

        // User isn't logged in, should return false
        $this->assertFalse(ldl_shortcode_directory_manage());
    }
}