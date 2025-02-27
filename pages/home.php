<?php
	defined('INSITE') or die('No direct script access allowed');
    require TEMPLATE_DIR.'header.php';
    $Account = new Account;
    $users = $Account->GetUsers();
?>

<div class="shadow-lg p-5">
    <h1>Aimpact</h1>
    <?php
        echo Message('info', 'שיוך לתוכנית 561 אוטומטית לאחר יצירת משתמש');
    ?>
    <div class="row align-items-center justify-content-center">
        <div class="col-md-12 col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="addUser-tab" data-toggle="tab" data-target="#addUser" type="button" role="tab" aria-controls="addUser" aria-selected="true">הוספת משתמש</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manage-tab" data-toggle="tab" data-target="#manage" type="button" role="tab" aria-controls="manage" aria-selected="false">ניהול משתמשים</button>
                </li>
            </ul>
            <div class="tab-content" id="tabContent">
                <div class="tab-pane fade show active" id="addUser" role="tabpanel" aria-labelledby="addUser-tab">
                    <form id="createUser" action="/" method="POST">
                        <input type="hidden" name="action" value="users">
                        <input type="hidden" name="sub_action" value="create_user">
                        <div class="mb-3 text-left">
                            <label for="first_name" class="form-label">שם פרטי</label>
                            <input type="text" minlength="2" class="form-control" placeholder="בשדה זה הקלד את השם הפרטי שלך" name="first_name" id="first_name" required />
                        </div>
                        <div class="mb-3 text-left">
                            <label for="last_name" class="form-label">שם משפחה</label>
                            <input type="text" minlength="2" class="form-control" placeholder="בשדה זה הקלד את שם המשפחה שלך" name="last_name" id="last_name" required />
                        </div>
                        <div class="mb-3 text-left">
                            <label for="phone" class="form-label">מספר טלפון</label>
                            <input type="text" minlength="10" maxlength="10" patten="05\d{8}$" title="מספר טלפון ישראלי בלבד (מתחיל ב0)" placeholder="בשדה זה, הקלד את מספר הטלפון שלך" class="form-control" name="phone" id="phone" required />
                        </div>
                        <div class="col-md-4 col-8 m-auto">
                            <button type="submit" class="btn btn-custom w-100">המשך</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="manage" role="tabpanel" aria-labelledby="manage-tab">
                    <?php 
                        if(is_array($users)) { 
                    ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">שם פרטי</th>
                                        <th scope="col">שם משפחה</th>
                                        <th scope="col">מספר טלפון</th>
                                        <th scope="col">נוצר בתאריך</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach($users as $key => $val) { 
                                    ?>
                                            <tr id="user-<?php echo (int)$val['id'];?>">
                                                <th scope="row"><?php echo (int)$key+1;?>.</th>
                                                <td><?php echo htmlspecialchars($val['first_name']);?></td>
                                                <td><?php echo htmlspecialchars($val['last_name']);?></td>
                                                <td><?php echo htmlspecialchars($val['phone_number']);?></td>
                                                <td><?php echo date("d-m-Y H:i", $val['createdAt']);?></td>
                                                <td>
                                                    <div class="row align-items-center justify-content-center">
                                                        <div class="col-md-4 col-12">
                                                            <div class="btn btn-danger deleteUser" data-user="<?php echo (int)($val['id']);?>">
                                                                מחיקה
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <div class="btn btn-secondary syncUser"data-user="<?php echo (int)($val['id']);?>">
                                                                שיוך לתוכנית
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <div class="btn btn-primary editUser" data-user="<?php echo (int)($val['id']);?>">
                                                                עריכה
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php 
                                        } unset($users, $Account); 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                        } else {
                            echo Message('info', 'לא נמצאו משתמשים לוקאלית');
                        } 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require TEMPLATE_DIR.'footer.php';
