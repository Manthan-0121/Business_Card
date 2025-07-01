<?php
include('./includes/header.php');
?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Show Business Cards</h4>
                            <?php
                            if ($_SESSION['role'] != "1") {
                            ?>
                                <div class="card-header-action">
                                    <a href="./create_card.php" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Create</a>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if (isset($_SESSION['success'])) {
                        ?>
                            <div class="show-tost alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <?php
                                    isset($_SESSION['success']) ? print_r($_SESSION['success']) : '';
                                    unset($_SESSION['success']);
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                // echo __BASE_URL;
                                ?>
                                <table class="table table-striped table-hover" id="dttable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <?php
                                            if ($_SESSION['role'] == '1') {
                                                echo '<th>User Name</th>';
                                            }
                                            ?>
                                            <th>Logo</th>
                                            <th>Business Name</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Update Status</th>
                                            <th>Edit</th>
                                            <?php
                                            if ($_SESSION['role'] != '1') {
                                                echo '<th>Delete</th>';
                                            }
                                            ?>
                                            <th>Download</th>
                                            <th>Share</th>
                                            <th>Show</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $business_card_select_sql = "SELECT binfo.id AS bid,binfo.template_id as btemplate, binfo.name AS bname, binfo.logo AS blogo, binfo.status AS bstatus, binfo.link_token AS btoken, bc.name AS bcategory";
                                        if ($_SESSION['role'] == '1') {
                                            $business_card_select_sql .= ", tu.first_name AS bfname, tu.last_name AS bkname";
                                        }
                                        $business_card_select_sql .= " FROM tbl_business_info AS binfo INNER JOIN tbl_business_category AS bc ON binfo.business_category_id = bc.id";
                                        if ($_SESSION['role'] == '1') {
                                            $business_card_select_sql .= " INNER JOIN tbl_user AS tu ON binfo.user_id = tu.id";
                                        }
                                        if ($_SESSION['role'] == '2') {
                                            $business_card_select_sql .= " WHERE binfo.user_id = :user_id";
                                        }

                                        $stmt = $conn->prepare($business_card_select_sql);
                                        if ($_SESSION['role'] == '2') {
                                            $stmt->bindParam(':user_id', $_SESSION['uid'], PDO::PARAM_INT);
                                        }
                                        $stmt->execute();
                                        $business_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        if ($business_cards) {
                                            $i = 1;
                                            foreach ($business_cards as $card) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <?php
                                                    if ($_SESSION['role'] == '1') {
                                                        echo '<td>' . htmlspecialchars($card['bfname']) . ' ' . htmlspecialchars($card['bkname']) . '</td>';
                                                    }
                                                    ?>
                                                    <td>
                                                        <?php
                                                        if (!empty($card['blogo'])) {
                                                            echo '<img src="' . $card['blogo'] . '" alt="' . htmlspecialchars($card['bname']) . '" width="50" height="50">';
                                                        } else {
                                                            echo 'No Logo';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($card['bname']); ?></td>
                                                    <td><?php echo htmlspecialchars($card['bcategory']); ?></td>
                                                    <td>
                                                        <?php
                                                        if ($card['bstatus'] == 1) {
                                                            echo '<span class="badge badge-success">Active</span>';
                                                        } else {
                                                            echo '<span class="badge badge-danger">Inactive</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <select class="form-control card_status" data-id="<?php echo $card['btoken']; ?>" name="status">
                                                            <option value="1" <?php if ($card['bstatus'] == 1) echo "selected"; ?>>Enable</option>
                                                            <option value="0" <?php if ($card['bstatus'] == 0) echo "selected"; ?>>Disable</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($card['btemplate'] == 1) {
                                                        ?>
                                                            <a href="./edit_card.php?token=<?php echo $card['btoken']; ?>" class="btn btn-primary"><i class="fa fa-edit me-2"></i></a>
                                                    </td>
                                                <?php
                                                        } else {
                                                ?>
                                                    <a href="./edit_card_02.php?token=<?php echo $card['btoken']; ?>" class="btn btn-primary"><i class="fa fa-edit me-2"></i></a></td>
                                                <?php
                                                        }
                                                ?>
                                                <?php
                                                if ($_SESSION['role'] != '1') {
                                                ?>
                                                    <td><a href="./delete_card.php?token=<?php echo $card['btoken']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this card?')"
                                                            title="Delete"><i class="fa fa-trash me-2"></i></a></td>
                                                <?php
                                                } ?>
                                                <td><a href="qr_generate.php?token=<?php echo $card['btoken']; ?>" target="_blank" class="btn btn-icon icon-left btn-info"><i class="fa fa-qrcode me-2"></i></a></td>
                                                <td>
                                                    <button
                                                        onclick=""
                                                        class="btn btn-icon icon-left btn-warning copy-btn"
                                                        data-id="<?php echo $card['btoken']; ?>" data-template_id="<?php echo $card['btemplate']; ?>"
                                                        title="Copy Share Link">
                                                        <i class="fa fa-clipboard me-2"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($card['btemplate'] == 1) {
                                                    ?>
                                                        <a href="<?php echo BASE_URL; ?>share/card.php?token=<?php echo $card['btoken']; ?>" target="_blank" class="btn btn-icon icon-left btn-success"><i class="fa fa-eye me-2"></i></a>
                                                </td>
                                            <?php
                                                    } else {
                                            ?>
                                                <a href="<?php echo BASE_URL; ?>share/card2.php?token=<?php echo $card['btoken']; ?>" target="_blank" class="btn btn-icon icon-left btn-success"><i class="fa fa-eye me-2"></i></a></td>
                                            <?php
                                                    }
                                            ?>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="8" class="text-center">No Business Cards Found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
include('./includes/footer.php');
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.copy-btn')) {
                const button = e.target.closest('.copy-btn');
                const token = button.getAttribute('data-id');
                const templateId = button.getAttribute('data-template_id');
                if (templateId == 1) {
                    var shareUrl = `${window.location.origin}/Manthan/Project/Business_Card/share/card.php?token=${token}`;
                } else {
                    var shareUrl = `${window.location.origin}/Manthan/Project/Business_Card/share/card2.php?token=${token}`;
                }

                navigator.clipboard.writeText(shareUrl)
                    .then(() => {
                        const icon = button.querySelector('i');
                        console.log('Share link copied to clipboard:', shareUrl);
                        icon.classList.replace('fa-clipboard', 'fa-check');
                        iziToast.show({
                            title: 'Success',
                            message: 'Share link copied to clipboard!',
                            position: 'topCenter'
                        });
                        setTimeout(() => {
                            icon.classList.replace('fa-check', 'fa-clipboard');
                        }, 2000);
                    })
                    .catch(err => {
                        console.error('Copy failed:', err);
                        alert('Error: Could not copy link.');
                    });
            }
        });

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    function card_status_update() {

    }
    $(document).ready(function() {
        $('.card_status').on('change', function() {
            const status = $(this).val();
            const token = $(this).data('id');
            $.ajax({
                url: 'ajax/update_card_status.php',
                method: 'POST',
                data: {
                    status: status,
                    token: token
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating status:', error);
                }
            });

            console.log('Status:', status, 'Token:', token);
        });
    });
</script>