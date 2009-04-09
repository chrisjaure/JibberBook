<?php
/*
  Class: Comments
  
  Used to interface with the storage mechanism.
  
  This instance supports XML.
*/
class Comments extends DataLayer {
  private $filename;
  private $handle;

  /*
    Function: __construct
    
    Sets the filename, gets a file resource handle, and locks the XML file.
  */
  public function __construct() {
    $this->filename = realpath(dirname(__FILE__) . '/' . JB_XML_FILENAME);
    $this->handle = @fopen($this->filename, 'r+') or die('<p><strong>Error:</strong> Could not open XML file. Make sure your server has correct permissions.</p>');
    flock($this->handle, LOCK_EX);
  }
  
  public function __destruct() {
    @flock($this->handle, LOCK_UN);
    @fclose($this->handle);
  }
  /*
    Function: getContents
    
    Gets contents from the XML file.
    
    Returns:
      String
  */
  private function getContents() {
    $contents = fread($this->handle, filesize($this->filename)) or die('Can\'t read file.');
    fseek($this->handle, 0);
    return (string) $contents;
  } 
  
  /*
    Function: putContents
    
    Writes to the XML file.
    
    Parameters:
      $contents - string to write to the XML file.
  */
  private function putContents($contents) {
    if (strlen($contents) > 25) {
      ftruncate($this->handle, 0);
      fwrite($this->handle, $contents) or die('Could not write to file.');
    }
  }
  
  /*
    Function: deleteComment
    
    Deletes a comment given the comment ID.
    
    Parameters:
      id - comment ID
  */
  public function deleteComment($id) {
    $xml = new DOMDocument();
    $xml->validateOnParse=true;
    $xml->loadXML($this->getContents(true));
    $message = $xml->getElementById($id);
    $parentNode = $message->parentNode;
    $parentNode->removeChild($message);
    $this->putContents($xml->saveXML());
  }
  
  /*
    Function: deleteSpam
    
    Deletes all comments classified as spam.
  */
  public function deleteSpam() {
    $xml = new DOMDocument();
    $xml->validateOnParse=true;
    $xml->loadXML($this->getContents(true));
    $xpath = new DOMXPath($xml);
    $spams = $xpath->query("/messages/message[spam=1]");
    foreach ($spams as $spam){
      $parentNode = $spam->parentNode;
      $parentNode->removeChild($spam);
    }
    $this->putContents($xml->saveXML());
  }
  
  /*
    Function: addComment
    
    Adds a comment.
    
    Parameters:
      $data - associative array of data, must contain 'name', 'website', 'comment', 'date', 'user_ip', 'user_agent', and 'spam'.
      
    Returns:
      ID of the new comment. 
  */
  public function addComment($data) {
    $xml = new SimpleXMLElement($this->getContents(true));
    $message = $xml->addChild('message');
    $id = $this->generateID();
    $message->addAttribute('mID', $id);
    foreach ($data as $key => $value) {
      $message->addChild($key, htmlspecialchars($value, ENT_QUOTES));
    }
    $this->putContents($xml->asXML());
    
    return $id;
  }
  
  /*
    Function: reclassifyComment
    
    Marks comment as spam or not spam given its current classification.
    
    Parameters:
     $id - comment ID
  */
  public function reclassifyComment($id) {
    $xml = new SimpleXMLElement($this->getContents(true));
    $message = $xml->xpath("/messages/message[@mID='$id']");
    $message = $message[0];
    if ((int) $message->spam) {
      $new_type = 'ham';
      $new_value = 0;
    } else {
      $new_type = 'spam';
      $new_value = 1;
    }
    $message->spam = $new_value;
    
    $this->notifyAkismet($message, $new_type);
    
    $this->putContents($xml->asXML());
  }
  
  /*
    Function: getCount
    
    Gets the number of the specified type of comments.
    
    Parameters:
      $filter - type of comments, 0 for ham, 1 for spam
      
    Returns:
      Number
  */
  public function getCount($filter) {
    $xml = new SimpleXMLElement($this->getContents());
    return count($xml->xpath("/messages/message[spam=$filter]"));
  }
  
  /*
    Function: getComments
    
    Gets all comments of specified type.
    
    Parameters:
      $filter - type of comment, 1 for spam, 0 for ham
      $limit - single value will get last [value] comments, array['upper'] and array['lower'] will get a range
    
    Returns:
      Multi-dimensional array
  */
  public function getComments($filter, $limit = null) {
    $xml = new SimpleXMLElement($this->getContents());
    $comments = $xml->xpath("//message[spam=$filter]");
    if (isset($limit)) {
      if (is_array($limit)) {
        if ($limit['lower'] < 0) $limit['lower'] = 0;
        $show = abs($limit['lower'] - $limit['upper']);
        $comments = array_slice($comments, $limit['lower'], $show);
      } else {
        $end = count($comments) - $limit;
        $end = ($end < 0) ? 0 : $end;
        $comments = array_slice($comments, $end);
      }
    }
    $data = array();
    foreach ($comments as $key => $message) {
      $data[$key]['name'] = (string)$message->name;
      $data[$key]['website'] = (string)$message->website;
      $data[$key]['comment'] = (string)$message->comment;
      $data[$key]['date'] = (string)$message->date;
      $data[$key]['id'] = (string)$message['mID'];
    }
    return array_reverse($data);
  }
  
  /*
    Function: notifyAkismet
    
    Called when a comment is reclassified.
    
    Parameters:
      $obj - comment array
      $type - new type, 'spam' or 'ham'
  */
  protected function notifyAkismet($obj, $type) {
    if (JB_AKISMET_KEY != '') {
      if ((string) $obj->user_ip != '' && (string) $obj->user_agent != '') {
      
        $vars = array();
        $vars['user_ip'] = (string) $obj->user_ip;
        $vars['user_agent'] = (string) $obj->user_agent;
        $vars['comment_content'] = (string) $obj->comment;
        $vars['comment_author'] = (string) $obj->name;
        $vars['comment_author_url'] = (string) $obj->website;
      
        $akismet = new MicroAkismet(JB_AKISMET_KEY, JB_GUESTBOOK_URL, 'JibberBook/' . JB_VERSION . ' | microakismet/' . JB_MA_VERSION);
        $akismet->$type($vars);
      }
    }
  }
}
?>
