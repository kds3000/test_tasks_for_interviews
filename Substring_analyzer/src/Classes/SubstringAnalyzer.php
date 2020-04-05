<?php


/**
 *
 * Class SubstringAnalyzer
 * SubstringAnalyzer instances contains info about substring and file we are working with. Also instances have
 * an analyze functions that analyzes substring and file in certain way that depends on current active module
 * and active function specified in YAML config file.
 */
class SubstringAnalyzer
{
    /**
     * @var string $substring we work with
     */
    public string $substring;
    /**
     * @var string file we work with location
     */
    public string $filepath;

    /**
     * @param string $file_path  file we work with location
     * @param string $config_path YAML config file location
     */
    public function setFilepath($file_path, $config_path)
    {
        try
        {
            $config = yaml_parse_file($config_path);
            if (!in_array(mime_content_type($file_path), $config ["mime-types"]))
            {
                throw new Exception("Incorrect MIME-type".PHP_EOL);
            } elseif (filesize($file_path) > $config ["max_size"]) {
                throw new Exception("File is too large".PHP_EOL);
            } else {
                $this->filepath = $file_path;
            }
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * @param string $substring we work with
     */
    public function setSubstring($substring)
    {
        try
        {
            if (strlen($substring) == 0) {
                throw new Exception("Substring cannot be empty".PHP_EOL);
            } else {
                $this->substring = $substring;
            }
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Analyzes substring and file in certain way that depends on current active module
     * and active function specified in YAML config file
     * @param string $class active module name
     * @param string $function active function name
     * @return null if SubstringAnalyzer instance is not fully initialized and
     *         certain result that active function returns, otherwise
     */
    public function analyze($class, $function)
    {
        if (!isset($this->filepath)){
            echo "Choose correct file".PHP_EOL;
            return null;
        } elseif (!isset($this->substring)){
            echo "Set correct substring".PHP_EOL;
            return null;
        } else {
            $instance = new $class;
            $result = $instance->$function($this->substring, $this->filepath);
            return $result;
        }
    }
}



