<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
          // Repeat this if block for each menu item 
       // designed to give the current page a class but also allows
      // you to have more classes if you need them
        print'<li class="';
        if($path_parts['filename']=="index"){
            print 'activePage';
        }
        print'">';
        print'<a href="index.php">Home</a>';
        print'</li>';
        
        print'<li class="';
        if($path_parts['filename']=="form"){
            print 'activePage';
        }
        print'">';
        print'<a href="form.php">Join</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "music") {
            print ' activePage ';
        }
        print '">';
        print '<a href="music.php">Music</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "fashion") {
            print ' activePage ';
        }
        print '">';
        print '<a href="fashion.php">Fashion</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "aboutUs") {
            print ' activePage ';
        }
        print '">';
        print '<a href="aboutUs.php">About</a>';
        print '</li>';
        
        ?>
    </ol>
    </nav>