<html>
    <?php
    $parts = explode('/', $url);
    if (isset($subBreed)) {
        $directory = '../../';
        $name = $breed . ' / ' . $subBreed;
        $url = 'http://' . $parts[count($parts) - 4] . '/';
    } else {
        $directory = '../';
        $name = $breed;
        $url = 'http://' . $parts[count($parts) - 3] . '/';
    }

    echo '<link rel="stylesheet" href="' . $directory . 'css/style.css">';
    ?>
    <body>
        <span id="<?php echo $name; ?>"></span>
        <div class="main-layout">
            <?php
            echo '
                <div id="navbar">
                    <a href="' . $url . '">Home</a>
                    <a href="#' . $name . '">' . ucfirst($name) . '</a>
                    <a href="http://dogtime.com/search?q=' . $name . '&submit=Search">More Info</a>
                </div>';

            echo $dogImages;
            ?>
        </div>
        <?php echo '<script src="' . $directory . 'js/gallery.js"></script>'; ?>
    </body>
</html>
