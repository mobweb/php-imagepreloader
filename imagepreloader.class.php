<?php
class imgpreloader {

    // Some files/folders are ignored by default to avoid conflicts
    private $ignoredFiles = array( ".", "..", ".DS_Store", "_notes", "Thumbs.db" );

    // These are the image extensions that are preloaded
    private $imageFormats = array( 'png', 'jpg', 'jpeg', 'gif' );

    private $imgDir;
    private $excludedDirsArray;
    private $imageSources = array();

    public function __construct( $imgDir, $excludedDirsArray = array() ) {
        $this->excludedDirsArray = $excludedDirsArray;
        $this->imgDir = $imgDir;
        $this->preloadDir( $this->imgDir );
        $this->outputJs();
    }

    private function preloadDir( $dirPath ) {

        /*
         *
         * First, check if the current directory has been excluded by
         * checking the current directory against the list of excluded
         * directories. Since the excluded directories are specified
         * relative to the base image directory, the base image directory
         * has to be removed from the current directory path.
         *
         */
        if( !in_array( str_replace( $this->imgDir . '/', '', $dirPath ), $this->excludedDirsArray ) ) {

            /*
             *
             * Walk through the current directory
             *
             */
            $dir = opendir( $dirPath );
            while( $file = readdir( $dir ) ) {

                /*
                 *
                 * Check if the current file is specified as ignored
                 *
                 */
                if( in_array( $file, $this->ignoredFiles ) ) {
                    continue;
                }

                /*
                 *
                 * First, check if the current 'file' is really a file or a
                 * directory. This is done by checking for a dot in the name.
                 * If there's no dot, we assume it's a directory (this means
                 * that directories with a dot in their name can't be scanned)
                 *
                 */
                if( strpos( $file, "." ) ) {

                    /*
                     *
                     * Grab the file's extension and check it against the list
                     * of accepted file formats. If it's not in an accepted
                     * format, continue with the next file
                     *
                     */
                    $fileExtension = substr( $file, strpos( $file, '.' ) + 1 );
                    if( !in_array( $fileExtension, $this->imageFormats ) ) {
                        continue;
                    }

                    /*
                     *
                     * Finally add the file to the list if images to be
                     * preloaded
                     *
                     */
                    $this->imageSources[] = $dirPath . "/" . $file;
                } else {

                    /*
                     *
                     * Since the current 'file' is really a folder, scan it
                     *
                     */
                    $this->preloadDir( $dirPath . "/" . $file );
                }
            }
        }
    }

    private function outputJs() {

        /*
         *
         * Actually output all the gathered images via JavaScript
         *
         */
        $images = "";
        foreach( $this->imageSources AS $src ) {
            $images .= $src . ",";
        }
        $images = substr( $images, 0, -1 );
   
        echo '
        <script type="text/javascript">
        var preloader = function() {
            var images = "' . $images . '".split(",");
            var tempImg = []

            for( var i = 0; i < images.length; i++ ) {
                tempImg[ i ] = new Image();
                tempImg[ i ].src = images[ i ];
            }
        }
        window.onload=preloader;
        </script>
        ';
    }
}

?>