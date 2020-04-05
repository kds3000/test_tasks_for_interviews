<?php

/**
 * contains functions that needs to use Knutt-Morriss-Pratt algorithm
 * Class KMPSearcher
 */
class KMPSearcher{
    /**
     * Finds all substring occurrences using prefix function calculatec with find_prefix_func
     * @param string $substring we are work with
     * @param string $file_path file we are looking for occurrences in
     * @return mixed ['result'=>[first order number of occurrence=>first index of occurrence),
     *                           second order number of occurrence=>second index of occurrence),
     *                           ...
     *                           last order number of occurrence=>last index of occurrence)
     *                          ],
     *                'msg'=>success or fail message
     *               ]
     */
    function kmp_search($substring, $file_path){
        $file = file_get_contents($file_path);
        $result['result'] = array();
        // split data to work with symbols
        $substr_splitted = str_split($substring);
        $file_splitted = str_split($file);
        $prefix_func = $this->find_prefix_func($substr_splitted);

        // $i - text index, $j - substring index
        $i = $j = 0;
        // order number of occurrence
        $num = 0;
        while($j<count($file_splitted)){
            while($i>-1 && $substr_splitted[$i]!=$file_splitted[$j]){
                // if it doesn't match, then uses then look at the prefix func
                $i = $prefix_func[$i];
            }
            $i++;
            $j++;
            if($i>=count($substr_splitted)){
                // if its match, find the matches string position
                // Then use prefix table to swipe to the right.
                $result['result'][$num++]=$j-count($substr_splitted);
                $i = $prefix_func[$i];
            }
        }
        if (count($result['result'])>0) {
            $occurrence_num = count($result['result']);
            $result['msg'] = "Found [$occurrence_num] occurrences.".PHP_EOL."At indexes: ".
                implode(", ", array_values($result['result']));
        } else {
            $result['result'] = false;
            $result['msg'] = "Substring is not found";
        }
        return $result;
    }

    /**
     * analyzes substring and creates prefix function
     * @param array $substring splitted in symbols substring that we calculate prefix function for
     * @return array prefix function
     */
    function find_prefix_func($substring){
        $i = 0;
        $j = $prefix_func[0] = -1;
        while($i<count($substring)){
            while($j>-1 && $substring[$i]!=$substring[$j]){
                $j = $prefix_func[$j];
            }
            $i++;
            $j++;
            if(isset($substring[$i])==isset($substring[$j])){
                $prefix_func[$i]=$prefix_func[$j];
            }else{
                $prefix_func[$i]=$j;
            }
        }
        return $prefix_func;
    }
}

