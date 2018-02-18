<html>
<link rel="stylesheet" href="../css/style.css">
<script src="../js/breeds.js"></script>
    <body>
        <div>
            <div class="main-layout">
                <h1>Doggies</h1>
                <?php
                echo $dogs;

                $parts = explode('/', $randomImage);
                echo '
                    <h3>Random (' . $parts[count($parts) - 2] . ')</h3>
                    <img src="' . $randomImage . '" />
                ';
                ?>
             </div>
         </div>
    </body>
</html>
