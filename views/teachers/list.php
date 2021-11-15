<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <h5 class="card-title text-primary">Professeurs</h5>
            </div>
            <div class="col-md-9">
                <div class="row justify-content-end align-items-center">
                    <div class="col-md-4">
                        <a href="?action=teachers/add" class="btn w-100 btn-primary ms-2">
                            Ajouter un professeur
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
            <?php foreach ($teachers as $teacher): ?>
                <tr class="user-row">
                    <th scope="row"><?= $teacher['id'] ?></th>
                    <td><?= $teacher['email'] ?></td>
                    <td><?= $teacher['last_name'] ?></td>
                    <td><?= $teacher['first_name'] ?></td>
                    <td><?php echo !empty($teacher['promotion_name']) ? $teacher['promotion_name'] : '' ?></td>
                    <td><?= $teacher['created_at'] ?></td>
                    <td class="text-end">
                        <a href="?action=teachers/edit&id=<?= $teacher['id'] ?>" class="btn btn-transparent btn-floating">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button
                            data-mdb-toggle="modal"
                            data-mdb-target="<?= '#delete-modal-' . $teacher['id'] ?>"
                            data-name="<?= $teacher['email'] ?>>"
                            class="btn btn-transparent text-danger btn-floating delete-button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach ($teachers as $teacher): ?>
            <div class="modal fade"
                 id="<?= 'delete-modal-' . $teacher['id'] ?>"
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
                            Êtes-vous sur de vouloir supprimer le professeur <?= $teacher['email'] ?> ?
                        </div>
                        <form action="?action=teachers/delete" method="post" class="modal-footer">
                            <input type="hidden" name="teacher_id" value="<?= $teacher['id'] ?>">
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
