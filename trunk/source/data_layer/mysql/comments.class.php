<?php
/*
  Class: Comments
  
  Used to interface with the storage mechanism.
  
  This instance supports MySQL.
*/
class Comments extends DataLayer {
  private $link;

  /*
    Function: __construct
    
    Establishes a connection with the database. Creates table if it doesn't exist.
  */
  public function __construct() {
    
    $this->link = mysql_connect(JB_MYSQL_HOST, JB_MYSQL_USERNAME, JB_MYSQL_PASSWORD)
      or die('Could not connect: ' . mysql_error());
    mysql_select_db(JB_MYSQL_DATABASE, $this->link) or die('Could not select database');
  
    if ( !mysql_num_rows( mysql_query("SHOW TABLES LIKE '" . JB_MYSQL_TABLE . "'") ) ) {
      mysql_query(str_replace('jb_comments', JB_MYSQL_TABLE, file_get_contents(realpath(dirname(__FILE__) . '/table_structure.sql'))), $this->link);
    }
  }
  
  /*
    Function: deleteComment
    
    Deletes a comment given the comment ID.
    
    Parameters:
      id - comment ID
  */
  public function deleteComment($id) {
    $query = "DELETE FROM `" . JB_MYSQL_TABLE . "` WHERE id='$id' LIMIT 1";
    mysql_query($query, $this->link);
  }
  
  /*
    Function: deleteSpam
    
    Deletes all comments classified as spam.
  */
  public function deleteSpam() {
    $query = "DELETE FROM `" . JB_MYSQL_TABLE . "` WHERE spam=1";
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
    $query = "INSERT INTO `" . JB_MYSQL_TABLE . "`";
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
    $query = "SELECT name, website, comment, user_ip, user_agent, spam FROM `" . JB_MYSQL_TABLE . "` WHERE id='$id' LIMIT 1";
    $comment = mysql_fetch_assoc(mysql_query($query, $this->link));
    $spam = (int) $comment['spam'];
    if ($spam) {
      $new_type = 'ham';
      $new_value = 0;
    } else {
      $new_type = 'spam';
      $new_value = 1;
    }
    $query = "UPDATE `" . JB_MYSQL_TABLE . "` SET spam='$new_value' WHERE id='$id'";
    mysql_query($query, $this->link);
    $this->notifyAkismet($comment, $new_type);
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
    $result = mysql_fetch_row(mysql_query("SELECT COUNT(id) FROM `" . JB_MYSQL_TABLE . "` WHERE spam=$filter", $this->link));
    return $result[0];
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
    $query = "SELECT id, name, website, comment, date FROM `" . JB_MYSQL_TABLE . "` WHERE spam=" . (int)$filter . ' ORDER BY date';
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
    Function: notifyAkismet
    
    Called when a comment is reclassified.
    
    Parameters:
      $obj - comment array
      $type - new type, 'spam' or 'ham'
  */
  protected function notifyAkismet($obj, $type) {
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
