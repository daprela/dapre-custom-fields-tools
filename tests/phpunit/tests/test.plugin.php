<?php

class Test_Dapre_Custom_Fields_Tools extends WP_UnitTestCase {

    public function test_constants () {
        $this->assertSame( 'dapre-cft', \dapre_cft\PLUGIN_NAME );

        $url = str_replace( 'tests/phpunit/tests/', '',
                trailingslashit( plugin_dir_url( __FILE__ ) ) );
        $this->assertSame( $url, \dapre_cft\PLUGIN_URL_PATH );
    }
}