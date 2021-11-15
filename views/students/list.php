<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <h5 class="card-title text-primary">Etudiants</h5>
            </div>
            <div class="col-md-9">
                <div class="row justify-content-end align-items-center">
                    <div class="col-md-4">
                        <select name="choose-role" id="choose-role" class="form-control py-2 mb-0" aria-label="Roles">
                            <option value="all" selected>Toute les promotions</option>
                            <option value="student">Etudiant</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <a href="?action=students/add" class="btn w-100 btn-primary ms-2">
                            Ajouter un étudiant
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <table class="table mt-3">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Email</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Promotion</th>
                <th scope="col">Ajouté le</th>
                <th scope="col" class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr class="user-row">
                    <th scope="row"><?= $user['id'] ?></th>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['last_name'] ?></td>
                    <td><?= $user['first_name'] ?></td>
                    <td><?php echo !empty($user['promotion_name']) ? $user['promotion_name'] : '' ?></td>
                    <td><?= $user['created_at'] ?></td>
                    <td class="text-end">
                        <a href="?action=students/edit&id=<?= $user['id'] ?>" class="btn btn-transparent btn-floating">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button
                                data-mdb-toggle="modal"
                                data-mdb-target="<?= '#delete-modal-' . $user['id'] ?>"
                                data-name="<?= $user['email'] ?>>"
                                class="btn btn-transparent text-danger btn-floating delete-button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach ($users as $user): ?>
            <div class="modal fade"
                 id="<?= 'delete-modal-' . $user['id'] ?>"
                 tabindex="-1"
                 aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Confirmer la suppression
                                <span class="text-danger" id="modal-title"></span>
                            </h5>
                            <button type="button" class="btn-close" data-mdb-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Êtes-vous sur de vouloir supprimer l'étudiant <?= $user['email'] ?> ?
                        </div>
                        <form action="?action=students/delete" method="post" class="modal-footer">
                            <input type="hidden" name="student_id" value="<?= $user['id'] ?>">
                            <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                                Fermer
                            </button>
                            <button type="submit" class="btn btn-primary">Confirmer</button>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>
