<?php
	defined('INSITE') or die('No direct script access allowed');
?>
        </div>
    </main>
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">עריכת משתמש - {username}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editUser" action="/" method="POST">
                        <input type="hidden" name="action" value="users">
                        <input type="hidden" name="sub_action" value="edit_user">
                        <input type="hidden" name="user_id" value="{none}">
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
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.rtlcss.com/bootstrap/v4.5.3/js/bootstrap.bundle.min.js" integrity="sha384-40ix5a3dj6/qaC7tfz0Yr+p9fqWLzzAXiwxVLt9dw7UjQzGYw6rWRhFAnRapuQyK" crossorigin="anonymous"></script>
    <script>
        const App = {
            base_url: "<?php echo $Website->settings->web_url;?>/"
        };
    </script>
    <script src="<?php echo $Website->settings->web_url;?>/assets/<?php echo TEMPLATE_NAME;?>/assets/js/app.js?v=<?php echo time();?>"></script>
</body>