Media plugin for Wolf CMS
=========================

The Media plugin is a third-party plugin for Wolf CMS. The idea is based on Wordpress' Media Library. The Media plugin provides an easy way to upload files. It also provides some hooks for plugins that need to upload images and/or resize them.

Features
--------

* Adds a new tab 'Media' where you can upload and manage your files
* Provides resized versions / thumbnails of image files

Requirements
------------

* PHP 5.3 or higher
* MySQL
* Wolf CMS 0.7.5 or higher
* The [ActiveRecord helper](https://github.com/nicwortel/wolfcms-ActiveRecord)

Installation instructions
-------------------------

1. Download the [ActiveRecord helper](https://github.com/nicwortel/wolfcms-ActiveRecord) and put it in *CMS_ROOT/wolf/helpers/ActiveRecord.php*.
2. Next, place the media plugin folder in the plugins directory (*CMS_ROOT/wolf/plugins*). **Important**: make sure the plugin folder is called 'media', not 'wolfcms-media' or anything else.
3. In the backend of Wolf CMS, open the 'Administration' tab and enable the Media plugin by checking the checkbox.
4. The plugin saves uploaded files inside the 'public' folder in a folder called 'media_uploads'. Make sure the folder exists and your web server has the appropriate rights to write new files into it.
5. Resized images are saved in a folder named 'cache' inside the 'media_uploads' folder. Again, make sure it exists and your web server has the appropriate rights to write into it.

If you're not sure wether both folders are existing and writable, open the 'Media' tab and look for the 'Status' box in the sidebar. If all messages are green, you're good to go. If not, follow the provided instructions.

Contributing
------------

Would you like to help developing this plugin? Or would you like to submit a bug report or feature request? The [GitHub repository](https://github.com/nicwortel/wolfcms-media) is the right place for this.

If you would like to submit a translation, head over to [Transifex.com](https://www.transifex.com/projects/p/wolfcms-media-plugin/) and submit a request to create a new language team (or to join an existing one).
