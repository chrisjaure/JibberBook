<?php
/*
  Class: Comments
  
  Used to interface with the storage mechanism.
  
  This instance supports MySQL.
*/
class Comments {
  private $link;
  private $table;
  /*
    Function: __construct
    
    Establishes a connection with the database. Creates table if it doesn't exist.
  */
  public function __construct() {
    
    // add your database info here
    // ---------------------------
    $host     = '';
    $username = '';
    $password = '';
    $database = '';
    $this->table = 'jb_comments';
    // ---------------------------
    
    $this->link = mysql_connect($host, $username, $password)
      or die('Could not connect: ' . mysql_error());
    mysql_select_db($database, $this->link) or die('Could not select database');
  
    if ( !mysql_num_rows( mysql_query("SHOW TABLES LIKE '{$this->table}'") ) ) {
      mysql_query("CREATE TABLE `{$this->table}` (
        `id` varchar(30) collate latin1_general_ci NOT NULL,
        `name` varchar(50) collate latin1_general_ci NOT NULL,
        `website` varchar(100) collate latin1_general_ci NOT NULL,
        `comment` text collate latin1_general_ci NOT NULL,
        `date` int(10) NOT NULL,
        `user_ip` varchar(15) collate latin1_general_ci NOT NULL,
        `user_agent` varchar(255) collate latin1_general_ci NOT NULL,
        `spam` tinyint(1) NOT NULL,
        PRIMARY KEY  (`id`),
        KEY `spam` (`spam`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci", $this->link);
    }
  }
  
  /*
    Function: deleteComment
    
    Deletes a comment given the comment ID.
    
    Parameters:
      id - comment ID
  */
  public function deleteComment($id) {
    $query = "DELETE FROM {$this->table} WHERE id='$id' LIMIT 1";
    mysql_query($query, $this->link);
  }
  
  /*
    Function: deleteSpam
    
    Deletes all comments classified as spam.
  */
  public function deleteSpam() {
    $query = "DELETE FROM {$this->table} WHERE spam=1";
    mysql_query($query, $this->link);
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
    $id = $this->generateID();
    $query = "INSERT INTO {$this->table}";
    $data['comment'] = htmlspecialchars_decode($data['comment'], ENT_COMPAT);
    $values = " VALUES ('$id', '{$data['name']}', '{$data['website']}', '{$data['comment']}', '{$data['date']}', '{$data['user_ip']}', '{$data['user_agent']}', '{$data['spam']}')";
    mysql_query($query . $values, $this->link);
    return $id;
  }
  
  /*
    Function: reclassifyComment
    
    Marks comment as spam or not spam given its current classification.
    
    Parameters:
     $id - comment ID
  */
  public function reclassifyComment($id) {
    $query = "SELECT name, website, comment, user_ip, user_agent, spam FROM {$this->table} WHERE id='$id' LIMIT 1";
    $comment = mysql_fetch_assoc(mysql_query($query, $this->link));
    $spam = (int) $comment['spam'];
    if ($spam) {
      $new_type = 'ham';
      $new_value = 0;
    } else {
      $new_type = 'spam';
      $new_value = 1;
    }
    $query = "UPDATE {$this->table} SET spam='$new_value' WHERE id='$id'";
    mysql_query($query, $this->link);
    $this->notifyAkismet($comment, $new_type);
  }
  
  /*
    Function: getHamCount
    
    Gets the number of ham comments.
    
    Returns:
      Number
  */
  public function getHamCount() {
    return $this->getCount(0);
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
    $result = mysql_fetch_row(mysql_query("SELECT COUNT(id) FROM {$this->table} WHERE spam=$filter", $this->link));
    return $result[0];
  }
  
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
    Function: getComments
    
    Gets all comments of specified type.
    
    Parameters:
      $filter - type of comment, 1 for spam, 0 for ham
      $limit - single value will get last [value] comments, array['upper'] and array['lower'] will get a range
    
    Returns:
      Multi-dimensional array
  */
  public function getComments($filter, $limit = null) {
    $query = "SELECT id, name, website, comment, date FROM {$this->table} WHERE spam=" . (int)$filter . ' ORDER BY date';
    $noreverse = false;
    if (isset($limit)) {
      if (is_array($limit)) {
        if ($limit['lower'] < 0) $limit['lower'] = 0;
        $show = abs($limit['lower'] - $limit['upper']);
        $query .= " ASC LIMIT {$limit['lower']}, $show";
      } else {
        $query .= " DESC LIMIT $limit";
         $noreverse = true;
      }
    }
    $data = array();
    $key = 0;
    $result = mysql_query($query, $this->link);
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $data[$key]['name'] = htmlspecialchars($line['name'], ENT_QUOTES);
      $data[$key]['website'] = htmlspecialchars($line['website'], ENT_QUOTES);
      $data[$key]['comment'] = htmlspecialchars($line['comment'], ENT_QUOTES);
      $data[$key]['date'] = $line['date'];
      $data[$key]['id'] = $line['id'];
      $key++;
    }
    if ($noreverse) return $data;
    else return array_reverse($data);
  }
  
  /*
    Function: generateID
    
    Generates a unique id.
    
    Returns:
      String
  */
  private function generateID() {
    return uniqid('m' . rand(1,5), true);
  }
  
  /*
    Function: notifyAkismet
    
    Called when a comment is reclassified.
    
    Parameters:
      $obj - comment array
      $type - new type, 'spam' or 'ham'
  */
  private function notifyAkismet($obj, $type) {
    require_once(realpath(dirname(__FILE__) . '/../microakismet/class.microakismet.inc.php'));
    if (JB_AKISMET_KEY != '') {
      if ($obj['user_ip'] != '' && $obj['user_agent'] != '') {
      
        $vars = array();
        $vars['user_ip'] = $obj['user_ip'];
        $vars['user_agent'] = $obj['user_agent'];
        $vars['comment_content'] = $obj['comment'];
        $vars['comment_author'] = $obj['name'];
        $vars['comment_author_url'] = $obj['website'];
      
        $akismet = new MicroAkismet(JB_AKISMET_KEY, JB_GUESTBOOK_URL, 'JibberBook/' . JB_VERSION . ' | microakismet/' . JB_MA_VERSION);
        $akismet->$type($vars);
      }
    }
  }
}
?>
