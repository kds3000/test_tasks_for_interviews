<?php
include "../src/Classes/SubstringAnalyzer.php";
include "../src/Classes/SubstringSearcher.php";
include "../src/Classes/HashComparator.php";
$config_path = '../src/config.yml';

/**
 *
 * Class SubstringAnalyzerTester
 * tests SubstringAnalyzer class
 *
 */
class SubstringAnalyzerTester {
    /**
     * SubstringAnalyzerTester constructor.
     * creates an instance of SubstringAnalyzer class for testing it
     */
    public function __construct()
    {
        $this->analyzer_instance = new SubstringAnalyzer();
    }

    /**
     * Tests two functions stristr_search and hash_equality from different modules.
     * For tests uses "python" substring and file python.txt that contains the only word "python"
     */
    private function pythonTxtTest()
    {
        $this->analyzer_instance->setSubstring("python");
        $this->analyzer_instance->setFilepath('files_for_tests\python.txt', $GLOBALS["config_path"]);

        $function = "stristr_search";
        $answer = ["line_number"=>1, "index"=>0];
        $this->checkResultCorrectness("SubstringSearcher", $this->analyzer_instance, $function, $answer, '1');

        $function = "hash_equality";
        $answer = true;
        $this->checkResultCorrectness("HashComparator", $this->analyzer_instance, $function, $answer, '2');
    }

    /**
     * Tests substring occurrence search function correctness. As input uses main file with some text and array of
     * files (each contains a certain substring), as output uses an array of correct answers
     * ([[correct line, correct index],[correct line, correct index]])
     * @param string $filepath path for file that we will search for occurrences in
     * @param string $inputs_dir fir that contains files with substrings we are searching for
     * @param array $inputs array of names of files that contains substrings
     * @param array $outputs array with correct answers to compare function result with
     * @throws Exception if outputs and inputs arrays are different sized
     */
    private function substringOccurrenceUserFileTest($filepath, $inputs_dir, $inputs, $outputs)
    {
        if (count($inputs) !== count($outputs)) {
            throw new Exception('Number of inputs and outputs doesn`t match!');
        }
        $this->analyzer_instance->setFilepath($filepath, $GLOBALS["config_path"]);
        for($i=0; $i<count($inputs); $i++) {
            $substring = file_get_contents($inputs_dir . $inputs[$i]);
            $this->analyzer_instance->setSubstring($substring);
            $function = "stristr_search";

            // [0] contains correct line number, [1] contains correct index
            $answer = ["line_number"=>$outputs[$i][0], "index"=>$outputs[$i][1]];
            $this->checkResultCorrectness("SubstringSearcher", $this->analyzer_instance, $function, $answer, $i+1);
        }
    }

    /** Checks function output properness and print corresponding message
     * @param string $class module name we are working with
     * @param $object SubstringAnalyzer instance we are working with
     * @param string $function function name from $class module we are working with
     * @param mixed $answer preliminary correct answer (expected function output)
     * @param integer test order number
     */
    private function checkResultCorrectness ($class, $object, $function, $answer, $test_number)
    {
        $result = $object->analyze($class, $function);
        if ($result['result'] === $answer)
        {
            echo "Test $test_number passed".PHP_EOL;
        } else {
            echo "Test $test_number failed".PHP_EOL;
        }
    }

    /**
     * Starts certain tests
     * @throws Exception if outputs and inputs arrays are different sized
     */
    public function runTests()
    {
        echo "Python.txt tests:".PHP_EOL;
        $this->pythonTxtTest();
        echo "Userfile tests:".PHP_EOL;
        $filepath = 'files_for_tests\LICENSE.txt';
        $inputs_dir = 'files_for_tests\\';
        $inputs = ['substring1.txt', 'substring2.txt', 'substring3.txt'];
        $outputs = [[4, 47], [117, 56], [324, 47]];
        $this->substringOccurrenceUserFileTest($filepath, $inputs_dir, $inputs, $outputs);
    }
}

#$tester = new SubstringAnalyzerTester();
#$tester->runTests();