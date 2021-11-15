<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <h5 class="card-title text-primary">Matières</h5>
            </div>
            <div class="col-md-9">
                <div class="row justify-content-end align-items-center">
                    <div class="col-md-4">
                        <a href="?action=courses/add" class="btn w-100 btn-primary ms-2">
                            Ajouter une matière
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <table class="table mt-3">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Coefficient</th>
                <th scope="col">Enseignant</th>
                <th scope="col">Ajouté le</th>
                <th scope="col" class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $course) : ?>
                <tr class="user-row">
                    <th scope="row"><?= $course['id'] ?></th>
                    <td><?= $course['name'] ?></td>
                    <td><?= $course['coefficient'] ?></td>
                    <td><?php echo !empty($course['teacher_email']) ? $course['teacher_email'] : '' ?></td>
                    <td><?= $course['created_at'] ?></td>
                    <td class="text-end">
                        <button data-mdb-toggle="modal" data-mdb-target="<?= '#delete-modal-' . $course['id'] ?>"
                                data-name="<?= $course['name'] ?>>"
                                class="btn btn-transparent text-danger btn-floating delete-button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach ($courses as $course) : ?>
            <div class="modal fade" id="<?= 'delete-modal-' . $course['id'] ?>" tabindex="-1" aria-hidden="true">
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
                            Êtes-vous sur de vouloir supprimer la matière <?= $course['name'] ?> ?
                        </div>
                        <form action="?action=courses/delete" method="post" class="modal-footer">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
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