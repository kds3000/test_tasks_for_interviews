<?php


/**
 * Class Configurator
 */
class Configurator
{
    /** Fills config YAML file with settings
     * @param string $config_filepath config file location
     * @throws Exception if there is a try to set an active function that is not defined in active module
     */
    public function fill_config_file($config_filepath) {
        $mimetypes = ['text/plain','text/html'];
        $max_size = 50000;
        $modules = [
            'SubstringSearcher'=>['stristr_search'],
            'HashComparator'=>['hash_equality'],
            'KMPSearcher'=>['kmp_search']
        ];
        $active_module = 'SubstringSearcher';
        $active_function = 'stristr_search';
        if (!in_array($active_function, $modules[$active_module])) {
            throw new Exception('There is no defined function in defined module!');
        }
        $result = [
            'mime-types'=>$mimetypes,
            'max_size'=>$max_size,
            'available_modules_and_functions'=>$modules,
            'active_module'=>$active_module,
            'active_function'=>$active_function,
        ];
        yaml_emit_file($config_filepath, $result);
    }
}