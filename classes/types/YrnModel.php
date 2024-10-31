<?php
namespace yrn;

class YrnModel {
    private static $data = array();

    private function __construct() {
    }

    public static function getDataById($postId) {
        if (!isset(self::$data[$postId])) {
            self::$data[$postId] = Numbers::getPostSavedData($postId);
        }

        return self::$data[$postId];
    }
}
