<?php
/*
 * Abstract Class: DataLayer
 * 
 * Base class used to interface with the data layer.
 * 
 */
abstract class DataLayer {
    
    abstract public function __construct();
    
    abstract public function deleteComment($id);
    abstract public function deleteSpam();
    
    abstract public function addComment($data);
    
    abstract public function reclassifyComment($id);
    
    abstract public function getCount($filter);
    
    /*
        Function: getHamCount
        
        Gets the number of ham comments.
        
        Returns:
          Integer
    */
    public function getHamCount() {
        return $this->getCount(0);
    }
    
    abstract public function getComments($filter, $limit = null);
    
    /*
        Function: getHam
        
        Gets all ham comments.
        
        Parameters:
          $offset - single value will get last [value] comments, array['upper'] and array['lower'] will get a range
        
        Returns:
          Multi-dimensional array
    */
    public function getHam($offset = null) {
        return $this->getComments(0, $offset);
    }
    
    /*
        Function: getSpam
        
        Gets all spam comments.
        
        Parameters:
          $offset - single value will get last [value] comments, array['upper'] and array['lower'] will get a range
        
        Returns:
          Multi-dimensional array
    */
    public function getSpam($offset = null) {
        return $this->getComments(1, $offset);
    }
    
    /*
        Function: generateID
        
        Generates a unique id.
        
        Returns:
          String
	*/
    protected function generateID() {
        return uniqid('m' . rand(1,5), true);
    }
    
    abstract protected function notifyAkismet($obj, $type);
}
?>
