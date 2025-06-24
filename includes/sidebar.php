<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"> <img alt="image" src="assets/img/logo.png" class="header-logo" /> <span
                    class="logo-name">BDC</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown">
                <a href="index.php" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="archive"></i><span>Business Cards</span></a>
                <ul class="dropdown-menu">
                    <?php
                    if ($_SESSION['role'] != "1") {
                    ?>
                        <li><a class="nav-link" href="create_card.php">Create</a></li>
                    <?php
                    }
                    ?>
                    <li><a class="nav-link" href="show_cards.php">Show</a></li>
                </ul>
            </li>
            <?php
            if ($_SESSION['role'] == "1") {
            ?>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="grid"></i><span>Business Category</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="create_business_category.php">Create</a></li>
                        <li><a class="nav-link" href="show_business_category.php">Show</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="grid"></i><span>Social Category</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="create_social_category.php">Create</a></li>
                        <li><a class="nav-link" href="show_social_category.php">Show</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            data-feather="share-2"></i><span>Social Icons</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="create_social_icons.php">Create</a></li>
                        <li><a class="nav-link" href="show_social_icons.php">Show</a></li>
                    </ul>
                </li>
            <?php
            }
            ?>
        </ul>
    </aside>
</div>