-----------------------------------------------------------
JibberBook v2.3
(c) 2009 Chris Jaure
license: MIT License
website: http://www.jibberbook.com/
-----------------------------------------------------------

REQUIREMENTS:

- PHP5


INSTALLATION:

1. Unpack files.
  
2. Open /inc/config.php and on line 33, change 'password' to a password of your choice.

3. Upload the files to your web server.

4. Make sure your server has permission to write to /data_layer/xml/comments.xml

If you plan on using HTML Purifier, your server MUST have permission to write to /libraries/htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer

If you want to use a non-supported storage system, just extend /data_layer/datalayer.class.php to suit your needs.


CONFIGURATION:

The configuration file contains many options.
For an up-to-date description of configuration options, visit http://code.google.com/p/jibberbook/wiki/Installation


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

To enable a theme, in config.inc on line number 16, enter the name of the subdirectory of the theme.


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
