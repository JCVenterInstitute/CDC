<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if ($settings->navigation_type == 0) {
    $query = $db->query("SELECT * FROM email");
    $results = $query->first();

//Value of email_act used to determine whether to display the Resend Verification link
    $email_act = $results->email_act;

// Set up notifications button/modal
    if ($user->isLoggedIn()) {
        if ($dayLimitQ = $db->query('SELECT notif_daylimit FROM settings', array())) $dayLimit = $dayLimitQ->results()[0]->notif_daylimit;
        else $dayLimit = 7;

        // 2nd parameter- true/false for all notifications or only current
        $notifications = new Notification($user->data()->id, false, $dayLimit);
    }
    ?>

  <!-- Navigation -->
  <div class="header-top">
    <div class="row">
      <div >
        <ul class="nav navbar-nav" style="float:right;">
            <?php if ($user->isLoggedIn()) { //anyone is logged in?>
              <li><a href="<?= $us_url_root ?>account.php"><i class="fa fa-fw fa-user"></i> <?php echo echousername($user->data()->id); ?></a>
              </li> <!-- Common for Hamburger and Regular menus link -->
                <?php if ($settings->notifications == 1) { ?>
                    <?php /*<li><a href="portal/'.PAGE_PATH.'#" id="notificationsTrigger" data-toggle="modal" data-target="#notificationsModal"><i class="glyphicon glyphicon-bell"></i> <span id="notifCount" class="badge" style="margin-top: -5px"><?= (($notifications->getUnreadCount() > 0) ? $notifications->getUnreadCount() : ''); ?></span></a></li>*/ ?>

                <li><a href="#" onclick="displayNotifications('new')" id="notificationsTrigger" data-toggle="modal" data-target="#notificationsModal"><i
                        class="glyphicon glyphicon-bell"></i> <span id="notifCount" class="badge" style="margin-top: -5px"><?= (int)$notifications->getUnreadCount(); ?></span></a>
                </li>
                <?php } ?>
                <?php if ($settings->messaging == 1) { ?>
                <li><a href="<?= $us_url_root ?>messages.php"><i class="glyphicon glyphicon-envelope"></i> <span id="msgCount" class="badge"
                                                                                                                       style="margin-top: -5px"><?php if ($msgC > 0) {
                                echo $msgC;
                            } ?></span></a></li>
                <?php } ?>

                <?php require_once $abs_us_root . $us_url_root . 'usersc/includes/navigation_right_side.php'; ?>

              <!-- Hamburger menu link -->
                <?php if (checkMenu(2, $user->data()->id)) {  //Links for permission level 2 (default admin) ?>
                <li class="hidden-sm hidden-md hidden-lg"><a href="<?= $us_url_root ?>admin.php"><i class="fa fa-fw fa-cogs"></i> Admin Dashboard</a>
                </li> <!-- Hamburger menu link -->
                <li class="hidden-sm hidden-md hidden-lg"><a href="<?= $us_url_root ?>admin_users.php"><i class="glyphicon glyphicon-user"></i> User Management</a>
                </li> <!-- Hamburger menu link -->
                <li class="hidden-sm hidden-md hidden-lg"><a href="<?= $us_url_root ?>admin_permissions.php"><i class="glyphicon glyphicon-lock"></i> User Permissions</a>
                </li> <!-- Hamburger menu link -->
                <li class="hidden-sm hidden-md hidden-lg"><a href="<?= $us_url_root ?>admin_pages.php"><i class="glyphicon glyphicon-wrench"></i> System Pages</a>
                </li> <!-- Hamburger menu link -->
                <li class="hidden-sm hidden-md hidden-lg"><a href="<?= $us_url_root ?>admin_messages.php"><i class="glyphicon glyphicon-envelope"></i> Messages Admin</a>
                </li> <!-- Hamburger menu link -->
                <li class="hidden-sm hidden-md hidden-lg"><a href="<?= $us_url_root ?>admin_logs.php"><i class="glyphicon glyphicon-search"></i> System Logs</a>
                </li> <!-- Hamburger menu link -->
                <?php } // is user an admin ?>

                <?php if (checkMenu(2, $user->data()->id)) {  //Links for permission level 2 (default admin) ?>
                  <li><a href="<?= $us_url_root ?>admin.php"><i class="fa fa-fw fa-cogs"></i> Admin Dashboard</a></li> <!-- regular Admin menu link -->
                  <li><a href="<?= $us_url_root ?>admin_users.php"><i class="glyphicon glyphicon-user"></i> User Management</a></li>
                  <li><a href="<?= $us_url_root ?>admin_permissions.php"><i class="glyphicon glyphicon-lock"></i> Page Permissions</a></li>
                  <li><a href="<?= $us_url_root ?>admin_pages.php"><i class="glyphicon glyphicon-wrench"></i> Page Management</a></li>
                  <li><a href="<?= $us_url_root ?>admin_messages.php"><i class="glyphicon glyphicon-envelope"></i> Message System</a></li>
                  <li><a href="<?= $us_url_root ?>admin_logs.php"><i class="glyphicon glyphicon-search"></i> System Logs</a></li>
                <?php } // is user an admin ?>
              <li><a href="<?= $us_url_root ?>logout.php"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li> <!-- regular Logout menu link -->

              <li class="hidden-sm hidden-md hidden-lg"><a href="<?= $us_url_root ?>logout.php"><i class="fa fa-fw fa-sign-out"></i> Logout</a>
              </li> <!-- regular Hamburger logout menu link -->

            <?php } else { // no one is logged in so display default items ?>
              <li><a href="<?= $us_url_root ?>login.php" class=""><i class="fa fa-sign-in"></i> Login</a></li>
              <li><a href="<?= $us_url_root ?>join.php" class=""><i class="fa fa-plus-square"></i> Register</a></li>
            <?php } //end of conditional for menu display ?>
        </ul> <!-- End of UL for navigation link list -->
      </div>

        <?php /*require_once $abs_us_root.$us_url_root.'usersc/includes/navigation.php';*/ ?>

    </div> <!-- End of Div for navigation bar -->
  </div> <!-- End of Div for navigation bar styling -->
<?php }?>
