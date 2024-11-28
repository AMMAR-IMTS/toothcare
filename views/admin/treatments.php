<?php
require_once('../layouts/header.php');
include BASE_PATH . '/models/Treatment.php';

$TreatmentModel = new Treatment();
$data = $TreatmentModel->getAll();

if ($permission != 'operator') dd('Access Denied...!');

?>

<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Treatments
 <!-- Button trigger modal -->
 <button
            type="button"
            class="btn btn-primary float-end"
            data-bs-toggle="modal"
            data-bs-target="#createUser">
            Add New Treatments
        </button>
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Treatments</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Treatment Fee</th>
                        <th>Registration Fee</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php
                    foreach ($data as $key => $t) {
                    ?>
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?= $t['name'] ?? '' ?></strong></td>
                            <td><?= $t['description'] ?? '' ?></td>
                            <td class="text-right">LKR <?= number_format($t['treatment_fee'], 2) ?? 0; ?> </td>
                            <td class="text-right">LKR <?= number_format($t['registration_fee'], 2) ?? 0; ?> </td>
                            <td>
                                <?php if ($t['is_active'] == 1) { ?>
                                    <span class="badge bg-success">Active</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">In Active</span>
                                <?php } ?>
                            </td>
                            <td>
                                
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">

                                            <a class="dropdown-item edit-treatment-btn" data-id="<?= $user['id']; ?>"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <a class="dropdown-item delete-treatment-btn" data-permission="<?= $user['permission']; ?>" data-id="<?= $user['id']; ?>"><i class="bx bx-trash me-1"></i> Delete</a>

                                        </div>
                                    </div>
                                
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

    <hr class="my-5" />


</div>

<!-- / Content -->

<!-- Modal -->
<div class="modal fade" id="createUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="create-form" action="<?= url('services/ajax_functions.php') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Add New Treatments</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="create_Treatment">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="Treatments_name" class="form-label">Treatments Name</label>
                            <input
                                type="text"
                                required
                                id="Treatments_name"
                                name="Treatments_name"
                                class="form-control"
                                placeholder="Enter Treatments Name" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col mb-3">
                            <label for="Description" class="form-label">Description</label>
                            <input
                                required
                                type="text"
                                name="Description"
                                id="Description"
                                class="form-control"
                                placeholder="Enter Description" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col mb-3">
                            <label for="Treatment_Fee" class="form-label">Treatment Fee</label>
                            <input
                                required
                                type="number"
                                name="Treatment_Fee"
                                id="Treatment_Fee"
                                class="form-control"
                                placeholder="Enter Treatment Fee" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col mb-3">
                            <label for="Registration_Fee" class="form-label">Registration Fee</label>
                            <input
                                required
                                type="number"
                                name="Registration_Fee"
                                id="Registration_Fee"
                                class="form-control"
                                placeholder="Enter Registration Fee" />
                        </div>
                    </div>


                    
                    <div class="row ">
                        <div class="mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Status</label>
                            <select class="form-select" id="permission" aria-label="Default select example" name="permission" required>
                                <option value="Active">Active</option>
                                <option value="Disactive">Disactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <div id="additional-fields">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <div id="alert-container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="create">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Udpate Modal -->
<div class="modal fade" id="edit-treatment-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="update-form" action="<?= url('services/ajax_functions.php') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Update Treatment</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_treatment">
                    <input type="hidden" id="user_id" name="id" value="">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameWithTitle" class="form-label">Treatment Name</label>
                            <input
                                type="text"
                                required
                                id="treatment_name"
                                name="treatment_name"
                                class="form-control"
                                placeholder="Enter Treatment Name" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col mb-3">
                            <label for="Description" class="form-label">Description</label>
                            <input
                                required
                                type="text"
                                name="Description"
                                id="Description"
                                class="form-control"
                                placeholder="Enter Description" />
                        </div>
                    </div>


                    <div class="row ">
                        <div class="col mb-3">
                            <label for="Treatment_Fee" class="form-label">Treatment Fee</label>
                            <input
                                required
                                type="number"
                                name="Treatment_Fee"
                                id="Treatment_Fee"
                                class="form-control"
                                placeholder="Enter Treatment Fee" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col mb-3">
                            <label for="Registration_Fee" class="form-label">Registration Fee</label>
                            <input
                                required
                                type="number"
                                name="Registration_Fee"
                                id="Registration_Fee"
                                class="form-control"
                                placeholder="Enter Registration Fee" />
                        </div>
                    </div>
                    <div class="row ">
                        <div class="mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Status</label>
                            <select class="form-select" id="is_active" aria-label="Default select example" id="is_active" name="is_active" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div id="edit-additional-fields">
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div id="edit-alert-container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="update_treatment">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
require_once('../layouts/footer.php');
?>
<script src="<?= asset('assets/forms-js/treatment.js') ?>"></script>