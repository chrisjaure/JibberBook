-----------------------------------------------------------
JibberBook v2.0
(c) 2007 Chris Jaure
license: MIT License
website: http://www.chromasynthetic.com/
-----------------------------------------------------------

REQUIREMENTS:

- Web Server
- PHP5

INSTALLATION:

1. Download and unzip files from: 

  http://www.chromasynthetic.com/scripts/jibberbook/jibberbookv2.zip
  
2. Open jibberbook/inc/config.php and on line 19, change 'password' to a password of your choice.

3. Upload the files to your web server.

If you want to use a MySQL database instead of XML, copy jibberbook/data_layer/mysql/comments.php and paste it to jibberbook/inc/. In that file, enter your database settings and a name for the table, which will be created automatically.

If you want to use a different storage system, just edit jibberbook/inc/comments.php to suit your needs.


CONFIGURATION:

The configuration file contains many options.
You can change (on line number):

12. The filename where the comments are stored. If you rename the file, change it here.
13. The filename of the comments page. If you rename the file, change it here.
14. How many comments are loaded at a time. A good range is 20-50.
15. The date format. For other date formats, go to http://php.net/manual/en/function.date.php
16. The folder name of the theme to be used.

19. The password for the admin section.

22. If true, HTML Purifier will be used to filter HTML. If false, all tags will be stripped. For more information on HTML Purifier, go to http://htmlpurifier.org/
23. The character encoding of your page. Required by HTML Purifier.
24. The doctype of your page. Required by HTML Purifier.
25. Allowed HTML elements and attributes. Required by HTML Purifier.

28. Your Akismet key. If a key is provided, Akismet will be used to filter spam. If a key is not provided, a simple spam filtration technique will be used. For more information on Akismet, go to http://akismet.com/
29. The URL of the guestbook page. Required by Akismet.


ADMINISTRATION:

By navigating to [guestbook_url]/admin, you can log in to the admin section of JibberBook. Here you can perform actions on comments.
(Icons by famfamfam, website: <http://www.famfamfam.com/lab/icons/silk/>)

You can:

1. Delete spam and ham comments.
2. Reclassify ham comments as spam comments.
3. Reclassify spam comments as ham comments.

Note: If you provided an Akismet key, data will be sent back to Akismet when reclassifing a comment.


INSTALLING THEMES:

Themes should be unzipped and uploaded to the 'theme' directory and should be contained in their own subdirectory.

To enable a theme, in config.inc on line number 19, enter the name of the subdirectory of the theme.


CREATING THEMES:

Currently, themes can only consist of CSS, not HTML. However, if you wish to integrate JibberBook into an existing page, you are free to do so.

Themes should be contained within their own folders. Use the default theme as an example.

Themes must contain these files:

- style.css
- style_js.css

style_js.css is loaded if the browser supports JavaScript.


TIPS FOR INTEGRATING JIBBERBOOK:

- HTML
  - Element IDs prefixed with 'jb_' are required by the JavaScript functionality. If you change these ID names, change it in the header where it is passed to the JavaScript.
  - 'jb_loading_message' should be placed in the same container as the comments. When this element is scrolled into view and more comments need to be loaded, they will be placed above this element.
