<?php 

if(!isset($title)) $title = "Noname page";
if(!isset($slider)) $slider = FALSE;

?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title ?> • nemeleon</title>

    <link rel="stylesheet" href="styles/core.css">
    <link rel="stylesheet" href="styles/fonts/nemeo-icons/css/nemeo-icons.css">

    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
    <link rel="apple-touch-icon" href="favicon.png">

    <script defer src="scripts/core.js"></script>

    <?php if($slider): ?>
    <!-- there are sliders on this page -->
    <link rel="stylesheet" href="styles/nemeo-slider.css">
    <script defer src="scripts/nemeo-slider.js"></script>
    <?php endif; ?>

</head>
<body>
<div class="site">

<div class="top-nav-bar">
    <nav>
        <div class="nav-content">
            <div class="links">
                <a class="logo" href="index.php">
                    <span class="logo-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 348 300"><path d="M337,121.6c-3.17-7.5-9.05-21.06-22.44-33.18a96.12,96.12,0,0,1-12.19,26.28A92.37,92.37,0,0,1,261.11,149a84.67,84.67,0,0,0,19-26.57c8.81-19.47,8.45-38.16,6.9-49.8a125.52,125.52,0,0,0-26.44-5.4c-4.28-.45-9-.72-14-.84a98.66,98.66,0,0,1-2.66,24.36,96.75,96.75,0,0,1-27,47.25,87.93,87.93,0,0,0,8.76-31.86,89.63,89.63,0,0,0-5.89-39.27c-9.38.48-18.32,1.29-25.52,2.25A13.22,13.22,0,0,1,179.3,54.22c.27-2.13,0-2.94.24-7.83.36-7.32.51-14-.84-19.53a30.5,30.5,0,0,0-7.59-13.56C161.79,2.74,148.38,1,142.94.34,117.69-2.84,48.1,16,15.44,79.9c-5.32,10.35-21.55,43.2-13,78.56a60.2,60.2,0,0,0,9.71,22c9.32,12.87,21.72,18,26.51,20,33.67,14,105.74,3.84,163.47,2.94,71.05-1.11,88.83,12.24,94.63,20a25.49,25.49,0,0,1,1.58,2.43c8.64,14.25,11,37-.65,46.32-5.53,4.44-14.32,5.94-20.83,2.91-7.86-3.69-12.1-13.89-12.91-21.39-.66-6.12,1-11-2.69-15.9a10,10,0,0,0-5.38-3.93c-4.84-1.23-9.26,2.67-9.8,3.15-7.38,6.48-5.55,24.42,0,36.45.75,1.62,11.21,23.13,34,26.16,16,2.16,28.32-6.09,32.18-8.7,2.36-1.56,7.14-5.07,14.1-13.95,17.12-21.84,20.05-60.66,20.05-60.66C347.53,205.32,352.34,158,337,121.6ZM70.72,131.65c-17.27,0-31.26-13.59-31.26-30.3a29.5,29.5,0,0,1,5.35-17,21.41,21.41,0,0,0-1,6.39A20.8,20.8,0,1,0,71.34,71.05c17,.33,30.66,13.77,30.66,30.27C102,118.06,88,131.65,70.72,131.65Z"/></svg></span>
                    <span class="logo-text"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 290 46.25"><path d="M30.72,22.85V46H21.5V27.44c0-2.49-.92-4.57-2.7-5.49-2.93-1.51-7.11.66-9.58,4.9V46H0V12.87H9.22v3.52a16.68,16.68,0,0,1,1.37-1.12A15,15,0,0,1,21.5,12.4a10.72,10.72,0,0,1,7.26,4.25A10.43,10.43,0,0,1,30.72,22.85Z"/><path d="M290,22.85V46h-9.22V27.44c0-2.49-.92-4.57-2.7-5.49-2.93-1.51-7.11.66-9.58,4.9V46h-9.22V12.87h9.22v3.52a16.68,16.68,0,0,1,1.37-1.12,15,15,0,0,1,10.91-2.87A10.72,10.72,0,0,1,288,16.65,10.43,10.43,0,0,1,290,22.85Z"/><path d="M124.72,22.85V46h-9.21V27.44c0-2.49-.89-4.57-2.59-5.49-2.94-1.59-7.18.88-9.48,5.51a4,4,0,0,0-.22.47V46H94V27.44c0-2.49-.89-4.57-2.58-5.49-2.34-1.26-5.51,0-7.86,3V46H74.34V12.87h9.22v3.19c.3-.28.61-.54.93-.79a13.42,13.42,0,0,1,9.51-3,9.94,9.94,0,0,1,7.4,4.34A10.45,10.45,0,0,1,102.57,19c.2-.32.42-.62.65-.92a15.34,15.34,0,0,1,2.78-2.8,13.42,13.42,0,0,1,9.51-3,9.92,9.92,0,0,1,7.39,4.34A11,11,0,0,1,124.72,22.85Z"/><rect x="167.73" width="9.22" height="45.95"/><path d="M69,29.42a16.46,16.46,0,0,0-.28-3.07,16.89,16.89,0,0,0-33.19,0,16.47,16.47,0,0,0-.29,3.07,16.1,16.1,0,0,0,.28,3,16.9,16.9,0,0,0,29.93,7.28L57.09,36a7.19,7.19,0,0,1-5,2A7.74,7.74,0,0,1,45,32.47H68.72A16.1,16.1,0,0,0,69,29.42ZM45,26.34A8.67,8.67,0,0,1,46,24.19a7.42,7.42,0,0,1,6.08-3.34,7.76,7.76,0,0,1,7.17,5.49Z"/><path d="M162.94,29.42a16.47,16.47,0,0,0-.29-3.07,16.89,16.89,0,0,0-33.19,0,16.47,16.47,0,0,0-.29,3.07,16.1,16.1,0,0,0,.28,3,16.9,16.9,0,0,0,29.93,7.28L151,36a7.19,7.19,0,0,1-5,2,7.74,7.74,0,0,1-7.17-5.52h23.77A16.09,16.09,0,0,0,162.94,29.42Zm-24.05-3.08A8.67,8.67,0,0,1,140,24.19a7.42,7.42,0,0,1,6.08-3.34,7.76,7.76,0,0,1,7.17,5.49Z"/><path d="M215.73,29.42a15.66,15.66,0,0,0-.29-3.07,16.88,16.88,0,0,0-33.18,0,16.47,16.47,0,0,0-.29,3.07,16.1,16.1,0,0,0,.28,3,16.9,16.9,0,0,0,29.93,7.28L203.82,36a7.19,7.19,0,0,1-5,2,7.74,7.74,0,0,1-7.17-5.52h23.77A16.1,16.1,0,0,0,215.73,29.42Zm-24-3.08a8.67,8.67,0,0,1,1.08-2.15,7.42,7.42,0,0,1,6.08-3.34A7.74,7.74,0,0,1,206,26.34Z"/><path d="M254.36,29.1a16.9,16.9,0,1,1-16.89-16.85A16.87,16.87,0,0,1,254.36,29.1Zm-16.89-8.27c-4.24,0-7.68,3.84-7.68,8.58S233.23,38,237.47,38s7.68-3.85,7.68-8.58S241.71,20.83,237.47,20.83Z"/></svg></span>
                </a>
                <form class="search-bar mid-hidden" method="get" action="search.php">
                    <i class="ni-magnificier"></i>
                    <input class="menu-search" type="text" name="q" id="search" placeholder="Search">
                </form>
                <a class="menu-button mid-hidden" href="colors.php">
                    <i class="ni-intercept"></i>
                    <!-- <span>Colors</span> -->
                </a>

            </div>
            <div class="actions">
                <span class="preference small-hidden">
                    <span class="theme-label"></span>
                    <span tabindex="2" class="switch theme-toggle">
                        <span class="switch-handle"></span>
                    </span>
                </span>
                <!-- <a class="menu-button mid-hidden" href="chats.php">
                    <i class="ni-message"></i>
                    <span>Chats</span>
                </a> -->
                <?php if (isset($user)): ?>
                <a class="menu-button small-hidden" href="user.php?id=<?= $user['id'] ?>">
                    <span class="pfp" style="background-image: url('<?= ($user['profile_picture'] != null || $user['profile_picture'] != "") ? ('uploads/'.$user['id'] .'/'. $user['profile_picture']) : 'styles/images/no-pfp.svg' ?>');"></span>
                    <span><?= $user['username'] ?></span>
                </a>            
                <?php else: ?>    
                <a class="menu-button small-hidden" href="login.php">
                    <!-- <i class="ni-message"></i> -->
                    <span>Login</span>
                </a>
                <?php endif; ?>

                <a id="menu-toggle" class="menu-button mid-visible"><i class="ni-angle-down"></i></a>
            </div>
        </div>
        <div class="dropdown-menu">
            <div class="dropdown-menu-content">
                <form class="search-bar" method="get" action="search.php">
                    <i class="ni-magnificier"></i>
                    <input class="menu-search" type="text" name="q" id="search" placeholder="Search">
                </form>
                <div class="links">
                    <a class="menu-button" href="colors.php"><i class="ni-intercept"></i><span>Colors</span></a>
                    <!-- <a class="menu-button" href="chats.php"><i class="ni-message"></i><span>Chats</span></a> -->
                    <?php if (isset($user)): ?>
                    <a class="menu-button small-visible" href="user.php?id=<?= $user['id'] ?>">
                        <span class="pfp" style="background-image: url('<?= ($user['profile_picture'] != null || $user['profile_picture'] != "") ? ('uploads/'.$user['id'] .'/'. $user['profile_picture']) : 'styles/images/no-pfp.svg' ?>');"></span>
                        <span><?= $user['username'] ?></span>
                    </a> 
                    <a class="menu-button" href="logout.php">Logout</a>
                    <?php else: ?>
                    <a class="menu-button" href="login.php"><i class="ni-user"></i><span>Login</span></a>
                    <?php endif; ?>
                    <p class="small-visible">
                        <span class="preference">
                            <span class="theme-label"></span>
                            <span tabindex="3" class="switch theme-toggle">
                                <span class="switch-handle"></span>
                            </span>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </nav>
</div>


<div class="content">