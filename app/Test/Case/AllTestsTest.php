<?php
class AllTestsTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All controller tests');
        $suite->addTestDirectoryRecursive(TESTS . 'Case');
        return $suite;
    }
} 
?>