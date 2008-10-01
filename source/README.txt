-----------------------------------------------------------
JibberBook v2.2
(c) 2008 Chris Jaure
license: MIT License
website: http://www.jibberbook.com/
-----------------------------------------------------------

REQUIREMENTS:

- PHP5


INSTALLATION:

1. Unpack files.
  
2. Open /inc/config.php and on line 22, change 'password' to a password of your choice.

3. Upload the files to your web server.

4. Make sure your server has permission to write to /xml/comments.xml

If you plan on using HTML Purifier, your server MUST have permission to write to /htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer

If you want to use a non-supported storage system, just extend /data_layer/datalayer.class.php to suit your needs.


CONFIGURATION:

The configuration file contains many options.
You can change (on line number):

12. Type of storage. Accepts xml or mysql.
13. The filename of the comments page. If you rename the file, change it here.
14. How many comments are loaded at a time. A good range is 20-50.
15. The date format. For other date formats, go to http://php.net/manual/en/function.date.php
16. The folder name of the theme to be used.
17. Language pack to be used. Should be the filename of any file in /localization/ without the extension. Uncomment if you need to change it.
18. Location of the emoticon images. Must be an absolute url. Set to false to disable emoticon replacement.
19. Enter your email address here if you want notifications of new comments. Set to false to disable.

22. If you're using xml for storage, the name of the xml file located in /data_layer/xml/

25.-29. If you're using mysql for storage, the information needed to connect to the database

32. The password for the admin section.

35. If true, HTML Purifier will be used to filter HTML. If false, all tags will be stripped. For more information on HTML Purifier, go to http://htmlpurifier.org/
36. The character encoding of your page. Required by HTML Purifier.
37. The doctype of your page. Required by HTML Purifier.
38. Allowed HTML elements and attributes. Required by HTML Purifier.

41. Your Akismet key. If a key is provided, Akismet will be used to filter spam. If a key is not provided, a simple spam filtration technique will be used. For more information on Akismet, go to http://akismet.com/
42. The URL of the guestbook page. Required by Akismet.

45. If emoticon replacement is enabled, the keys will be replaced by the images.


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
  - To make things simpler, the form and comment display html are located in seperate files in /inc/templates/ if you wish to simply include them into an existing page.
