<!-- Modal -->
<div class="modal fade" id="employees_accounts" tabindex="-1" role="dialog" aria-labelledby="employeeInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeInfoModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo $path_parts['basename'];?>" method="POST" id="userInfo">
                    <div class="form-group">
                        <label for="employee_id">Employee ID</label>
                        <input type="input" class="form-control" id="edit_employee_id" name="edit_employee_id" readonly>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="edit_roles" id="edit_admin" value="admin">
                          <label class="form-check-label" for="exampleRadios1">
                            Admin
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="edit_roles" id="edit_agent" value="agent">
                          <label class="form-check-label" for="exampleRadios2">
                            Agent
                          </label>
                        </div>
                    </div> 

                        <label for="edit_employee_fName">First Name</label>
                        <input type="input" class="form-control" id="edit_employee_fName" name="edit_employee_fName">
                        <label for="edit_employee_mName">First Name</label>
                        <input type="input" class="form-control" id="edit_employee_mName" name="edit_employee_mName">
                        <label for="edit_employee_lName">First Name</label>
                        <input type="input" class="form-control" id="edit_employee_lName" name="edit_employee_lName">
                        <label for="edit_employee_email">First Name</label>
                        <input type="email" class="form-control" id="edit_employee_email" name="edit_employee_email">
                    
                </form>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="submit" class="btn btn-primary mr-auto" form="userInfo" name="editAccount">Save changes</button>
                <button type="submit" class="btn btn-danger" form="userInfo" name="deleteAccount">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>