<?php
require_once 'PHPUnit/Framework.php';
class AllTests {
    public static $testClassNames = array(
        'SafsiLocalDiskTest'
    );
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Safsi');
        foreach ( self::$testClassNames as $testClassName ) {
            require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . $testClassName . '.php');
            $suite->addTestSuite($testClassName);
        }
        return $suite;
    }
}
?>
