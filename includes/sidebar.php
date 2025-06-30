<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.php"> <img alt="image" src="assets/img/logo.png" class="header-logo" /> <span
                    class="logo-name">BDC</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown  <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''); ?>">
                <a href="index.php" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'create_card.php' || basename($_SERVER['PHP_SELF']) == 'show_cards.php' ? 'active' : ''); ?>"">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="archive"></i><span>Business Cards</span></a>
                <ul class="dropdown-menu">
                    <?php
                    if ($_SESSION['role'] != "1") {
                    ?>
                        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'create_card.php' ? 'active' : ''); ?>"><a class="nav-link" href="create_card.php">Create</a></li>
                    <?php
                    }
                    ?>
                    <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'show_cards.php' ? 'active' : ''); ?>"><a class="nav-link" href="show_cards.php">Show</a></li>
                </ul>
            </li>
            <?php
            if ($_SESSION['role'] == "1") {
            ?>
                <li class="dropdown <?php
echo (basename($_SERVER['PHP_SELF']) == 'create_business_category.php' || basename($_SERVER['PHP_SELF']) == 'show_business_category.php' ? 'active' : '');  
                    ?>">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="grid"></i><span>Business Category</span></a>
                    <ul class="dropdown-menu">
                        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'create_business_category.php' ? 'active' : ''); ?>"><a class="nav-link" href="create_business_category.php">Create</a></li>
                        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'show_business_category.php' ? 'active' : ''); ?>"><a class="nav-link" href="show_business_category.php">Show</a></li>
                    </ul>
                </li>
                <li class="dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'create_social_category.php' || basename($_SERVER['PHP_SELF']) == 'show_social_category.php' ? 'active' : ''); ?>">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="grid"></i><span>Social Category</span></a>
                    <ul class="dropdown-menu">
                        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'create_social_category.php' ? 'active' : ''); ?>"><a class="nav-link" href="create_social_category.php">Create</a></li>
                        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'show_social_category.php' ? 'active' : ''); ?>"><a class="nav-link" href="show_social_category.php">Show</a></li>
                    </ul>
                </li>
                <li class="dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'create_social_icons.php' || basename($_SERVER['PHP_SELF']) == 'show_social_icons.php' ? 'active' : ''); ?>">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="share-2"></i><span>Social Icons</span></a>
                    <ul class="dropdown-menu">
                        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'create_social_icons.php' ? 'active' : ''); ?>"><a class="nav-link" href="create_social_icons.php">Create</a></li>
                        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'show_social_icons.php' ? 'active' : ''); ?>"><a class="nav-link" href="show_social_icons.php">Show</a></li>
                    </ul>
                </li>
                <li class="dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'show_users.php' ? 'active' : ''); ?>">
                    <a href="show_users.php" class="nav-link"><i data-feather="users"></i><span>Users</span></a>
                </li>
            <?php
            }
            ?>
        </ul>
    </aside>
</div>