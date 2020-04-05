<?php


/**
 * contains functions that works with substrings searching tasks
 * Class SubstringSearcher
 */
class SubstringSearcher
{
    /**
     * Searchs for substring occurrence in file. Stops working when first occurrence is found. Returns info about first
     * occurrence.
     * @param string $substring needle
     * @param string $file_path location of file that contains haystack
     * @return mixed ['result'=>["line_number"=>line of substring occurrence),
     *                           "index"=>inline index of substring occurrence
     *                          ],
     *                'msg'=>success or fail message
     *               ]
     */
    function stristr_search($substring, $file_path) {
        $file_content = file_get_contents($file_path);

        // Stores file content before found needle
        $content_before_substring = stristr($file_content, $substring, true);
        if ($content_before_substring !== false) {
            // split that content to count lines
            $exploded = explode(PHP_EOL, $content_before_substring);
            $line = count($exploded);
            $index = strlen($exploded[$line - 1]);
            $result['result'] = ["line_number"=>$line, "index"=>$index];
            $result['msg'] = "Substring found in $line line, at $index index";
        } else {
            $result['result'] = false;
            $result['msg'] = "Substring is not found";
        }
        return $result;
    }
}