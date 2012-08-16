# php-imagepreloader

A simple PHP class that preloads images in a specified directory by including them into the browser's cache as JavaScript objects.

## Usage

Simply create a new imgpreloader object and pass it the relative path to the folder containing the images to be preloaded and optionally an array containing the paths of the directories to be ignored, relative to the main image folder (argument 1).

```new imgpreloader( 'img-dir', array( 'relative/path/to/excluded-dirs', 'another-one' ) );```

## Example

Let's assume the following file structure:

    index.php
    imgpreloader.class.php
    images/
        chrome/
            nav.png
            nav-hover.png
            nav-visited.png
        raw/
            something-huge.gif

As you can see, ```images/``` is our main image folder, so we want to preload everything inside of it. However, we don't want to preload the contents of the ```images/raw/``` folder since it contains our uncompressed working files. ```index.php``` is our main file where we want to preload the images:

    include_once( 'imgpreloader.class.php' ); // Loading the class, relative to index.php
    new imgpreloader( 'images', array( 'raw' ) ); // Preload images/, but ignore images/raw/

## How it works

The *imgpreloader* class scans the contents of the image folder that is passed when creating an *imgpreloader* object. It creates a list of all the image files (gif, jpg, jpeg and png) contained inside of that folder, of course ignoring any excluded folders (```images/raw/``` in the example above).

That list of image files is passed to JavaScript, and a temporary ```Image()``` object is created in JavaScript to put the image file into the browser's cache:

    tempImg[ i ] = new Image();
    tempImg[ i ].src = images[ 'images/example.gif' ];

This all happens only when all the content on the site has been loaded, by binding the preloading on the ```window.onload```-event. This assures that your images are only preloaded AFTER all the content on the current site has been loaded.

## Support

Got a question or spotted a bug? Feel free to E-Mail me at [info@mobweb.ch](mailto:info@mobweb.ch) or send a pull request via GitHub.