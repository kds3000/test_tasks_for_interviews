<?php
/**
 * Class Item
 *
 * @author Dmitriy Kuznetsov 
 */
final class Item {
    /**
     * Unique ID of an item
     * @var integer Item $id
     */
    private int $id;
    
    /**
     * Item name
     * @var string Item $name
     */
    private string $name;
    
    /**
     * Item status
     * @var integer Item $status
     */
    private int $status;
    
    /**
     * True if item name or status has been changed after initialize,
     * otherwise false
     * @var boolean Item $changed
     */
    private bool $changed;
    
    /**
     * True if item name and status has been initialized 
     * using class init() method, otherwise false
     * @var boolean Item $is_initialized
     */
    private bool $is_initialized = false;
    
    /**
     * Creates an instance of Item class, initializing $id property
     * @param integer $id Unique ID of an item
     */
    public function __construct($id) {
        $this->id = $id;
    }
    
    /**
     * Initializes an object once, setting name and status taken from objects DB
     * @param integer $id Unique ID of an item
     * @param $mysqli an object which represents the connection to a MySQL Server
     * @throws Exception when method has been already called once
     */
    private function init($id, $mysqli) {
        if ($this->is_initialized) {
            throw new Exception("Object is already initialized");
        }
        else {
            $query = "SELECT name, status FROM objects WHERE id = $id";
            $result = $mysqli->query($query);
            if ($result) {
                $row = $result->fetch_row();
                $this->name = $row[0];
                $this->status = $row[1];
                $this->changed = false;
                $this->is_initialized = true;
                $result->close();
            }
        }
    }
    
    /**
     * 
     * @param $property any property of an object
     * @return value of object certain property
     */
    public function __get($property) {
        return $this->$property;
    }
    
    /**
     * Set name or status properties of an object. Wrong type values and empty 
     * values can not be set
     * @param $property 
     * @param $value integer for status, string for name
     * @throws Exception when there is a try to overwrite id
     * @throws Exception when there is a try to set emoty value
     * @throws Exception when setting not int for status or not str for name 
     */
    public function __set($property, $value) {
        if ($property === 'id') {
            throw new Exception("ID is not available for overwriting");
        }   
        elseif (($property == 'status') and is_int($value)) {
            $this->status = $value;
            $this->changed = true;
        }
        elseif (empty($value) and ($value != "0")) {
            throw new Exception("Value can not be empty");
        } 
        elseif (($property == 'name') and is_string($value)) {
            $this->name = $value;
            $this->changed = true;
        }
        else {
            throw new Exception("Wrong type value");
        }
    }
    
    /**
     * Saves name and status properties to DB if that values have been changed
     * from the outside of class
     * @param $mysqli an object which represents the connection to a MySQL Server
     */
    public function save($mysqli) {
        if ($this->changed) {
            $query = "UPDATE objects SET name = $this->name, "
                    . "status = $this->status WHERE id = $this->id";
            $result = $mysqli->query($query); 
            $result->close();
        }
    }
}