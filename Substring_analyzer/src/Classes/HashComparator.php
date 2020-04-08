<?php


/**
 * Class HashComparator
 * contains functions to work with hashes
 */
class HashComparator
{
    /**
     * Simple function that compares MD5 hashes of substring and file content
     * @param string $substring we work with
     * @param string $file_path  file we work with location
     * @return mixed ['result'=>true if hashes are equal and false otherwise,
     *                'msg'=>success or fail message
     *               ]
     */
    public function hash_equality($substring, $file_path) {
        $file_content = file_get_contents($file_path);
        $substring_hash = hash("md5", $substring);
        $file_content_hash = hash("md5", $file_content);
        if ($substring_hash === $file_content_hash) {
            $result['msg'] = "Hash is correct";
            $result['result'] = true;
        } else {
            $result['msg'] = "Hash is incorrect";
            $result['result'] = false;
        }
        return $result;
    }
}