<?php
// main script
include 'Classes/SubstringAnalyzer.php';

// getting config info from config file, including active module
$config = yaml_parse_file('config.yml');
$active_module = $config["active_module"];
$active_function = $config["active_function"];
include 'Classes/'.$active_module.'.php';

if (isset($_POST['submit'])) {
    $name = $_FILES['file']['name'];
    $temp_name = $_FILES['file']['tmp_name'];
    $substring = $_POST['substring'];
    if (isset($name) and !empty($name)) {
        $location = '../uploads/';
        if (move_uploaded_file($temp_name, $location . $name)) {
            $substring = $_POST['substring'];
            $file = $location . $name;
            $substring_analyzer = new SubstringAnalyzer();
            $substring_analyzer->setFilepath($file, 'config.yml');
            $substring_analyzer->setSubstring($substring);
            $result = $substring_analyzer->analyze($active_module, $active_function);
            echo $result['msg'];
        }
    } else {
        echo 'You should select a file for handling';
    }
}





