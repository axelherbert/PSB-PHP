<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <h5 class="card-title text-primary">Promotions</h5>
            </div>
            <div class="col-md-9">
                <div class="row justify-content-end align-items-center">
                    <div class="col-md-4">
                        <a href="?action=promotions/add" class="btn w-100 btn-primary ms-2">
                            Ajouter une promotion
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
                <th scope="col">Début</th>
                <th scope="col">Fin</th>
                <th scope="col">Nombre d'étudiants</th>
                <th scope="col">Nombre de professeurs</th>
                <th scope="col">Nombre de matières</th>
                <th scope="col" class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($promotions as $promotion): ?>
                <tr class="user-row" data-role="{{ user.role }}">
                    <th scope="row"><?= $promotion['id'] ?></th>
                    <td><?= $promotion['promotion_name'] ?></td>
                    <td><?= $promotion['promotion_start'] ?></td>
                    <td><?= $promotion['promotion_end'] ?></td>
                    <td><?= $promotion['nbStudents'] ?></td>
                    <td><?= $promotion['nbTeachers'] ?></td>
                    <td><?= $promotion['nbCourses'] ?></td>
                    <td class="text-end">
                        <a href="?action=promotions/edit&id=<?= $promotion['id'] ?>"
                           class="btn btn-transparent btn-floating">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button
                                data-mdb-toggle="modal"
                                data-mdb-target="<?= '#delete-modal-' . $promotion['id'] ?>"
                                data-name="<?= $promotion['promotion_name'] ?>>"
                                class="btn btn-transparent text-danger btn-floating delete-button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach ($promotions as $promotion): ?>
            <div class="modal fade"
                 id="<?= 'delete-modal-' . $promotion['id'] ?>"
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
                            Êtes-vous sur de vouloir supprimer la promotion <?= $promotion['promotion_name'] ?> ?
                        </div>
                        <form action="?action=promotions/delete" method="post" class="modal-footer">
                            <input type="hidden" name="promotion_id" value="<?= $promotion['id'] ?>">
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
